<?php

namespace App\Http\Controllers\Shop;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\CheckoutRequest;
use App\Notifications\OrderModificationAccepted;
use App\Notifications\OrderModificationProposed;
use App\Notifications\OrderModificationRejected;
use App\Notifications\OrderPlaced;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\CartService;
use App\Services\CouponService;
use App\Services\InvoiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;

class OrderController extends Controller
{
    public function checkout(CartService $cart): View|RedirectResponse
    {
        $items = $cart->detailed();

        if ($items->isEmpty()) {
            return redirect()->route('shop.cart.index')->with('cart_error', 'Your cart is empty.');
        }

        $invisible = $items->reject(fn ($i) => $i->is_visible);
        if ($invisible->isNotEmpty()) {
            return redirect()->route('shop.cart.index')
                ->with('cart_error', 'Some items are no longer available. Please review your cart.');
        }

        $groups = $items->groupBy(fn ($i) => $i->product->vendor_id);

        $coupon      = $cart->getCoupon();
        $discount    = $cart->discount();
        $grandTotal  = $cart->total();

        return view('shop.checkout.index', [
            'coupon'     => $coupon,
            'discount'   => $discount,
            'grandTotal' => $grandTotal,
            'items'    => $items,
            'groups'   => $groups,
            'subtotal' => $cart->subtotal(),
            'user'     => auth()->user(),
        ]);
    }

    public function store(CheckoutRequest $request, CartService $cart): RedirectResponse
    {
        $items = $cart->detailed();

        if ($items->isEmpty()) {
            return redirect()->route('shop.cart.index')->with('cart_error', 'Your cart is empty.');
        }

        $invisible = $items->reject(fn ($i) => $i->is_visible);
        if ($invisible->isNotEmpty()) {
            return redirect()->route('shop.cart.index')
                ->with('cart_error', 'Some items are no longer available. Please review your cart.');
        }

        try {
            $orders = DB::transaction(function () use ($items, $request) {
                $created = [];
                $groups  = $items->groupBy(fn ($i) => $i->product->vendor_id);

                foreach ($groups as $vendorId => $groupItems) {
                    foreach ($groupItems as $item) {
                        $affected = Product::query()
                            ->where('id', $item->product->id)
                            ->where('is_active', true)
                            ->where('stock', '>=', $item->quantity)
                            ->decrement('stock', $item->quantity);

                        if ($affected === 0) {
                            throw new RuntimeException(
                                "Stock for \"{$item->product->name}\" is no longer sufficient."
                            );
                        }
                    }

                    $subtotal = $groupItems->sum('subtotal');

                    $order = Order::create([
                        'order_number'     => Order::generateOrderNumber(),
                        'user_id'          => auth()->id(),
                        'vendor_id'        => $vendorId,
                        'status'           => OrderStatus::Pending,
                        'payment_method'   => 'cash_on_delivery',
                        'subtotal'         => $subtotal,
                        'shipping_cost'    => 0,
                        'total'            => $subtotal,
                        'shipping_name'    => $request->input('shipping_name'),
                        'shipping_phone'   => $request->input('shipping_phone'),
                        'shipping_city'    => $request->input('shipping_city'),
                        'shipping_address' => $request->input('shipping_address'),
                        'notes'            => $request->input('notes'),
                    ]);

                    foreach ($groupItems as $item) {
                        OrderItem::create([
                            'order_id'     => $order->id,
                            'product_id'   => $item->product->id,
                            'vendor_id'    => $vendorId,
                            'product_name' => $item->product->name,
                            'price'        => $item->product->price,
                            'quantity'     => $item->quantity,
                            'subtotal'     => $item->subtotal,
                        ]);
                    }

                    // Notify the vendor
                    $order->vendor->owner?->notify(new OrderPlaced($order));

                    $created[] = $order;
                }

                return $created;
            });
        } catch (RuntimeException $e) {
            return redirect()->route('shop.cart.index')->with('cart_error', $e->getMessage());
        }

        // Record coupon usage before clearing cart session
        $coupon = $cart->getCoupon();
        $discount = $cart->discount();
        $grandTotal = $cart->total();
        $subtotalBeforeDiscount = $cart->subtotal();

        if ($coupon && $discount > 0) {
            // Distribute discount proportionally across orders
            $totalSubtotal = collect($orders)->sum(fn ($o) => (float) $o->subtotal);

            foreach ($orders as $order) {
                $proportion = $totalSubtotal > 0 ? ((float) $order->subtotal / $totalSubtotal) : 0;
                $orderDiscount = round($discount * $proportion, 2);

                $order->update([
                    'coupon_id'    => $coupon->id,
                    'coupon_code'  => $coupon->code,
                    'discount'     => $orderDiscount,
                    'total'        => max(0, (float) $order->subtotal - $orderDiscount),
                ]);
            }

            app(CouponService::class)->recordUsage($coupon, auth()->id(), $orders[0]->id, $discount);
        }

        $cart->clear();
        $cart->removeCoupon();

        // Send email notifications (non-blocking, doesn't fail the order if it errors)
        foreach ($orders as $createdOrder) {
            $createdOrder->sendNotifications();
        }

        $message = count($orders) === 1
            ? 'Order placed successfully!'
            : count($orders) . ' orders placed (one per vendor).';

        return redirect()->route('shop.orders.index')->with('cart_status', $message);
    }

