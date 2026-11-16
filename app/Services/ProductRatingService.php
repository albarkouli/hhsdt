<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Review;

class ProductRatingService
{
    /**
     * Recalculate avg_rating and reviews_count for a product.
     * Uses query builder update to bypass $fillable (these are computed columns).
     */
    public function recalculate(Product $product): void
    {
        $agg = Review::where('product_id', $product->id)
            ->where('is_published', true)
            ->selectRaw('COUNT(*) as total, COALESCE(AVG(rating), 0) as avg_rating')
            ->first();

        Product::where('id', $product->id)->update([
            'avg_rating'    => round((float) $agg->avg_rating, 2),
            'reviews_count' => (int) $agg->total,
        ]);
    }
}