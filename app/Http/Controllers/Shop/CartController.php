<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use App\Services\CouponService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(CartService $cart): View
    {
        return view('shop.cart.index', [
            'items'    => $cart->detailed(),
            'subtotal' => $cart->subtotal(),
        ]);
    }

    public function add(Request $request, Product $product, CartService $cart): RedirectResponse
    {
        abort_unless($product->isVisible(), 404);

        $qty = (int) $request->input('quantity', 1);

        if ($qty > 100) {
            return back()->with('cart_error', 'Maximum 100 units per item per order.');
        }

        if ($qty < 1) {
            return back()->with('cart_error', 'Quantity must be at least 1.');
        }

        $current  = $cart->items()[$product->id] ?? 0;
        $newTotal = $current + $qty;

        if ($newTotal > $product->stock) {
            return back()->with(
                'cart_error',
                "Only {$product->stock} item(s) available for \"{$product->name}\"."
            );
        }

        $cart->add($product->id, $qty);

        return back()->with('cart_status', "\"{$product->name}\" added to cart.");
    }

    public function update(Request $request, Product $product, CartService $cart): RedirectResponse
    {
        $qty = (int) $request->input('quantity', 0);

        if ($qty > $product->stock) {
            return back()->with('cart_error', "Only {$product->stock} item(s) available.");
        }

        $cart->update($product->id, $qty);

        return back()->with('cart_status', 'Cart updated.');
    }

    public function remove(Product $product, CartService $cart): RedirectResponse
    {
        $cart->remove($product->id);

        return back()->with('cart_status', "\"{$product->name}\" removed.");
    }

    public function clear(CartService $cart): RedirectResponse
    {
        $cart->clear();

        return redirect()->route('shop.cart.index')->with('cart_status', 'Cart cleared.');
    }

    public function applyCoupon(\Illuminate\Http\Request $request, CartService $cart, CouponService $coupons): \Illuminate\Http\RedirectResponse
    {
        
        if (! auth()->check()) {
            return redirect()->route('login')->with('cart_error', 'Please login to use coupons.');
        }
$code = $request->string('code')->trim()->toString();

        if ($code === '') {
            return back()->with('cart_error', 'Please enter a coupon code.');
        }

        $items = $cart->detailed();
        if ($items->isEmpty()) {
            return back()->with('cart_error', 'Your cart is empty.');
        }

        $invisible = $items->reject(fn ($i) => $i->is_visible);
        if ($invisible->isNotEmpty()) {
            return back()->with('cart_error', 'Some items are unavailable. Please review your cart.');
        }

        [$coupon, $error, $discount] = $coupons->validate($code, $cart->subtotal(), auth()->id());

        if ($error) {
            return back()->with('cart_error', $error);
        }

        $cart->applyCoupon($coupon);

        return back()->with('cart_status', "Coupon \"{$coupon->code}\" applied â€” you save " . number_format($discount, 2) . ' LYD.');
    }

    public function removeCoupon(CartService $cart): \Illuminate\Http\RedirectResponse
    {
        $cart->removeCoupon();

        return back()->with('cart_status', 'Coupon removed.');
    }
}