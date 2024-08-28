<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentReceiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payments = Payment::inRandomOrder()->take(floor(Payment::count() * 0.7))->get();

        foreach ($payments as $payment) {
            $payment->receiving_cashier = User::inRandomOrder()->first()->id;
            $payment->opening_balance = $payment->opening_balance - $payment->received_amount;
            $payment->status = 'RECEIVED';
            $payment->received_amount_datetime = Carbon::now();
            $payment->save();
        }
    }
}
