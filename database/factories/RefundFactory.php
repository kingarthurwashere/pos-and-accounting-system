<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\RefundType;
use App\Models\User;
use App\Models\Payment;
use App\Models\Location;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Refund>
 */
class RefundFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['INITIATED', 'APPROVED', 'REJECTED']);
        $payment = Payment::whereStatus('RECEIVED')->first();

        return [
            'payment_id' => $payment->id,
            'amount' => $payment->received_amount,
            'status' => $status,
            'initiated_by' => User::inRandomOrder()->first()->id,
            'approved_by' => $status === 'APPROVED' ? User::inRandomOrder()->first()->id : null,
            'rejected_by' => $status === 'REJECTED' ? User::inRandomOrder()->first()->id : null,
            'initiation_location_id' => Location::inRandomOrder()->first()->id,
            'initiation_datetime' => Carbon::now(),
            'approval_datetime' => $status === 'APPROVED' ? Carbon::now() : null,
            'rejection_datetime' => $status === 'REJECTED' ? Carbon::now() : null,
        ];
    }
}
