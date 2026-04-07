<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 categories, each with 20 products
        Category::factory(10)
            ->hasProducts(20)
            ->create();
    }
}
