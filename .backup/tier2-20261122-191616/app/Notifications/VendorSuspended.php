<?php

namespace App\Notifications;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorSuspended extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Vendor $vendor, public string $reason = 'suspended') {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $action = $this->reason === 'rejected' ? 'rejected' : 'suspended';

        return (new MailMessage)
            ->subject("Your store has been {$action}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your store **{$this->vendor->name}** has been {$action}.")
            ->line('Your existing orders are still being processed, but new products will not be visible to customers.')
            ->line('If you believe this is a mistake, please contact us at hello@libyamarketplace.ly')
            ->line('Thank you for your understanding.');
    }

    public function toArray(object $notifiable): array
    {
        $action = $this->reason === 'rejected' ? 'rejected' : 'suspended';

        return [
            'type'    => 'vendor_suspended',
            'title'   => "Your store has been {$action}",
            'message' => "{$this->vendor->name} is no longer active.",
            'url'     => route('vendor.dashboard'),
        ];
    }
}