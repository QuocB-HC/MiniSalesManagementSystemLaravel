<?php

namespace Database\Factories;

use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Shop;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'shop_id' => Shop::factory(),
            'name' => $this->faker->words(3, true),
            'sku' => $this->faker->unique()->bothify('PROD-####-????'),
            'description' => $this->faker->sentence(10),
            'stock_quantity' => $this->faker->numberBetween(10, 100),
            'committed_quantity' => 0,
            'price' => $this->faker->randomFloat(2, 100000, 5000000),
            'image_url' => 'https://picsum.photos/400/300?random='.$this->faker->unique()->numberBetween(1, 1000),
            'status' => $this->faker->randomElement([
                ProductStatus::PENDING,
                ProductStatus::APPROVED,
                ProductStatus::OUT_OF_STOCK,
            ]),
        ];
    }
}
