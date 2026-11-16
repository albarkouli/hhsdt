<?php

namespace Database\Factories;

use App\Enums\VendorStatus;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'user_id'     => User::factory(),
            'name'        => $name,
            'slug'        => Str::slug($name) . '-' . fake()->unique()->numerify('####'),
            'description' => fake()->paragraph(),
            'phone'       => fake()->numerify('09########'),
            'email'       => fake()->safeEmail(),
            'address'     => fake()->address(),
            'city'        => fake()->randomElement(['Tripoli', 'Benghazi', 'Misrata', 'Zawiya', 'Sabha']),
            'status'      => VendorStatus::Active,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => VendorStatus::Pending]);
    }

    public function active(): static
    {
        return $this->state(fn () => ['status' => VendorStatus::Active]);
    }
}