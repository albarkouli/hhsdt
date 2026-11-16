<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(): View
    {
        $products = auth()->user()->wishlist()
            ->visible()
            ->with(['vendor:id,name', 'category:id,name'])
            ->latest('wishlists.created_at')
            ->paginate(12);

        return view('shop.wishlist.index', compact('products'));
    }

    public function toggle(Request $request, Product $product): JsonResponse|RedirectResponse
    {
        abort_unless($product->isVisible(), 404);

        $user = auth()->user();
        $exists = $user->wishlist()->where('product_id', $product->id)->exists();

        if ($exists) {
            $user->wishlist()->detach($product->id);
            $added = false;
            $message = 'Removed from wishlist.';
        } else {
            $user->wishlist()->attach($product->id);
            $added = true;
            $message = 'Added to wishlist.';
        }

        $count = $user->wishlist()->count();

        if ($request->wantsJson()) {
            return response()->json([
                'added' => $added,
                'count' => $count,
                'message' => $message,
            ]);
        }

        return back()->with('cart_status', $message);
    }
}