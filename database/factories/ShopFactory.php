<?php

namespace Database\Factories;

use App\Enums\ShopStatus;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Shop>
 */
class ShopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->company(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'logo_url' => $this->faker->imageUrl(200, 200, 'business', true, 'logo'),
            'facebook_url' => 'https://facebook.com/'.$this->faker->userName(),
            'instagram_url' => 'https://instagram.com/'.$this->faker->userName(),
            'twitter_url' => 'https://twitter.com/'.$this->faker->userName(),
            'status' => ShopStatus::APPROVED,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
