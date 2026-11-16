<?php

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PlatformEarning;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;

uses()->group('commissions');

it('records commission when an order is delivered', function () {
    $customer = User::factory()->create();
    $owner = User::factory()->create();
    $vendor = Vendor::factory()->for($owner, 'owner')->active()->create(['commission_rate' => 5.00]);
    $owner->assignRole('vendor');

    $order = Order::create([
        'order_number' => Order::generateOrderNumber(),
        'user_id'      => $customer->id,
        'vendor_id'    => $vendor->id,
        'status'       => OrderStatus::Shipped,
        'payment_method' => 'cash_on_delivery',
        'subtotal'     => 1000,
        'shipping_cost' => 0,
        'discount'     => 100,
        'total'        => 900,
        'shipping_name' => 'T', 'shipping_phone' => '0912345678',
        'shipping_city' => 'Tripoli', 'shipping_address' => 'A',
    ]);

    $this->actingAs($owner)->post(route('vendor.orders.deliver', $order));

    $order->refresh();
    expect((float) $order->commission_rate)->toBe(5.0);
    expect((float) $order->commission_amount)->toBe(45.0);   // (1000 - 100) * 5%
    expect((float) $order->vendor_net)->toBe(855.0);

    $earning = PlatformEarning::where('order_id', $order->id)->first();
    expect($earning)->not->toBeNull();
    expect((float) $earning->amount)->toBe(45.0);
    expect($earning->status)->toBe('pending');
});