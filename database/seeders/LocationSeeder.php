<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class LocationSeeder extends Seeder
{
    protected $locations = [];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if (App::environment('local')) {
            $this->development();
        } elseif (App::environment('staging')) {
            $this->development();
        } elseif (App::environment('production')) {
            // Seeders for production
            $this->production();
        }
    }

    private function development()
    {
        $this->locations = [
            [
                'alias' => fake()->name() . ' House',
                'city_id' => 1,
            ],
            [
                'alias' => fake()->name() . ' House',
                'city_id' => City::where('active', 1)->inRandomOrder()->first()->id,
            ],
            [
                'alias' => fake()->name() . ' House',
                'city_id' => City::where('active', 1)->inRandomOrder()->first()->id,
            ],
            [
                'alias' => fake()->name() . ' House',
                'city_id' => City::where('active', 1)->inRandomOrder()->first()->id,
            ],
            [
                'alias' => fake()->name() . ' House',
                'city_id' => City::where('active', 1)->inRandomOrder()->first()->id,
            ],
            [
                'alias' => fake()->name() . ' House',
                'city_id' => City::where('active', 1)->inRandomOrder()->first()->id,
            ],
            [
                'alias' => fake()->name() . ' House',
                'city_id' => City::where('active', 1)->inRandomOrder()->first()->id,
            ],
            [
                'alias' => fake()->name() . ' House',
                'city_id' => City::where('active', 1)->inRandomOrder()->first()->id,
            ],
            [
                'alias' => fake()->name() . ' House',
                'city_id' => City::where('active', 1)->inRandomOrder()->first()->id,
            ],
            [
                'alias' => fake()->name() . ' House',
                'city_id' => City::where('active', 1)->inRandomOrder()->first()->id,
            ],
        ];
        foreach ($this->locations as $location) {
            Location::create([
                'alias' => $location['alias'],
                'slug' => Str::slug($location['alias']),
                'city_id' => $location['city_id'],
            ]);
        }
    }

    private function production()
    {
        $this->locations = [
            [
                'alias' => 'Regal Star Mall',
                'city_id' => City::where('name', 'Harare')->first()->id, //Harare
            ],
        ];

        foreach ($this->locations as $location) {
            Location::create([
                'alias' => $location['alias'],
                'slug' => Str::slug($location['alias']),
                'city_id' => $location['city_id'],
            ]);
        }
    }
}
