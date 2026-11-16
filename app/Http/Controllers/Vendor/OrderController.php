<?php

namespace App\Http\Controllers\Vendor;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderModification;
use App\Models\Product;
use App\Notifications\OrderModificationProposed;
use App\Notifications\OrderStatusChanged;
use App\Services\InvoiceService;
use App\Services\CommissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $vendor = auth()->user()->vendor;
        abort_if(! $vendor, 403, 'No vendor profile.');

        $status = $request->query('status');

        $orders = $vendor->orders()
            ->with(['user:id,name,email', 'items'])
            ->when(
                in_array($status, ['pending', 'pending_customer_approval', 'confirmed', 'shipped', 'delivered', 'cancelled'], true),
                fn ($q) => $q->where('status', $status)
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'pending'                  => $vendor->orders()->where('status', OrderStatus::Pending)->count(),
            'pending_customer_approval' => $vendor->orders()->where('status', OrderStatus::PendingCustomerApproval)->count(),
            'confirmed'                => $vendor->orders()->where('status', OrderStatus::Confirmed)->count(),
            'shipped'                  => $vendor->orders()->where('status', OrderStatus::Shipped)->count(),
            'delivered'                => $vendor->orders()->where('status', OrderStatus::Delivered)->count(),
            'cancelled'                => $vendor->orders()->where('status', OrderStatus::Cancelled)->count(),
        ];

        return view('vendor.orders.index', compact('vendor', 'orders', 'status', 'counts'));
    }

    public function show(Order $order): View
    {
        $this->authorizeOwnership($order);
        $order->load(['user:id,name,email', 'items.product:id,name']);
        return view('vendor.orders.show', compact('order'));
    }

    public function confirm(Order $order): RedirectResponse
    {
        $this->authorizeOwnership($order);

        if ($order->status !== OrderStatus::Pending) {
            return back()->with('error', 'Only pending orders can be confirmed.');
        }

        try {
            DB::transaction(function () use ($order) {
                $order->update([
                    'status'       => OrderStatus::Confirmed,
                    'confirmed_at' => now(),
                ]);

                app(InvoiceService::class)->issueOrRefresh($order);
            });

            $order->user->notify(new OrderStatusChanged($order));
        } catch (Throwable $e) {
            Log::error('Confirm order failed', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to confirm order. Please try again.');
        }

        return back()->with('status', 'Order confirmed.');
    }

    public function ship(Order $order): RedirectResponse
    {
        $this->authorizeOwnership($order);

        if ($order->status !== OrderStatus::Confirmed) {
            return back()->with('error', 'Only confirmed orders can be shipped.');
        }

        try {
            $order->update([
                'status'     => OrderStatus::Shipped,
                'shipped_at' => now(),
            ]);

            $order->user->notify(new OrderStatusChanged($order));
        } catch (Throwable $e) {
            Log::error('Ship order failed', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to ship order.');
        }

        return back()->with('status', 'Order marked as shipped.');
    }

    public function deliver(Order $order): RedirectResponse
    {
        $this->authorizeOwnership($order);

        if ($order->status !== OrderStatus::Shipped) {
            return back()->with('error', 'Only shipped orders can be delivered.');
        }

        try {
            $order->update([
                'status'       => OrderStatus::Delivered,
                'delivered_at' => now(),
            ]);

            $order->user->notify(new OrderStatusChanged($order));

            // Record platform commission (idempotent)
            app(CommissionService::class)->recordForOrder($order->fresh());
        } catch (Throwable $e) {
            Log::error('Deliver order failed', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to deliver order.');
        }

        return back()->with('status', 'Order marked as delivered.');
    }

    public function cancel(Order $order): RedirectResponse
    {
        $this->authorizeOwnership($order);

        if (! $order->isCancellable()) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        try {
            DB::transaction(function () use ($order) {
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                    }
                }

                $order->update([
                    'status'       => OrderStatus::Cancelled,
                    'cancelled_at' => now(),
                ]);

                app(InvoiceService::class)->cancel($order);
            });

            $order->user->notify(new OrderStatusChanged($order));

            // Reverse commission if it was recorded
            app(CommissionService::class)->cancelForOrder($order->fresh());
        } catch (Throwable $e) {
            Log::error('Cancel order failed', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to cancel order.');
        }

        return back()->with('status', 'Order cancelled and stock restored.');
    }

    public function showModifyForm(Order $order): View|RedirectResponse
    {
        $this->authorizeOwnership($order);

        if (! in_array($order->status, [OrderStatus::Pending, OrderStatus::Confirmed], true)) {
            return back()->with('error', 'Only pending or confirmed orders can be modified.');
        }

        if ($order->hasPendingModification()) {
            return back()->with('error', 'A modification is already pending customer approval.');
        }

        $order->load('items.product:id,name,stock');

        return view('vendor.orders.modify', compact('order'));
    }

    public function proposeModification(Request $request, Order $order): RedirectResponse
    {
        $this->authorizeOwnership($order);

        if (! in_array($order->status, [OrderStatus::Pending, OrderStatus::Confirmed], true)) {
            return back()->with('error', 'Only pending or confirmed orders can be modified.');
        }

        $validated = $request->validate([
            'note'             => ['required', 'string', 'min:5', 'max:500'],
            'items'            => ['required', 'array', 'min:1'],
            'items.*.id'       => ['required', 'integer'],
            'items.*.quantity' => ['required', 'integer', 'min:0', 'max:1000'],
        ]);

        try {
            $order->load('items');

            if ($order->hasPendingModification()) {
                return back()->with('error', 'A modification is already pending customer approval.');
            }

            $orderItemIds = $order->items->pluck('id')->toArray();
            $submittedIds = collect($validated['items'])->pluck('id')->toArray();

            if (array_diff($submittedIds, $orderItemIds) !== []) {
                return back()->with('error', 'One or more items do not belong to this order.');
            }

            $originalItems = [];
            $proposedItems = [];
            $proposedTotal = 0.0;
            $newQtyById    = collect($validated['items'])->keyBy('id')->map(fn ($i) => (int) $i['quantity']);

            foreach ($order->items as $item) {
                $originalItems[] = [
                    'id'       => $item->id,
                    'name'     => $item->product_name,
                    'price'    => (float) $item->price,
                    'quantity' => (int) $item->quantity,
                    'subtotal' => (float) $item->subtotal,
                ];

                $newQty = min($newQtyById[$item->id] ?? $item->quantity, $item->quantity);

                if ($newQty > 0) {
                    $newSubtotal = (float) $item->price * $newQty;
                    $proposedItems[] = [
                        'id'       => $item->id,
                        'name'     => $item->product_name,
                        'price'    => (float) $item->price,
                        'quantity' => $newQty,
                        'subtotal' => $newSubtotal,
                    ];
                    $proposedTotal += $newSubtotal;
                }
            }

            if (empty($proposedItems)) {
                return back()->with('error', 'At least one item must have quantity > 0. Use Cancel instead.');
            }

            $originalTotal = (float) $order->subtotal;

            if (abs($originalTotal - $proposedTotal) < 0.01) {
                return back()->with('error', 'Proposed total is identical to the original. No change to propose.');
            }

            DB::transaction(function () use ($order, $validated, $originalItems, $proposedItems, $originalTotal, $proposedTotal) {
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                    }
                }

                $modification = OrderModification::create([
                    'order_id'       => $order->id,
                    'proposed_by'    => auth()->id(),
                    'original_items' => $originalItems,
                    'proposed_items' => $proposedItems,
                    'original_total' => $originalTotal,
                    'proposed_total' => $proposedTotal,
                    'note'           => $validated['note'],
                    'status'         => 'pending',
                ]);

                $order->update(['status' => OrderStatus::PendingCustomerApproval]);

                $order->user->notify(new OrderModificationProposed($order, $modification));
            });
        } catch (Throwable $e) {
            Log::error('Propose modification failed', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to propose modification.');
        }

        return redirect()
            ->route('vendor.orders.show', $order)
            ->with('status', 'Modification proposed. Awaiting customer approval.');
    }

    private function authorizeOwnership(Order $order): void
    {
        abort_if(
            $order->vendor_id !== auth()->user()->vendor?->id,
            403,
            'You do not own this order.'
        );
    }
}