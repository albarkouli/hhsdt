<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\ProductRatingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->query('filter');

        $reviews = Review::query()
            ->with(['product:id,name,slug', 'user:id,name,email'])
            ->when($filter === 'published', fn ($q) => $q->where('is_published', true))
            ->when($filter === 'hidden',    fn ($q) => $q->where('is_published', false))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $counts = [
            'all'       => Review::count(),
            'published' => Review::where('is_published', true)->count(),
            'hidden'    => Review::where('is_published', false)->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'filter', 'counts'));
    }

    public function toggle(Review $review, ProductRatingService $service): RedirectResponse
    {
        $review->update(['is_published' => ! $review->is_published]);

        if ($review->product) {
            $service->recalculate($review->product);
        }

        return back()->with('status', $review->is_published ? 'Review published.' : 'Review hidden.');
    }

    public function destroy(Review $review, ProductRatingService $service): RedirectResponse
    {
        $product = $review->product;
        $review->delete();

        if ($product) {
            $service->recalculate($product);
        }

        return back()->with('status', 'Review deleted.');
    }
}