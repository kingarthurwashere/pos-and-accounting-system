<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = $this->faker->sentence(4);

        return [
            'product_id' => fake()->numberBetween($min = 10, $max = 10000),
            'name' => $name,
            'slug' => Str::slug($name),
            'price' => fake()->numberBetween($min = 200, $max = 1200),
            'quantity' => fake()->numberBetween(1, 3),
            'sku' => fake()->bothify('SKU-####'),
            'is_inventory' => 0
        ];
    }
}
