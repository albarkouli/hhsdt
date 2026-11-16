<?php

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;

uses()->group('orders');

beforeEach(function () {
    $this->customer = User::factory()->create();
    $this->customer->assignRole('customer');

    $owner = User::factory()->create();
    $this->vendor = Vendor::factory()->for($owner, 'owner')->active()->create();
    $this->category = Category::factory()->create();
});

it('creates an order and decrements stock atomically', function () {
    $product = Product::factory()->for($this->vendor, 'vendor')
        ->for($this->category, 'category')
        ->create(['price' => 100, 'stock' => 10, 'is_active' => true]);

    $this->actingAs($this->customer)->post(route('shop.cart.add', $product), ['quantity' => 3]);

    $response = $this->actingAs($this->customer)->post(route('shop.checkout.store'), [
        'shipping_name'    => 'Test Buyer',
        'shipping_phone'   => '0912345678',
        'shipping_city'    => 'Tripoli',
        'shipping_address' => 'Test Street 1',
        'notes'            => 'Call first',
    ]);

    $response->assertRedirect(route('shop.orders.index'));

    expect(Order::count())->toBe(1);
    expect($product->fresh()->stock)->toBe(7);
});

it('splits the order by vendor', function () {
    // Second vendor
    $owner2 = User::factory()->create();
    $vendor2 = Vendor::factory()->for($owner2, 'owner')->active()->create();

    $product1 = Product::factory()->for($this->vendor, 'vendor')
        ->for($this->category, 'category')
        ->create(['price' => 100, 'stock' => 5, 'is_active' => true]);
    $product2 = Product::factory()->for($vendor2, 'vendor')
        ->for($this->category, 'category')
        ->create(['price' => 200, 'stock' => 3, 'is_active' => true]);

    $this->actingAs($this->customer)->post(route('shop.cart.add', $product1), ['quantity' => 1]);
    $this->actingAs($this->customer)->post(route('shop.cart.add', $product2), ['quantity' => 1]);

    $this->actingAs($this->customer)->post(route('shop.checkout.store'), [
        'shipping_name'    => 'Test',
        'shipping_phone'   => '0912345678',
        'shipping_city'    => 'Tripoli',
        'shipping_address' => 'Test Street 1',
    ]);

    expect(Order::count())->toBe(2);
    expect($product1->fresh()->stock)->toBe(4);
    expect($product2->fresh()->stock)->toBe(2);
});

it('snapshots product name and price at purchase time', function () {
    $product = Product::factory()->for($this->vendor, 'vendor')
        ->for($this->category, 'category')
        ->create(['name' => 'Original Name', 'price' => 100, 'stock' => 5, 'is_active' => true]);

    $this->actingAs($this->customer)->post(route('shop.cart.add', $product), ['quantity' => 1]);
    $this->actingAs($this->customer)->post(route('shop.checkout.store'), [
        'shipping_name' => 'Test', 'shipping_phone' => '0912345678',
        'shipping_city' => 'Tripoli', 'shipping_address' => 'Addr',
    ]);

    // Product changes after purchase
    $product->update(['name' => 'Renamed Product', 'price' => 999]);

    $item = Order::first()->items->first();
    expect($item->product_name)->toBe('Original Name');
    expect((float) $item->price)->toBe(100.0);
});

it('does not allow a customer to view another customer\'s order', function () {
    $order = Order::factory()->create(['user_id' => User::factory()->create()->id]);

    $other = User::factory()->create();
    $other->assignRole('customer');

    $this->actingAs($other)
        ->get(route('shop.orders.show', $order))
        ->assertForbidden();
});