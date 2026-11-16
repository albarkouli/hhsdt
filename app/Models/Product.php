<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'category_id',
        'name',
        'slug',
        'description',
        'image',
        'price',
        'stock',
        'sku',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price'     => 'decimal:2',
            'stock'     => 'integer',
            'is_active'     => 'boolean',
            'avg_rating'    => 'decimal:2',
            'reviews_count' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = static::generateUniqueSlug(
                    $product->name,
                    (int) $product->vendor_id
                );
            }
        });
    }

    public static function generateUniqueSlug(string $name, int $vendorId): string
    {
        $base = Str::slug($name) ?: 'product';
        $slug = $base;
        $i    = 1;

        while (static::where('vendor_id', $vendorId)->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeVisible(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->whereHas('vendor', fn ($q) => $q->where('status', 'active'));
    }

    public function isVisible(): bool
    {
        return $this->is_active
            && $this->vendor
            && $this->vendor->isActive()
            && $this->stock > 0;
    }

    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Like visible(), but includes out-of-stock products
     * (still requires active product from active vendor).
     */
    public function scopeListable(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query
            ->where('is_active', true)
            ->whereHas('vendor', fn ($q) => $q->where('status', 'active'));
    }
}