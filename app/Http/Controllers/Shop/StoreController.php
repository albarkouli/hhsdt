<?php

namespace App\Http\Controllers\Shop;

use App\Enums\VendorStatus;
use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\View\View;

class StoreController extends Controller
{
    public function show(Vendor $vendor): View
    {
        abort_unless($vendor->status === VendorStatus::Active, 404);

        $products = $vendor->products()
            ->visible()
            ->with('category:id,name,slug')
            ->latest()
            ->paginate(12);

        return view('shop.stores.show', [
            'vendor'   => $vendor,
            'products' => $products,
        ]);
    }
}