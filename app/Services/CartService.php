<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    /**
     * Raw cart: [productId => quantity]
     *
     * @return array<int,int>
     */
    public function items(): array
    {
        return session()->get('cart', []);
    }

    public function count(): int
    {
        return array_sum($this->items());
    }

    public function add(int $productId, int $qty = 1): void
    {
        $cart = $this->items();
        $cart[$productId] = ($cart[$productId] ?? 0) + $qty;
        session()->put('cart', $cart);
    }

    public function update(int $productId, int $qty): void
    {
        $cart = $this->items();

        if ($qty <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = $qty;
        }

        session()->put('cart', $cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->items();
        unset($cart[$productId]);
        session()->put('cart', $cart);
    }

    public function clear(): void
    {
        session()->forget('cart');
    }

    /**
     * Load the cart with live product data.
     *
     * Each item is an object:
     *   { product, quantity, subtotal, is_visible }
     *
     * @return Collection<int,object>
     */
    public function detailed(): Collection
    {
        $cart = $this->items();

        if (empty($cart)) {
            return collect();
        }

        $products = Product::query()
            ->whereIn('id', array_keys($cart))
            ->with([
                'vendor:id,name,slug,status',
                'category:id,name,slug',
            ])
            ->get()
            ->keyBy('id');

        return collect($cart)
            ->map(function (int $qty, int $id) use ($products) {
                $product = $products->get($id);

                if (! $product) {
                    return null;
                }

                return (object) [
                    'product'    => $product,
                    'quantity'   => $qty,
                    'subtotal'   => (float) $product->price * $qty,
                    'is_visible' => $product->isVisible(),
                ];
            })
            ->filter()
            ->values();
    }

    public function subtotal(): float
    {
        return $this->detailed()
            ->filter(fn ($item) => $item->is_visible)
            ->sum(fn ($item) => $item->subtotal);
    }

    // ===== COUPON =====

    public function getCoupon(): ?\App\Models\Coupon
    {
        $id = session()->get('cart_coupon_id');
        if (! $id) {
            return null;
        }

        $coupon = \App\Models\Coupon::find($id);
        if (! $coupon || ! $coupon->isUsable()) {
            session()->forget('cart_coupon_id');
            return null;
        }

        return $coupon;
    }

    public function applyCoupon(\App\Models\Coupon $coupon): void
    {
        session()->put('cart_coupon_id', $coupon->id);
    }

    public function removeCoupon(): void
    {
        session()->forget('cart_coupon_id');
    }

    public function discount(): float
    {
        $coupon = $this->getCoupon();
        if (! $coupon) {
            return 0.0;
        }

        return $coupon->discountFor($this->subtotal());
    }

    public function total(): float
    {
        return max(0.0, $this->subtotal() - $this->discount());
    }
}