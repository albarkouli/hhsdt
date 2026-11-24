<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlaced extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $order = $this->order;

        return (new MailMessage)
            ->subject("New order {$order->order_number}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("You have a new order on WESALKOM.")
            ->line("**Order:** {$order->order_number}")
            ->line("**Customer:** {$order->user->name}")
            ->line("**Total:** " . number_format((float) $order->total, 2) . " LYD")
            ->line("**Items:** {$order->items->count()}")
            ->action('View order', route('vendor.orders.show', $order))
            ->line('Please confirm the order as soon as possible.');
    }

    public function toArray(object $notifiable): array
    {
        $order = $this->order;

        return [
            'type'       => 'order_placed',
            'order_id'   => $order->id,
            'order_number' => $order->order_number,
            'title'      => "New order {$order->order_number}",
            'message'    => "From {$order->user->name} â€” " . number_format((float) $order->total, 2) . ' LYD',
            'url'        => route('vendor.orders.show', $order),
        ];
    }
}