<?php

namespace App\Notifications;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorApproved extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Vendor $vendor) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your store is now active!')
            ->greeting("Congratulations, {$notifiable->name}!")
            ->line("Your store **{$this->vendor->name}** has been approved and is now live on WESALKOM.")
            ->line('You can start adding products right away.')
            ->action('Go to my store dashboard', route('vendor.dashboard'))
            ->line('Welcome to WESALKOM!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'   => 'vendor_approved',
            'title'  => 'Your store has been approved',
            'message' => "{$this->vendor->name} is now live.",
            'url'    => route('vendor.dashboard'),
        ];
    }
}