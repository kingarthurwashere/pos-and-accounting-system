<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Location;
use App\Models\User;
use App\Models\WithdrawalRequestType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class WithdrawalRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = WithdrawalRequestType::inRandomOrder()->first()->slug;
        $department_id = Department::inRandomOrder()->first()->slug;
        $cashier_id = User::inRandomOrder()->first()->id;
        $status = $this->faker->randomElement(['PENDING', 'APPROVED', 'REJECTED']);

        return [
            'reference' => Str::random(10), // Generate a random string for the reference
            'email' => $this->faker->email,
            'type' => $type, // Assuming you have predefined types
            'amount' => $this->faker->numberBetween(100, 10000),
            'agent_balance_deductible' => $type === 'salary',
            'status' => $status,
            'approved_by' => $status === 'APPROVED' ? $cashier_id : null,
            'rejection_message' => $status === 'REJECTED' ? $this->faker->sentence : null,
            'rejected_by' => $status === 'REJECTED' ? $cashier_id : null,
            'approval_datetime' => $status === 'APPROVED' ? $this->faker->dateTimeBetween('-1 year', 'now') : null,
            'rejection_datetime' => $status === 'REJECTED' ? $this->faker->dateTimeBetween('-1 year', 'now') : null,
        ];
    }
}
