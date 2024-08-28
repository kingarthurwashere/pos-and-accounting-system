<?php

namespace Database\Factories;

use App\Enums\OrderSource;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $total = fake()->numberBetween(100, 1000);

        // Randomly decide if the order is fully paid (balance = 0) or not paid at all (balance = total)
        $isFullyPaid = fake()->boolean(); // 50% chance

        $balance = $isFullyPaid ? 0 : $total;

        $source = fake()->randomElement(array_map(fn ($source) => $source->value, OrderSource::cases()));

        return [
            'total' => $total,
            'order_id' => $source === OrderSource::ONLINE ? fake()->numberBetween(1, 10000) : null,
            'balance' => $balance,
            'source' => $source,
            'agent_id' => fake()->randomElement(Agent::all()->pluck('id')->toArray()),
            'customer_name' => fake()->name(),
            'customer_phone' => fake()->phoneNumber(),
            'customer_email' => fake()->unique()->safeEmail(),
            'created_by' => fake()->randomElement(User::all()->pluck('id')->toArray()),
        ];
    }
}
