<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $status   = $request->query('status');
        $vendorId = $request->query('vendor');

        $orders = Order::query()
            ->with(['user:id,name,email', 'vendor:id,name'])
            ->when(
                in_array($status, ['pending','confirmed','shipped','delivered','cancelled'], true),
                fn ($q) => $q->where('status', $status)
            )
            ->when($vendorId, fn ($q) => $q->where('vendor_id', $vendorId))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders'   => $orders,
            'status'   => $status,
            'vendorId' => $vendorId,
            'vendors'  => Vendor::orderBy('name')->get(['id', 'name']),
            'counts'   => [
                'pending'   => Order::where('status', OrderStatus::Pending)->count(),
                'confirmed' => Order::where('status', OrderStatus::Confirmed)->count(),
                'shipped'   => Order::where('status', OrderStatus::Shipped)->count(),
                'delivered' => Order::where('status', OrderStatus::Delivered)->count(),
                'cancelled' => Order::where('status', OrderStatus::Cancelled)->count(),
            ],
        ]);
    }

    public function show(Order $order): View
    {
        $order->load(['user:id,name,email', 'vendor:id,name,phone', 'items.product:id,name']);

        return view('admin.orders.show', compact('order'));
    }

    public function cancel(Order $order): RedirectResponse
    {
        if (! $order->isCancellable()) {
            return back()->with('error', 'This order cannot be cancelled (already shipped or final).');
        }

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
        });

        return back()->with('status', 'Order cancelled and stock restored.');
    }
}