    public function index(): View
    {
        $orders = auth()->user()->orders()
            ->with(['vendor:id,name,slug', 'items'])
            ->latest()
            ->paginate(10);

        return view('shop.orders.index', compact('orders'));
    }

    public function review(Order $order): View
    {
        abort_unless($order->user_id === auth()->id(), 403);

        if ($order->status !== OrderStatus::PendingCustomerApproval) {
            abort(404);
        }

        $modification = $order->modifications()->where('status', 'pending')->latest()->first();
        abort_if(! $modification, 404);

        $order->load(['vendor:id,name,slug', 'items']);

        return view('shop.orders.review', compact('order', 'modification'));
    }

        public function acceptModification(Order $order): RedirectResponse
    {
        abort_unless($order->user_id === auth()->id(), 403);

        if ($order->status !== OrderStatus::PendingCustomerApproval) {
            return redirect()->route('shop.orders.show', $order)
                ->with('cart_error', 'This order is no longer awaiting review.');
        }

        $modification = $order->modifications()->where('status', 'pending')->latest()->first();
        if (! $modification) {
            return redirect()->route('shop.orders.show', $order)
                ->with('cart_error', 'No pending modification found.');
        }

        try {
            DB::transaction(function () use ($order, $modification) {
                $modification = $modification->fresh();
                if (! $modification || $modification->status !== 'pending') {
                    throw new \RuntimeException('This modification was already processed.');
                }

                foreach ($modification->proposed_items as $proposedItem) {
                    $orderItem = $order->items()->find($proposedItem['id']);
                    if (! $orderItem || ! $orderItem->product_id) {
                        continue;
                    }

                    $affected = Product::query()
                        ->where('id', $orderItem->product_id)
                        ->where('is_active', true)
                        ->where('stock', '>=', $proposedItem['quantity'])
                        ->decrement('stock', $proposedItem['quantity']);

                    if ($affected === 0) {
                        throw new \RuntimeException("Stock for \"{$orderItem->product_name}\" is no longer sufficient. Please reject instead.");
                    }
                }

                foreach ($modification->proposed_items as $proposedItem) {
                    $order->items()
                        ->where('id', $proposedItem['id'])
                        ->update([
                            'quantity' => $proposedItem['quantity'],
                            'subtotal' => $proposedItem['subtotal'],
                        ]);
                }

                $proposedIds = collect($modification->proposed_items)->pluck('id')->toArray();
                $order->items()->whereNotIn('id', $proposedIds)->delete();

                $newSubtotal = (float) collect($modification->proposed_items)->sum('subtotal');
                $order->update([
                    'subtotal'     => $newSubtotal,
                    'total'        => $newSubtotal,
                    'status'       => OrderStatus::Pending,
                    'confirmed_at' => null,
                ]);

                $modification->markAccepted();
            });

            $order->fresh()->vendor->owner?->notify(new OrderModificationAccepted($order));
        } catch (\RuntimeException $e) {
            return redirect()->route('shop.orders.review', $order)->with('cart_error', $e->getMessage());
        } catch (\Throwable $e) {
            \Log::error('Accept modification failed', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return redirect()->route('shop.orders.review', $order)
                ->with('cart_error', 'Something went wrong. Please try again.');
        }

        return redirect()
            ->route('shop.orders.show', $order)
            ->with('cart_status', 'You accepted the proposed changes. The vendor will confirm your order shortly.');
    }

        public function rejectModification(Order $order): RedirectResponse
    {
        abort_unless($order->user_id === auth()->id(), 403);

        if ($order->status !== OrderStatus::PendingCustomerApproval) {
            return redirect()->route('shop.orders.show', $order)
                ->with('cart_error', 'This order is no longer awaiting review.');
        }

        $modification = $order->modifications()->where('status', 'pending')->latest()->first();
        if (! $modification) {
            return redirect()->route('shop.orders.show', $order)
                ->with('cart_error', 'No pending modification found.');
        }

        try {
            DB::transaction(function () use ($order, $modification) {
                $modification = $modification->fresh();
                if (! $modification || $modification->status !== 'pending') {
                    throw new \RuntimeException('This modification was already processed.');
                }

                $modification->markRejected();

                $order->update([
                    'status'       => OrderStatus::Cancelled,
                    'cancelled_at' => now(),
                ]);

                app(\App\Services\InvoiceService::class)->cancel($order);
            });

            $order->vendor->owner?->notify(new OrderModificationRejected($order));
        } catch (\Throwable $e) {
            \Log::error('Reject modification failed', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return redirect()->route('shop.orders.show', $order)
                ->with('cart_error', 'Something went wrong. Please try again.');
        }

        return redirect()
            ->route('shop.orders.show', $order)
            ->with('cart_status', 'You rejected the proposed changes. The order has been cancelled.');
    }

    public function show(Order $order): View
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load(['vendor:id,name,slug,phone', 'items']);

        return view('shop.orders.show', compact('order'));
    }
}