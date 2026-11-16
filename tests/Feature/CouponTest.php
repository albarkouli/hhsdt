<?php

use App\Models\Category;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use App\Services\CouponService;

uses()->group('coupons');

beforeEach(function () {
    $this->customer = User::factory()->create();
    $this->customer->assignRole('customer');

    $owner = User::factory()->create();
    $this->vendor = Vendor::factory()->for($owner, 'owner')->active()->create();
    $this->category = Category::factory()->create();
    $this->product = Product::factory()->for($this->vendor, 'vendor')
        ->for($this->category, 'category')
        ->create(['price' => 100, 'stock' => 10, 'is_active' => true]);
});

it('applies a percent coupon to cart subtotal', function () {
    Coupon::create([
        'code' => 'SAVE10', 'name' => 'Save 10%', 'type' => 'percent', 'value' => 10,
        'min_order' => 0, 'max_uses_per_user' => 1, 'is_active' => true,
    ]);

    $this->actingAs($this->customer)
        ->post(route('shop.cart.add', $this->product), ['quantity' => 2]);

    $this->actingAs($this->customer)
        ->post(route('shop.cart.coupon.apply'), ['code' => 'SAVE10'])
        ->assertSessionHas('cart_status');

    expect(session('cart_coupon_id'))->not->toBeNull();
    expect(app(\App\Services\CartService::class)->discount())->toBe(20.0);
    expect(app(\App\Services\CartService::class)->total())->toBe(180.0);
});

it('rejects a coupon below minimum order value', function () {
    Coupon::create([
        'code' => 'MIN500', 'name' => 'Min 500', 'type' => 'fixed', 'value' => 50,
        'min_order' => 500, 'max_uses_per_user' => 1, 'is_active' => true,
    ]);

    $this->actingAs($this->customer)
        ->post(route('shop.cart.add', $this->product), ['quantity' => 1]); // 100 LYD

    $this->actingAs($this->customer)
        ->post(route('shop.cart.coupon.apply'), ['code' => 'MIN500'])
        ->assertSessionHas('cart_error');

    expect(session('cart_coupon_id'))->toBeNull();
});

it('rejects an expired coupon', function () {
    Coupon::create([
        'code' => 'OLD10', 'name' => 'Old', 'type' => 'percent', 'value' => 10,
        'min_order' => 0, 'max_uses_per_user' => 1, 'is_active' => true,
        'expires_at' => now()->subDay(),
    ]);

    $this->actingAs($this->customer)
        ->post(route('shop.cart.add', $this->product), ['quantity' => 1]);

    $this->actingAs($this->customer)
        ->post(route('shop.cart.coupon.apply'), ['code' => 'OLD10'])
        ->assertSessionHas('cart_error');
});

it('prevents reusing the same coupon by the same user', function () {
    $coupon = Coupon::create([
        'code' => 'ONCE', 'name' => 'Once', 'type' => 'percent', 'value' => 10,
        'min_order' => 0, 'max_uses_per_user' => 1, 'is_active' => true,
    ]);

    CouponUsage::create([
        'coupon_id' => $coupon->id,
        'user_id'   => $this->customer->id,
        'order_id'  => null,
        'discount_amount' => 10,
    ]);

    [$c, $error, $discount] = app(CouponService::class)->validate('ONCE', 200, $this->customer->id);

    expect($c)->toBeNull();
    expect($error)->toContain('already used');
});

it('caps discount at max_discount for percent coupons', function () {
    $coupon = Coupon::create([
        'code' => 'CAP50', 'name' => 'Cap 50', 'type' => 'percent', 'value' => 50,
        'min_order' => 0, 'max_discount' => 50, 'max_uses_per_user' => 1, 'is_active' => true,
    ]);

    // 50% of 500 = 250, but capped at 50
    expect($coupon->discountFor(500))->toBe(50.0);
    expect($coupon->discountFor(80))->toBe(40.0); // 50% of 80 = 40 (below cap)
});