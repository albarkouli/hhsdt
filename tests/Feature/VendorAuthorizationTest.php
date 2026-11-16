<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;

uses()->group('authorization');

beforeEach(function () {
    $this->vendorUser = User::factory()->create();
    $this->vendor = Vendor::factory()->for($this->vendorUser, 'owner')->active()->create();
    $this->vendorUser->assignRole('vendor');

    $this->otherVendorUser = User::factory()->create();
    $this->otherVendor = Vendor::factory()->for($this->otherVendorUser, 'owner')->active()->create();
    $this->otherVendorUser->assignRole('vendor');

    $this->category = Category::factory()->create();
});

it('blocks a customer from accessing the admin dashboard', function () {
    $customer = User::factory()->create();
    $customer->assignRole('customer');

    $this->actingAs($customer)->get(route('admin.dashboard'))->assertForbidden();
});

it('blocks a customer from accessing the vendor dashboard', function () {
    $customer = User::factory()->create();
    $customer->assignRole('customer');

    $this->actingAs($customer)->get(route('vendor.dashboard'))->assertForbidden();
});

it('blocks a vendor from accessing the admin dashboard', function () {
    $this->actingAs($this->vendorUser)->get(route('admin.dashboard'))->assertForbidden();
});

it('blocks a vendor from editing another vendor\'s product', function () {
    $foreign = Product::factory()->for($this->otherVendor, 'vendor')
        ->for($this->category, 'category')->create();

    $this->actingAs($this->vendorUser)
        ->get(route('vendor.products.edit', $foreign))
        ->assertForbidden();
});

it('blocks a vendor from deleting another vendor\'s product', function () {
    $foreign = Product::factory()->for($this->otherVendor, 'vendor')
        ->for($this->category, 'category')->create();

    $this->actingAs($this->vendorUser)
        ->delete(route('vendor.products.destroy', $foreign))
        ->assertForbidden();
});

it('blocks a suspended vendor from creating a product', function () {
    $this->vendor->update(['status' => 'suspended']);

    $this->actingAs($this->vendorUser)
        ->get(route('vendor.products.create'))
        ->assertForbidden();
});