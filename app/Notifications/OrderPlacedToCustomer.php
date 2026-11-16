<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlacedToCustomer extends Notification
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
        $order = $this->order->loadMissing(['vendor:id,name,slug,phone', 'items']);

        return (new MailMessage)
            ->subject("Order confirmed: {$order->order_number}")
            ->view('emails.order-placed-customer', [
                'order'      => $order,
                'customer'   => $notifiable,
                'orderUrl'   => route('shop.orders.show', $order),
                'vendorUrl'  => route('shop.stores.show', ['vendor' => $order->vendor->slug]),
            ]);
    }
}