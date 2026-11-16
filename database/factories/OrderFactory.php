<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 10, 1000);

        return [
            'order_number'     => 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
            'user_id'          => User::factory(),
            'vendor_id'        => Vendor::factory(),
            'status'           => OrderStatus::Pending,
            'payment_method'   => 'cash_on_delivery',
            'subtotal'         => $subtotal,
            'shipping_cost'    => 0,
            'total'            => $subtotal,
            'shipping_name'    => fake()->name(),
            'shipping_phone'   => '09' . fake()->numerify('########'),
            'shipping_city'    => fake()->randomElement(['Tripoli', 'Benghazi', 'Misrata']),
            'shipping_address' => fake()->address(),
            'notes'            => null,
        ];
    }
}