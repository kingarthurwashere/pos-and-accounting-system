<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch all orders with a balance greater than 0
        $all_orders = Order::where('balance', '>', 0)->get();

        // Calculate 75% of all such orders
        $total_orders = floor($all_orders->count() * 0.75);

        // Randomly select a subset of these orders to create payments for
        // This approach assumes you want to randomly select orders without prioritizing the latest or oldest
        $orders = $all_orders->random($total_orders);

        foreach ($orders as $order) {
            $paid_amount = fake()->numberBetween(100, $order->balance);

            // Common attributes for Payment creation
            $commonAttributes = [
                'payable_identifier' => $order->id,
                'amount_due' => $paid_amount,
                'covers_full_balance' => false,
                'payable_slug' => 'order', // Make sure this aligns with your database schema
                'initiated_by' => User::inRandomOrder()->first()->id,
            ];

            // Create Payment with common attributes
            Invoice::create($commonAttributes);
        }
    }
}
