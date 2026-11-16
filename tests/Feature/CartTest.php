<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;

uses()->group('cart');

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->user->assignRole('customer');

    $owner = User::factory()->create();
    $this->vendor = Vendor::factory()->for($owner, 'owner')->active()->create();
    $this->category = Category::factory()->create();

    $this->product = Product::factory()->for($this->vendor, 'vendor')
        ->for($this->category, 'category')
        ->create(['price' => 100, 'stock' => 5, 'is_active' => true]);
});

it('adds a product to the cart', function () {
    $response = $this->actingAs($this->user)
        ->post(route('shop.cart.add', $this->product), ['quantity' => 2]);

    $response->assertRedirect();
    expect(session('cart'))->toBe([$this->product->id => 2]);
});

it('rejects quantity exceeding stock', function () {
    $this->actingAs($this->user)
        ->post(route('shop.cart.add', $this->product), ['quantity' => 99])
        ->assertSessionHas('cart_error');

    expect(session('cart'))->toBeEmpty();
});

it('rejects adding an invisible product', function () {
    $this->product->update(['is_active' => false]);

    $this->actingAs($this->user)
        ->post(route('shop.cart.add', $this->product), ['quantity' => 1])
        ->assertNotFound();
});

it('removes a product from the cart', function () {
    $this->actingAs($this->user)->post(route('shop.cart.add', $this->product), ['quantity' => 1]);

    $this->actingAs($this->user)
        ->delete(route('shop.cart.remove', $this->product))
        ->assertRedirect();

    expect(session('cart'))->toBeEmpty();
});