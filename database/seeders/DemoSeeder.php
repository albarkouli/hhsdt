<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name'  => 'Super Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);
        $admin->syncRoles(['super_admin']);

        foreach ([
            ['name' => 'Ahmed Ali',   'email' => 'customer1@test.com'],
            ['name' => 'Fatima Omar', 'email' => 'customer2@test.com'],
        ] as $data) {
            $user = User::factory()->create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => bcrypt('password'),
            ]);
            $user->syncRoles(['customer']);
        }

        $categories = Category::all();

        $vendorData = [
            [
                'name'  => 'Tripoli Electronics',
                'city'  => 'Tripoli',
                'desc'  => 'Your trusted source for phones, laptops, and electronics in Tripoli.',
                'owner' => ['name' => 'Mohammed Hamid', 'email' => 'vendor1@test.com'],
                'products' => [
                    ['name' => 'iPhone 15 Pro 256GB', 'price' => 3500, 'stock' => 10, 'cat' => 'Electronics'],
                    ['name' => 'Samsung Galaxy S24', 'price' => 2800, 'stock' => 5,  'cat' => 'Electronics'],
                    ['name' => 'AirPods Pro 2',      'price' => 450,  'stock' => 20, 'cat' => 'Electronics'],
                    ['name' => 'Anker Power Bank',   'price' => 120,  'stock' => 30, 'cat' => 'Electronics'],
                ],
            ],
            [
                'name'  => 'Benghazi Fashion',
                'city'  => 'Benghazi',
                'desc'  => 'Latest fashion trends for men and women, delivered across Libya.',
                'owner' => ['name' => 'Layla Ben Amer', 'email' => 'vendor2@test.com'],
                'products' => [
                    ['name' => 'Mens Leather Jacket',   'price' => 650, 'stock' => 8,  'cat' => 'Fashion'],
                    ['name' => 'Womens Summer Dress',   'price' => 180, 'stock' => 15, 'cat' => 'Fashion'],
                    ['name' => 'Nike Running Shoes',    'price' => 420, 'stock' => 12, 'cat' => 'Fashion'],
                ],
            ],
            [
                'name'  => 'Misrata Home Store',
                'city'  => 'Misrata',
                'desc'  => 'Everything for your home — furniture, decor, kitchen tools.',
                'owner' => ['name' => 'Omar Al-Misrati', 'email' => 'vendor3@test.com'],
                'products' => [
                    ['name' => 'Modern Sofa Set',        'price' => 2200, 'stock' => 3,  'cat' => 'Home & Garden'],
                    ['name' => 'Dining Table 6 seats',   'price' => 1400, 'stock' => 4,  'cat' => 'Home & Garden'],
                    ['name' => 'Non-Stick Cookware Set', 'price' => 320,  'stock' => 10, 'cat' => 'Home & Garden'],
                ],
            ],
            [
                'name'  => 'Libya Sports Hub',
                'city'  => 'Tripoli',
                'desc'  => 'Sports equipment and outdoor gear for every athlete.',
                'owner' => ['name' => 'Khalid Al-Werfalli', 'email' => 'vendor4@test.com'],
                'products' => [
                    ['name' => 'Adidas Football',         'price' => 150, 'stock' => 25, 'cat' => 'Sports & Outdoors'],
                    ['name' => 'Yoga Mat Premium',        'price' => 90,  'stock' => 20, 'cat' => 'Sports & Outdoors'],
                    ['name' => 'Camping Tent 4 person',   'price' => 480, 'stock' => 6,  'cat' => 'Sports & Outdoors'],
                ],
            ],
        ];

        foreach ($vendorData as $v) {
            $owner = User::factory()->create([
                'name'     => $v['owner']['name'],
                'email'    => $v['owner']['email'],
                'password' => bcrypt('password'),
            ]);
            $owner->syncRoles(['vendor']);

            $vendor = Vendor::create([
                'user_id'     => $owner->id,
                'name'        => $v['name'],
                'slug'        => \Illuminate\Support\Str::slug($v['name']),
                'description' => $v['desc'],
                'phone'       => '09' . rand(10000000, 99999999),
                'email'       => $v['owner']['email'],
                'city'        => $v['city'],
                'address'     => 'Main Street, ' . $v['city'],
                'status'      => 'active',
            ]);

            foreach ($v['products'] as $p) {
                $cat = $categories->firstWhere('name', $p['cat']);

                Product::create([
                    'vendor_id'   => $vendor->id,
                    'category_id' => $cat?->id,
                    'name'        => $p['name'],
                    'slug'        => \Illuminate\Support\Str::slug($p['name']) . '-' . rand(1000, 9999),
                    'description' => 'High quality product from ' . $vendor->name,
                    'price'       => $p['price'],
                    'stock'       => $p['stock'],
                    'sku'         => strtoupper(\Illuminate\Support\Str::random(8)),
                    'is_active'   => true,
                ]);
            }
        }

        $this->command->info('Demo data seeded successfully!');
    }
}