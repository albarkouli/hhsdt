<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $featuredProducts = Product::query()
            ->visible()
            ->with(['vendor:id,name,slug', 'category:id,name,slug'])
            ->latest()
            ->limit(8)
            ->get();

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->withCount(['products' => fn ($q) => $q->visible()])
            ->limit(8)
            ->get(['id', 'name', 'slug']);

        $vendors = Vendor::where('status', 'active')
            ->withCount(['products' => fn ($q) => $q->visible()])
            ->orderByDesc('products_count')
            ->limit(4)
            ->get(['id', 'name', 'slug', 'city', 'logo', 'description']);

        $stats = [
            'products'   => Product::visible()->count(),
            'vendors'    => Vendor::where('status', 'active')->count(),
            'categories' => Category::where('is_active', true)->count(),
        ];

        $recentIds = session()->get('recently_viewed', []);
        $recentlyViewed = collect();
        if (! empty($recentIds)) {
            $recentlyViewed = Product::query()
                ->visible()
                ->whereIn('id', $recentIds)
                ->with(['vendor:id,name', 'category:id,name'])
                ->limit(8)
                ->get()
                ->sortBy(fn ($p) => array_search($p->id, $recentIds))
                ->values();
        }

        return view('shop.home', compact('featuredProducts', 'categories', 'vendors', 'stats', 'recentlyViewed'));
    }
}