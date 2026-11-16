<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderModificationRejected extends Notification implements ShouldQueue
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
            ->subject("Customer rejected the change — {$this->order->order_number}")
            ->greeting("Hello {$notifiable->name},")
            ->line("**{$this->order->user->name}** rejected your proposed changes for order **{$this->order->order_number}**.")
            ->line('The order has been cancelled and stock has been restored.')
            ->action('View order', route('vendor.orders.show', $this->order))
            ->line('The customer may place a new order later.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'         => 'order_modification_rejected',
            'order_id'     => $this->order->id,
            'order_number' => $this->order->order_number,
            'title'        => "Customer rejected the change",
            'message'      => "Order {$this->order->order_number} has been cancelled.",
            'url'          => route('vendor.orders.show', $this->order),
        ];
    }
}