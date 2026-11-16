<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Wishlist;

uses()->group('wishlist');

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

it('adds a product to the wishlist', function () {
    $this->actingAs($this->customer)
        ->postJson(route('shop.wishlist.toggle', $this->product))
        ->assertOk()
        ->assertJson(['added' => true, 'count' => 1]);

    expect(Wishlist::where('user_id', $this->customer->id)->where('product_id', $this->product->id)->exists())->toBeTrue();
});

it('removes a product from the wishlist when toggled again', function () {
    $this->customer->wishlist()->attach($this->product->id);

    $this->actingAs($this->customer)
        ->postJson(route('shop.wishlist.toggle', $this->product))
        ->assertOk()
        ->assertJson(['added' => false, 'count' => 0]);

    expect(Wishlist::count())->toBe(0);
});

it('shows only the current user wishlist', function () {
    $otherUser = User::factory()->create();

    $this->customer->wishlist()->attach($this->product->id);
    $otherUser->wishlist()->attach($this->product->id);

    $this->actingAs($this->customer)
        ->get(route('shop.wishlist.index'))
        ->assertOk();

    expect($this->customer->wishlist()->count())->toBe(1);
    expect($otherUser->wishlist()->count())->toBe(1);
});

it('requires authentication to toggle wishlist', function () {
    $this->post(route('shop.wishlist.toggle', $this->product))
        ->assertRedirect(route('login'));
});