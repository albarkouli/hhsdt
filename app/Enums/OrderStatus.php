<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending                   = 'pending';
    case PendingCustomerApproval   = 'pending_customer_approval';
    case Confirmed                 = 'confirmed';
    case Shipped                   = 'shipped';
    case Delivered                 = 'delivered';
    case Cancelled                 = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending                 => 'Pending',
            self::PendingCustomerApproval => 'Awaiting customer approval',
            self::Confirmed               => 'Confirmed',
            self::Shipped                 => 'Shipped',
            self::Delivered               => 'Delivered',
            self::Cancelled               => 'Cancelled',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Pending                 => 'bg-yellow-100 text-yellow-800',
            self::PendingCustomerApproval => 'bg-purple-100 text-purple-800',
            self::Confirmed               => 'bg-blue-100 text-blue-800',
            self::Shipped                 => 'bg-indigo-100 text-indigo-800',
            self::Delivered               => 'bg-green-100 text-green-700',
            self::Cancelled               => 'bg-red-100 text-red-700',
        };
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::Delivered, self::Cancelled], true);
    }
}