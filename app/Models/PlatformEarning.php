<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformEarning extends Model
{
    protected $fillable = [
        'order_id', 'vendor_id', 'order_total', 'commission_rate',
        'amount', 'status', 'settled_at',
    ];

    protected function casts(): array
    {
        return [
            'order_total'     => 'decimal:2',
            'commission_rate' => 'decimal:2',
            'amount'          => 'decimal:2',
            'settled_at'      => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}