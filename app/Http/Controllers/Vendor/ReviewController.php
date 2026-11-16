<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        $vendor = auth()->user()->vendor;
        abort_if(! $vendor, 403);

        $reviews = Review::whereHas('product', fn ($q) => $q->where('vendor_id', $vendor->id))
            ->with(['product:id,name,slug', 'user:id,name'])
            ->latest()
            ->paginate(15);

        $counts = [
            'total'         => $reviews->total(),
            'unreplied'     => Review::whereHas('product', fn ($q) => $q->where('vendor_id', $vendor->id))
                                ->whereNull('vendor_reply')->where('is_published', true)->count(),
        ];

        return view('vendor.reviews.index', compact('vendor', 'reviews', 'counts'));
    }

    public function reply(Request $request, Review $review): RedirectResponse
    {
        $vendor = auth()->user()->vendor;
        abort_if(! $vendor, 403);
        abort_unless($review->product?->vendor_id === $vendor->id, 403);

        $validated = $request->validate([
            'vendor_reply' => ['required', 'string', 'min:2', 'max:1000'],
        ]);

        $review->update([
            'vendor_reply'      => $validated['vendor_reply'],
            'vendor_replied_at' => now(),
        ]);

        return back()->with('status', 'Reply posted.');
    }

    public function deleteReply(Review $review): RedirectResponse
    {
        $vendor = auth()->user()->vendor;
        abort_if(! $vendor, 403);
        abort_unless($review->product?->vendor_id === $vendor->id, 403);

        $review->update([
            'vendor_reply'      => null,
            'vendor_replied_at' => null,
        ]);

        return back()->with('status', 'Reply removed.');
    }
}