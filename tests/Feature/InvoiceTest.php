<?php

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;

uses()->group('invoice');

beforeEach(function () {
    $this->customer = User::factory()->create();
    $this->customer->assignRole('customer');

    $owner = User::factory()->create();
    $this->vendor = Vendor::factory()->for($owner, 'owner')->active()->create();
    $owner->assignRole('vendor');

    $this->category = Category::factory()->create();
    $this->product = Product::factory()->for($this->vendor, 'vendor')
        ->for($this->category, 'category')
        ->create(['price' => 100, 'stock' => 10]);

    $this->order = Order::create([
        'order_number'     => Order::generateOrderNumber(),
        'user_id'          => $this->customer->id,
        'vendor_id'        => $this->vendor->id,
        'status'           => OrderStatus::Pending,
        'payment_method'   => 'cash_on_delivery',
        'subtotal'         => 200,
        'shipping_cost'    => 0,
        'total'            => 200,
        'shipping_name'    => 'Test',
        'shipping_phone'   => '0912345678',
        'shipping_city'    => 'Tripoli',
        'shipping_address' => 'Addr',
    ]);

    OrderItem::create([
        'order_id'     => $this->order->id,
        'product_id'   => $this->product->id,
        'vendor_id'    => $this->vendor->id,
        'product_name' => $this->product->name,
        'price'        => 100,
        'quantity'     => 2,
        'subtotal'     => 200,
    ]);
});

it('generates an invoice when order is confirmed', function () {
    expect(Invoice::count())->toBe(0);

    $this->actingAs($this->vendor->owner)
        ->post(route('vendor.orders.confirm', $this->order))
        ->assertRedirect();

    expect(Invoice::count())->toBe(1);
    expect(Invoice::first()->status)->toBe('issued');
    expect((float) Invoice::first()->snapshot['totals']['total'])->toBe(200.0);
});

it('does not generate invoice if order is already confirmed', function () {
    $this->order->update(['status' => OrderStatus::Confirmed]);

    $this->actingAs($this->vendor->owner)
        ->post(route('vendor.orders.confirm', $this->order));

    expect(Invoice::count())->toBe(0);
});

it('cancels the invoice when order is cancelled', function () {
    $this->actingAs($this->vendor->owner)
        ->post(route('vendor.orders.confirm', $this->order));

    expect(Invoice::first()->status)->toBe('issued');

    $this->actingAs($this->vendor->owner)
        ->post(route('vendor.orders.cancel', $this->order));

    expect(Invoice::first()->fresh()->status)->toBe('cancelled');
});