<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlacedToVendor extends Notification
{
    use Queueable;

    public function __construct(public Order $order)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $order = $this->order->loadMissing(['user:id,name,email', 'items']);

        return (new MailMessage)
            ->subject("New order received: {$order->order_number}")
            ->view('emails.order-placed-vendor', [
                'order'      => $order,
                'vendor'     => $notifiable,
                'orderUrl'   => route('vendor.orders.show', $order),
            ]);
    }
}