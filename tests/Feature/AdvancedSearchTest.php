<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;

uses()->group('search');

beforeEach(function () {
    $owner = User::factory()->create();
    $this->vendor = Vendor::factory()->for($owner, 'owner')->active()->create();
    $this->category = Category::factory()->create(['name' => 'Electronics', 'slug' => 'electronics']);

    $this->p1 = Product::factory()->for($this->vendor, 'vendor')->for($this->category, 'category')
        ->create(['name' => 'iPhone 15', 'price' => 3500, 'stock' => 5, 'is_active' => true, 'avg_rating' => 4.5, 'reviews_count' => 10]);
    $this->p2 = Product::factory()->for($this->vendor, 'vendor')->for($this->category, 'category')
        ->create(['name' => 'Samsung S24', 'price' => 2800, 'stock' => 3, 'is_active' => true, 'avg_rating' => 4.8, 'reviews_count' => 15]);
    $this->p3 = Product::factory()->for($this->vendor, 'vendor')->for($this->category, 'category')
        ->create(['name' => 'Budget Phone', 'price' => 200, 'stock' => 0, 'is_active' => true, 'avg_rating' => 3.0, 'reviews_count' => 2]);
});

it('filters products by price range', function () {
    $response = $this->get(route('shop.products.index', ['min_price' => 500, 'max_price' => 3000]));
    $response->assertOk();

    $names = $response->viewData('products')->pluck('name')->toArray();
    expect($names)->toContain('Samsung S24');
    expect($names)->not->toContain('iPhone 15');
    expect($names)->not->toContain('Budget Phone');
});

it('sorts products by price ascending', function () {
    $response = $this->get(route('shop.products.index', ['sort' => 'price_asc']));
    $names = $response->viewData('products')->pluck('name')->toArray();

    // Budget is out of stock by default, so only 2 products visible; Samsung (2800) < iPhone (3500)
    expect($names[0])->toBe('Samsung S24');
    expect($names[1])->toBe('iPhone 15');
});

it('excludes out of stock products by default but includes them when toggled', function () {
    $default = $this->get(route('shop.products.index'));
    expect($default->viewData('products')->pluck('name'))->not->toContain('Budget Phone');

    $included = $this->get(route('shop.products.index', ['include_out_of_stock' => 1]));
    expect($included->viewData('products')->pluck('name'))->toContain('Budget Phone');
});