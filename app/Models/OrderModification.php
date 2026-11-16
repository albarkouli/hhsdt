<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderModification extends Model
{
    protected $fillable = [
        'order_id',
        'proposed_by',
        'original_items',
        'proposed_items',
        'original_total',
        'proposed_total',
        'note',
        'status',
        'accepted_at',
        'rejected_at',
    ];

    protected function casts(): array
    {
        return [
            'original_items'  => 'array',
            'proposed_items'  => 'array',
            'original_total'  => 'decimal:2',
            'proposed_total'  => 'decimal:2',
            'accepted_at'     => 'datetime',
            'rejected_at'     => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function proposedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'proposed_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function markAccepted(): void
    {
        $this->update([
            'status'      => 'accepted',
            'accepted_at' => now(),
        ]);
    }

    public function markRejected(): void
    {
        $this->update([
            'status'      => 'rejected',
            'rejected_at' => now(),
        ]);
    }
}