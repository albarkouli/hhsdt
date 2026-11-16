<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\StoreReviewRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Services\ProductRatingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Product $product): View
    {
        abort_unless($product->isVisible(), 404);

        $reviews = $product->reviews()
            ->published()
            ->with('user:id,name')
            ->latest()
            ->paginate(15);

        $myReview = auth()->check()
            ? Review::where('product_id', $product->id)->where('user_id', auth()->id())->first()
            : null;

        return view('shop.reviews.index', compact('product', 'reviews', 'myReview'));
    }

    public function store(StoreReviewRequest $request, Product $product, ProductRatingService $service): RedirectResponse
    {
        abort_unless($product->isVisible(), 404);

        $exists = Review::where('product_id', $product->id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($exists) {
            return back()->with('cart_error', 'You have already reviewed this product.');
        }

        $hasPurchased = Order::where('user_id', auth()->id())
            ->whereIn('status', ['confirmed', 'shipped', 'delivered'])
            ->whereHas('items', fn ($q) => $q->where('product_id', $product->id))
            ->exists();

        Review::create([
            'product_id'           => $product->id,
            'user_id'              => auth()->id(),
            'rating'               => $request->integer('rating'),
            'title'                => $request->input('title'),
            'body'                 => $request->string('body')->toString(),
            'is_verified_purchase' => $hasPurchased,
            'is_published'         => true,
        ]);

        $service->recalculate($product);

        return back()->with('cart_status', 'Thank you! Your review has been posted.');
    }

    public function destroy(Product $product, Review $review, ProductRatingService $service): RedirectResponse
    {
        abort_unless($review->user_id === auth()->id(), 403);
        abort_unless($review->product_id === $product->id, 404);

        $review->delete();
        $service->recalculate($product);

        return back()->with('cart_status', 'Your review has been removed.');
    }
}