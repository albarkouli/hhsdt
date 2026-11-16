<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'vendor_id',
        'status',
        'payment_method',
        'coupon_id',
        'coupon_code',
        'discount',
        'commission_rate',
        'commission_amount',
        'vendor_net',
        'subtotal',
        'shipping_cost',
        'total',
        'shipping_name',
        'shipping_phone',
        'shipping_city',
        'shipping_address',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status'         => OrderStatus::class,
            'discount'       => 'decimal:2',
            'commission_rate'   => 'decimal:2',
            'commission_amount' => 'decimal:2',
            'vendor_net'        => 'decimal:2',
            'subtotal'       => 'decimal:2',
            'shipping_cost'  => 'decimal:2',
            'total'          => 'decimal:2',
            'confirmed_at'   => 'datetime',
            'shipped_at'     => 'datetime',
            'delivered_at'   => 'datetime',
            'cancelled_at'   => 'datetime',
        ];
    }

    public static function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (static::where('order_number', $number)->exists());

        return $number;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function modifications(): HasMany
    {
        return $this->hasMany(OrderModification::class);
    }

    public function latestModification(): HasOne
    {
        return $this->hasOne(OrderModification::class)->latestOfMany();
    }

    public function hasPendingModification(): bool
    {
        return $this->modifications()->where('status', 'pending')->exists();
    }

    public function sendNotifications(): void
    {
        // Send to customer ط£آ¢أ¢â€ڑآ¬أ¢â‚¬â€Œ don't fail the order if email fails
        try {
            $this->user?->notify(new \App\Notifications\OrderPlacedToCustomer($this));
        } catch (\Throwable $e) {
            \Log::warning("Customer email failed for order {$this->order_number}: " . $e->getMessage());
        }

        // Send to vendor owner
        try {
            $this->vendor?->owner?->notify(new \App\Notifications\OrderPlacedToVendor($this));
        } catch (\Throwable $e) {
            \Log::warning("Vendor email failed for order {$this->order_number}: " . $e->getMessage());
        }
    }

    public function isCancellable(): bool
    {
        return in_array($this->status, [OrderStatus::Pending, OrderStatus::Confirmed], true);
    }
}