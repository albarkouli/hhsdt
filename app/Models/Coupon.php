<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'name', 'description', 'type', 'value',
        'min_order', 'max_discount', 'max_uses', 'used_count',
        'max_uses_per_user', 'starts_at', 'expires_at', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'value'              => 'decimal:2',
            'min_order'          => 'decimal:2',
            'max_discount'       => 'decimal:2',
            'max_uses'           => 'integer',
            'used_count'         => 'integer',
            'max_uses_per_user'  => 'integer',
            'starts_at'          => 'datetime',
            'expires_at'         => 'datetime',
            'is_active'          => 'boolean',
        ];
    }

    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function isPercent(): bool
    {
        return $this->type === 'percent';
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function hasStarted(): bool
    {
        return $this->starts_at === null || $this->starts_at->isPast();
    }

    public function isExhausted(): bool
    {
        return $this->max_uses !== null && $this->used_count >= $this->max_uses;
    }

    public function isUsable(): bool
    {
        return $this->is_active && ! $this->isExpired() && $this->hasStarted() && ! $this->isExhausted();
    }

    /**
     * Compute the actual discount for a given subtotal.
     */
    public function discountFor(float $subtotal): float
    {
        if ($subtotal < (float) $this->min_order) {
            return 0.0;
        }

        $discount = $this->isPercent()
            ? round($subtotal * ((float) $this->value / 100), 2)
            : (float) $this->value;

        if ($this->max_discount !== null && $discount > (float) $this->max_discount) {
            $discount = (float) $this->max_discount;
        }

        return min($discount, $subtotal);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            });
    }
}