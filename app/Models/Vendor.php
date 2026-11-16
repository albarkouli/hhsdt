<?php

namespace App\Models;

use App\Enums\VendorStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Vendor extends Model
{
    /** @use HasFactory<\Database\Factories\VendorFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'logo',
        'phone',
        'email',
        'address',
        'city',
        'status',
        'commission_rate',
    ];

    protected function casts(): array
    {
        return [
            'status' => VendorStatus::class,
            'commission_rate' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Vendor $vendor) {
            if (empty($vendor->slug)) {
                $vendor->slug = static::generateUniqueSlug($vendor->name);
            }
        });

        static::created(function (Vendor $vendor) {
            $owner = $vendor->owner;

            if (! $owner) {
                return;
            }

            // Promote the owner to the vendor role, removing only the
            // default customer role (keeps super_admin etc. intact).
            if ($owner->hasRole('customer')) {
                $owner->removeRole('customer');
            }
            if (! $owner->hasRole('vendor')) {
                $owner->assignRole('vendor');
            }
        });
    }

    public static function generateUniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'vendor';
        $slug = $base;
        $i    = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function isActive(): bool
    {
        return $this->status === VendorStatus::Active;
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class);
    }
    public function platformEarnings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PlatformEarning::class);
    }
}