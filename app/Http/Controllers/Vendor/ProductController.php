<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $vendor   = auth()->user()->vendor;
        $products = $vendor->products()->with('category:id,name')->latest()->paginate(15);

        return view('vendor.products.index', compact('vendor', 'products'));
    }

    public function create(): View
    {
        $vendor = $this->requireActiveVendor();

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')->orderBy('name')->get(['id', 'name']);

        return view('vendor.products.form', [
            'product'    => new Product(),
            'vendor'     => $vendor,
            'categories' => $categories,
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $vendor = $this->requireActiveVendor();

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $vendor->products()->create($data);

        return redirect()
            ->route('vendor.products.index')
            ->with('status', 'Product created.');
    }

    public function edit(Product $product): View
    {
        $this->authorizeOwnership($product);
        $vendor = $this->requireActiveVendor();

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')->orderBy('name')->get(['id', 'name']);

        return view('vendor.products.form', [
            'product'    => $product,
            'vendor'     => $vendor,
            'categories' => $categories,
        ]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $this->authorizeOwnership($product);
        $this->requireActiveVendor();

        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()
            ->route('vendor.products.index')
            ->with('status', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorizeOwnership($product);
        $this->requireActiveVendor();

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return back()->with('status', 'Product deleted.');
    }

    private function requireActiveVendor(): \App\Models\Vendor
    {
        $vendor = auth()->user()->vendor;

        abort_if(! $vendor, 403, 'No vendor profile.');
        abort_unless($vendor->status->canSell(), 403, 'Your store is not active.');

        return $vendor;
    }

    private function authorizeOwnership(Product $product): void
    {
        abort_if(
            $product->vendor_id !== auth()->user()->vendor?->id,
            403,
            'You do not own this product.'
        );
    }
}