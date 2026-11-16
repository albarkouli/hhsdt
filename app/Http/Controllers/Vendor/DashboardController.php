<?php

namespace App\Http\Controllers\Vendor;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $vendor = auth()->user()->vendor;

        abort_if(! $vendor, 403, 'No vendor profile linked to your account.');

        // === Counters ===
        $totalProducts = $vendor->products()->count();
        $activeProducts = $vendor->products()->where('is_active', true)->count();
        $pendingOrders = $vendor->orders()->where('status', OrderStatus::Pending)->count();
        $totalOrders = $vendor->orders()->count();

        // === Revenue (excl. cancelled) ===
        $totalRevenue = (float) $vendor->orders()
            ->where('status', '!=', OrderStatus::Cancelled)
            ->sum('total');

        $deliveredRevenue = (float) $vendor->orders()
            ->where('status', OrderStatus::Delivered)
            ->sum('total');

        // === Last 7 days sales ===
        $salesLast7 = $vendor->orders()
            ->where('status', '!=', OrderStatus::Cancelled)
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as day, SUM(total) as revenue, COUNT(*) as orders')
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $chartLabels = [];
        $chartRevenue = [];
        $chartOrders = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('D');
            $chartRevenue[] = (float) ($salesLast7[$day]->revenue ?? 0);
            $chartOrders[] = (int) ($salesLast7[$day]->orders ?? 0);
        }

        // === Order status breakdown ===
        $statusCounts = $vendor->orders()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $orderStatusBreakdown = [];
        foreach (['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'] as $s) {
            $orderStatusBreakdown[$s] = (int) ($statusCounts[$s] ?? 0);
        }

        // === Top products (by units sold) ===
        $topProducts = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('order_items.vendor_id', $vendor->id)
            ->where('orders.status', '!=', OrderStatus::Cancelled->value)
            ->select(
                'order_items.product_name',
                DB::raw('SUM(order_items.quantity) as units_sold'),
                DB::raw('SUM(order_items.subtotal) as revenue')
            )
            ->groupBy('order_items.product_name')
            ->orderByDesc('units_sold')
            ->limit(5)
            ->get();

        // === Recent orders ===
        $recentOrders = $vendor->orders()
            ->with(['user:id,name'])
            ->latest()
            ->limit(5)
            ->get();

        // === Low stock products ===
        $lowStockProducts = $vendor->products()
            ->where('is_active', true)
            ->where('stock', '<', 5)
            ->orderBy('stock')
            ->limit(5)
            ->get(['id', 'name', 'stock']);

        return view('vendor.dashboard', compact(
            'vendor',
            'totalProducts',
            'activeProducts',
            'pendingOrders',
            'totalOrders',
            'totalRevenue',
            'deliveredRevenue',
            'chartLabels',
            'chartRevenue',
            'chartOrders',
            'orderStatusBreakdown',
            'topProducts',
            'recentOrders',
            'lowStockProducts',
        ));
    }
}