<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'vendor_id',
        'invoice_number',
        'sequence_number',
        'status',
        'version',
        'snapshot',
        'issued_at',
    ];

    protected function casts(): array
    {
        return [
            'snapshot'  => 'array',
            'issued_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}