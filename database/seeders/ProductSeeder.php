<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shops = Shop::factory(5)->create();

        $categories = Category::factory(10)->create();

        Product::factory(200)
            ->recycle($shops)
            ->recycle($categories)
            ->create();
    }
}
