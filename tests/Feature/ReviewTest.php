<?php

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\Vendor;

uses()->group('reviews');

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

it('allows a customer to post a review', function () {
    $this->actingAs($this->customer)
        ->post(route('shop.reviews.store', $this->product), [
            'rating' => 5,
            'title'  => 'Great',
            'body'   => 'Excellent quality, fast delivery.',
        ])
        ->assertRedirect();

    expect(Review::count())->toBe(1);
    expect(Review::first()->rating)->toBe(5);
    expect((float) $this->product->fresh()->avg_rating)->toBe(5.0);
    expect($this->product->fresh()->reviews_count)->toBe(1);
});

it('prevents duplicate reviews from the same customer', function () {
    Review::create([
        'product_id' => $this->product->id,
        'user_id'    => $this->customer->id,
        'rating'     => 4,
        'body'       => 'Good product overall.',
    ]);

    $this->actingAs($this->customer)
        ->post(route('shop.reviews.store', $this->product), [
            'rating' => 5,
            'body'   => 'Trying again with same user.',
        ])
        ->assertSessionHas('cart_error');

    expect(Review::count())->toBe(1);
});

it('marks review as verified when customer purchased', function () {
    $order = Order::create([
        'order_number'     => Order::generateOrderNumber(),
        'user_id'          => $this->customer->id,
        'vendor_id'        => $this->vendor->id,
        'status'           => OrderStatus::Delivered,
        'payment_method'   => 'cash_on_delivery',
        'subtotal'         => 100,
        'shipping_cost'    => 0,
        'total'            => 100,
        'shipping_name'    => 'Test',
        'shipping_phone'   => '0912345678',
        'shipping_city'    => 'Tripoli',
        'shipping_address' => 'Addr',
    ]);
    OrderItem::create([
        'order_id'     => $order->id,
        'product_id'   => $this->product->id,
        'vendor_id'    => $this->vendor->id,
        'product_name' => $this->product->name,
        'price'        => 100,
        'quantity'     => 1,
        'subtotal'     => 100,
    ]);

    $this->actingAs($this->customer)
        ->post(route('shop.reviews.store', $this->product), [
            'rating' => 5,
            'body'   => 'Verified purchase review here.',
        ]);

    expect(Review::first()->is_verified_purchase)->toBeTrue();
});

it('recalculates aggregate when admin hides a review', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(['super_admin']);

    $r1 = Review::create(['product_id' => $this->product->id, 'user_id' => $this->customer->id, 'rating' => 5, 'body' => 'Great product!']);
    $r2 = Review::create(['product_id' => $this->product->id, 'user_id' => User::factory()->create()->id, 'rating' => 1, 'body' => 'Not good at all.']);

    app(\App\Services\ProductRatingService::class)->recalculate($this->product);
    expect((float) $this->product->fresh()->avg_rating)->toBe(3.0);
    expect($this->product->fresh()->reviews_count)->toBe(2);

    $this->actingAs($admin)
        ->post(route('admin.reviews.toggle', $r2))
        ->assertRedirect();

    expect((float) $this->product->fresh()->avg_rating)->toBe(5.0);
    expect($this->product->fresh()->reviews_count)->toBe(1);
});

it('blocks a customer from deleting another customer review', function () {
    $otherUser = User::factory()->create();
    $review = Review::create([
        'product_id' => $this->product->id,
        'user_id'    => $otherUser->id,
        'rating'     => 4,
        'body'       => 'Someone else review body.',
    ]);

    $this->actingAs($this->customer)
        ->delete(route('shop.reviews.destroy', [$this->product, $review]))
        ->assertForbidden();
});