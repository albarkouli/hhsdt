<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    /**
     * Issue (or refresh) an invoice for the given order.
     * Called at first confirmation and after any accepted modification.
     */
    public function issueOrRefresh(Order $order): Invoice
    {
        return DB::transaction(function () use ($order) {
            $order->load(['items', 'vendor', 'user']);

            $snapshot = $this->buildSnapshot($order);

            $existing = Invoice::where('order_id', $order->id)->lockForUpdate()->first();

            if ($existing) {
                // Refresh same invoice number, bump version
                $existing->update([
                    'snapshot' => $snapshot,
                    'version'  => $existing->version + 1,
                    'status'   => 'issued',
                ]);

                return $existing;
            }

            // Generate new sequential number for current year
            $year = now()->year;
            $last = Invoice::lockForUpdate()->orderByDesc('sequence_number')->first();
            $next = ($last?->sequence_number ?? 0) + 1;

            $invoiceNumber = sprintf('INV-%d-%06d', $year, $next);

            return Invoice::create([
                'order_id'        => $order->id,
                'user_id'         => $order->user_id,
                'vendor_id'       => $order->vendor_id,
                'invoice_number'  => $invoiceNumber,
                'sequence_number' => $next,
                'status'          => 'issued',
                'version'         => 1,
                'snapshot'        => $snapshot,
                'issued_at'       => now(),
            ]);
        });
    }

    public function cancel(Order $order): void
    {
        Invoice::where('order_id', $order->id)->update(['status' => 'cancelled']);
    }

    private function buildSnapshot(Order $order): array
    {
        return [
            'order_number'   => $order->order_number,
            'payment_method' => $order->payment_method,
            'seller' => [
                'name'    => $order->vendor->name,
                'address' => $order->vendor->address,
                'city'    => $order->vendor->city,
                'phone'   => $order->vendor->phone,
                'email'   => $order->vendor->email,
                'logo'    => $order->vendor->logo,
            ],
            'buyer' => [
                'name'    => $order->shipping_name,
                'phone'   => $order->shipping_phone,
                'city'    => $order->shipping_city,
                'address' => $order->shipping_address,
            ],
            'items' => $order->items->map(fn ($item) => [
                'name'     => $item->product_name,
                'price'    => (float) $item->price,
                'quantity' => (int) $item->quantity,
                'subtotal' => (float) $item->subtotal,
            ])->toArray(),
            'totals' => [
                'subtotal'      => (float) $order->subtotal,
                'shipping_cost' => (float) $order->shipping_cost,
                'total'         => (float) $order->total,
            ],
            'notes'      => $order->notes,
            'order_date' => $order->created_at->toIso8601String(),
        ];
    }
}