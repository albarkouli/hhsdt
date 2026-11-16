<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\OrderModification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderModificationProposed extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order, public OrderModification $modification) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Action needed: {$this->order->vendor->name} proposed a change to your order")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your order **{$this->order->order_number}** from **{$this->order->vendor->name}** needs your review.")
            ->line("**Reason:** {$this->modification->note}")
            ->line("**Original total:** " . number_format((float) $this->modification->original_total, 2) . ' LYD')
            ->line("**Proposed total:** " . number_format((float) $this->modification->proposed_total, 2) . ' LYD')
            ->action('Review the proposal', route('shop.orders.review', $this->order))
            ->line('Please accept or reject the change to continue.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'         => 'order_modification_proposed',
            'order_id'     => $this->order->id,
            'order_number' => $this->order->order_number,
            'title'        => "Review proposed changes",
            'message'      => "{$this->order->vendor->name} proposed a change to order {$this->order->order_number}.",
            'url'          => route('shop.orders.review', $this->order),
        ];
    }
}