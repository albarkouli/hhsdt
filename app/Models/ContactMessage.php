<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
        'ip_address',
        'read_at',
        'replied_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at'    => 'datetime',
            'replied_at' => 'datetime',
        ];
    }

    public function markAsRead(): void
    {
        if ($this->read_at === null) {
            $this->update([
                'read_at' => now(),
                'status'  => $this->status === 'new' ? 'read' : $this->status,
            ]);
        }
    }

    public function markAsReplied(): void
    {
        $this->update([
            'replied_at' => now(),
            'status'     => 'replied',
        ]);
    }
}