<?php

namespace Database\Factories;

use App\Models\Category;
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
            'category_id' => Category::factory(), // Auto-generate category_id using CategoryFactory
            'name' => $this->faker->words(3, true), // Product name with 3 words
            'sku' => $this->faker->unique()->bothify('PROD-####-????'), // Unique SKU with format PROD-1234-ABCD
            'description' => $this->faker->sentence(10), // Description with 10 words
            'stock_quantity' => $this->faker->numberBetween(10, 100), // Stock quantity between 10 and 100
            'committed_quantity' => 0, // Default committed quantity to 0 for new products
            'price' => $this->faker->randomFloat(2, 100000, 5000000), // Price between 100,000 and 5,000,000 VND
            'image_url' => 'https://picsum.photos/400/300?random='.$this->faker->unique()->numberBetween(1, 1000), // Random image URL
            'is_disabled' => false, // Default to not disabled
            'status' => $this->faker->randomElement(['available', 'out_of_stock', 'pre-order']),
        ];
    }
}
