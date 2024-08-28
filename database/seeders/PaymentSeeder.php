<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Location;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentSource;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Ensure you have this if you're using the DB facade for transactions or raw queries

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch all orders with a balance greater than 0
        $invoices = Invoice::get();

        foreach ($invoices as $i) {
            $paid_amount = fake()->numberBetween(100, $i->balance);

            // Common attributes for Payment creation
            $commonAttributes = [
                'invoice_id' => $i->id,
                'payable_identifier' => $i->payable_identifier,
                'covers_full_balance' => $i->covers_full_balance,
                'received_amount' =>  $i->amount_due,
                'tender' => $i->amount_due,
                'change_amount' => 0,
                'payable_slug' => 'order', // Make sure this aligns with your database schema
                'initiated_by' => $i->initiated_by,
                'location_id' => Location::inRandomOrder()->first()->id,
                'opening_balance' => Order::find($i->payable_identifier)->balance,
                'source' => PaymentSource::inRandomOrder()->first()->slug,
            ];

            // Create Payment with common attributes
            Payment::create($commonAttributes);
        }
    }
}
