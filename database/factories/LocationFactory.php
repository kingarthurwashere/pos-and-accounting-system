<?php

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $alias = fake()->name();

        return [
            'alias' => fake()->streetName,
            'slug' => Str::slug($alias),
            'city_id' => City::where('active', 1)->inRandomOrder()->first()->id,
            'usd_balance' => 0,
        ];
    }
}
