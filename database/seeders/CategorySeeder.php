<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics',       'description' => 'Phones, laptops, and accessories', 'sort_order' => 10],
            ['name' => 'Fashion',           'description' => 'Clothing, shoes, and accessories',  'sort_order' => 20],
            ['name' => 'Home & Garden',     'description' => 'Furniture, decor, and tools',      'sort_order' => 30],
            ['name' => 'Sports & Outdoors', 'description' => 'Fitness and outdoor equipment',     'sort_order' => 40],
            ['name' => 'Books & Media',     'description' => 'Books, music, and movies',          'sort_order' => 50],
            ['name' => 'Beauty & Health',   'description' => 'Cosmetics and personal care',       'sort_order' => 60],
            ['name' => 'Toys & Kids',       'description' => 'Toys and children\'s products',     'sort_order' => 70],
            ['name' => 'Food & Beverages',  'description' => 'Groceries and drinks',              'sort_order' => 80],
        ];

        foreach ($categories as $data) {
            Category::firstOrCreate(
                ['name' => $data['name']],
                $data + ['is_active' => true]
            );
        }
    }
}