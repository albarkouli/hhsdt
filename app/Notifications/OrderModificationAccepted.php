<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderModificationAccepted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Customer accepted the change — {$this->order->order_number}")
            ->greeting("Good news, {$notifiable->name}!")
            ->line("**{$this->order->user->name}** accepted your proposed changes for order **{$this->order->order_number}**.")
            ->line("**New total:** " . number_format((float) $this->order->total, 2) . ' LYD')
            ->action('Confirm the order', route('vendor.orders.show', $this->order))
            ->line('You can now confirm and process the order.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'         => 'order_modification_accepted',
            'order_id'     => $this->order->id,
            'order_number' => $this->order->order_number,
            'title'        => "Customer accepted the change",
            'message'      => "Order {$this->order->order_number} is now pending again.",
            'url'          => route('vendor.orders.show', $this->order),
        ];
    }
}