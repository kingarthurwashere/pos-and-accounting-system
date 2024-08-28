<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\StockProduct;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StockProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StockProduct::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $fashionCategories = [
            'Men\'s Clothing',
            'Women\'s Clothing',
            'Kid\'s Clothing',
            'Footwear',
            'Accessories',
            'Jewelry',
            'Bags',
            'Sportswear',
            'Formal Wear',
            'Casual Wear',
            // Add more categories as needed
        ];
        $name = $this->faker->unique()->words(3, true);
        $location = Location::inRandomOrder()->first();
        return [
            'name' => $name,
            'slug' => Str::slug($name), // Generate a slug from the name.
            'price' => $this->faker->numberBetween($min = 200, $max = 18000),
            'price_approved' => fake()->boolean(),
            'uploaded_by' => User::inRandomOrder()->first()->id,
            'price_approved_by' => User::inRandomOrder()->first()->id,
            'initial_stock_taker' => fake()->name,
            'size' => fake()->numberBetween(4, 12) . 'UK',
            'category' => fake()->randomElement($fashionCategories),
            'city_name' => $location->city->name,
            'color' => fake()->colorName,
            'location_id' => $location->id,
            'stock_quantity' => $this->faker->numberBetween($min = 1, $max = 1000),  // Randomly assign available as true or false.
            'sku' => $this->faker->unique()->bothify('SKU-####'), // Generate a unique SKU code.
        ];
    }
}
