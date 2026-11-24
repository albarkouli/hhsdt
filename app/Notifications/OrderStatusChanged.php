<?php

namespace App\Notifications;

use App\Enums\OrderStatus;
use App\Models\Invoice;
use App\Models\Order;
use App\Services\InvoicePdfService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrderStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $order  = $this->order;
        $status = $order->status;

        $title = match ($status->value) {
            'confirmed' => 'Your order was confirmed',
            'shipped'   => 'Your order has been shipped',
            'delivered' => 'Your order has been delivered',
            'cancelled' => 'Your order was cancelled',
            default     => "Order {$order->order_number} updated",
        };

        $message = (new MailMessage)
            ->subject("{$title} â€” {$order->order_number}")
            ->greeting("Hello {$notifiable->name}!")
            ->line($title . '.')
            ->line("**Order:** {$order->order_number}")
            ->line("**Vendor:** {$order->vendor->name}")
            ->line("**New status:** {$status->label()}")
            ->line("**Total:** " . number_format((float) $order->total, 2) . ' LYD');

        // Attach invoice PDF when the order is confirmed
        if ($status === OrderStatus::Confirmed) {
            $invoice = Invoice::where('order_id', $order->id)->first();

            if ($invoice) {
                try {
                    $pdf = app(InvoicePdfService::class)->generate($invoice);
                    $message->attachData(
                        $pdf,
                        "{$invoice->invoice_number}.pdf",
                        ['mime' => 'application/pdf']
                    );
                    $message->line('Your invoice is attached to this email.');
                } catch (Throwable $e) {
                    Log::warning('Invoice PDF attachment failed: ' . $e->getMessage());
                }
            }
        }

        return $message
            ->action('View order', route('shop.orders.show', $order))
            ->line('Thank you for shopping on WESALKOM.');
    }

    public function toArray(object $notifiable): array
    {
        $order = $this->order;

        return [
            'type'         => 'order_status_changed',
            'order_id'     => $order->id,
            'order_number' => $order->order_number,
            'status'       => $order->status->value,
            'title'        => "Order {$order->order_number} is now {$order->status->label()}",
            'message'      => "From {$order->vendor->name}",
            'url'          => route('shop.orders.show', $order),
        ];
    }
}