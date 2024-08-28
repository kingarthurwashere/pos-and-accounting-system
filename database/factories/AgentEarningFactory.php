<?php

namespace Database\Factories;

use App\Models\Agent;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AgentEarning>
 */
class AgentEarningFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'agent_id' => fake()->randomElement(Agent::all()->pluck('id')),
            'order_id' => fake()->randomElement(Order::all()->pluck('id')),
            'amount' => fake()->unique()->numberBetween($min = 100, $max = 10000),
        ];
    }
}
