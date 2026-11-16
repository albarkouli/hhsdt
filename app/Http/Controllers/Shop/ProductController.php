<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $q                 = $request->string('q')->trim()->toString();
        $categorySlug      = $request->string('category')->trim()->toString();
        $minPrice          = $request->input('min_price');
        $maxPrice          = $request->input('max_price');
        $sort              = $request->query('sort', 'newest');
        $includeOutOfStock = $request->boolean('include_out_of_stock');

        $query = Product::query()
            ->when($includeOutOfStock,
                fn ($q) => $q->listable(),
                fn ($q) => $q->visible()
            )
            ->with(['category:id,name,slug', 'vendor:id,name,slug'])
            ->when($q !== '', fn ($query) => $query->where('name', 'like', '%' . $q . '%'))
            ->when($categorySlug !== '', fn ($query) => $query->whereHas(
                'category',
                fn ($c) => $c->where('slug', $categorySlug)
            ))
            ->when($minPrice !== null && $minPrice !== '', fn ($query) => $query->where('price', '>=', (float) $minPrice))
            ->when($maxPrice !== null && $maxPrice !== '', fn ($query) => $query->where('price', '<=', (float) $maxPrice));

        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'rating') {
            $query->orderByDesc('avg_rating')->orderByDesc('reviews_count');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->withCount(['products' => fn ($qq) => $qq->visible()])
            ->get(['id', 'name', 'slug']);

        $hasFilters = $q !== '' || $categorySlug !== '' || $minPrice || $maxPrice || $includeOutOfStock;

        return view('shop.products.index', [
            'products'           => $products,
            'categories'         => $categories,
            'q'                  => $q,
            'categorySlug'       => $categorySlug,
            'minPrice'           => $minPrice,
            'maxPrice'           => $maxPrice,
            'sort'               => $sort,
            'includeOutOfStock'  => $includeOutOfStock,
            'totalCount'         => $products->total(),
            'hasFilters'         => $hasFilters,
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->isVisible(), 404);

        // Track recently viewed (last 8, no dupes, newest first)
        $recent = session()->get('recently_viewed', []);
        $recent = array_values(array_diff($recent, [$product->id]));
        array_unshift($recent, $product->id);
        session()->put('recently_viewed', array_slice($recent, 0, 8));

        $product->load([
            'category:id,name,slug',
            'vendor:id,name,slug,city,phone,logo,description',
        ]);

        $related = Product::query()
            ->visible()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['vendor:id,name'])
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $moreFromVendor = Product::query()
            ->visible()
            ->where('vendor_id', $product->vendor_id)
            ->where('id', '!=', $product->id)
            ->whereNotIn('id', $related->pluck('id'))
            ->with(['vendor:id,name'])
            ->latest()
            ->limit(4)
            ->get();

        $recentIds = array_values(array_diff(session()->get('recently_viewed', []), [$product->id]));
        $recentlyViewed = collect();
        if (! empty($recentIds)) {
            $recentlyViewed = Product::query()
                ->visible()
                ->whereIn('id', array_slice($recentIds, 0, 4))
                ->with('vendor:id,name')
                ->get();
        }

        return view('shop.products.show', compact('product', 'related', 'moreFromVendor', 'recentlyViewed'));
    }
}