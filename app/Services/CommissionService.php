<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PlatformEarning;
use Illuminate\Support\Facades\DB;

class CommissionService
{
    /**
     * Calculate and store commission for a delivered order.
     * Called once when order status becomes 'delivered'.
     */
    public function recordForOrder(Order $order): void
    {
        if ($order->status->value !== 'delivered') {
            return;
        }

        // Idempotent: only record once
        if (PlatformEarning::where('order_id', $order->id)->exists()) {
            return;
        }

        $rate = (float) ($order->vendor?->commission_rate ?? 5.00);
        $netRevenue = max(0, (float) $order->subtotal - (float) $order->discount);

        $commission = round($netRevenue * ($rate / 100), 2);
        $vendorNet  = round($netRevenue - $commission, 2);

        DB::transaction(function () use ($order, $rate, $commission, $vendorNet, $netRevenue) {
            $order->update([
                'commission_rate'   => $rate,
                'commission_amount' => $commission,
                'vendor_net'        => $vendorNet,
            ]);

            PlatformEarning::create([
                'order_id'        => $order->id,
                'vendor_id'       => $order->vendor_id,
                'order_total'     => $netRevenue,
                'commission_rate' => $rate,
                'amount'          => $commission,
                'status'          => 'pending',
            ]);
        });
    }

    public function cancelForOrder(Order $order): void
    {
        PlatformEarning::where('order_id', $order->id)->delete();
        $order->update([
            'commission_rate'   => 0,
            'commission_amount' => 0,
            'vendor_net'        => 0,
        ]);
    }
}