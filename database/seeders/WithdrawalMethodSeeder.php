<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WithdrawalMethodSeeder extends Seeder
{
    protected $refund_methods = [
        ['slug' => 'CASH', 'name' => 'Cash'],
        ['slug' => 'BANK_TRANSFER', 'name' => 'Bank Transfer'],
        ['slug' => 'ORDER_BALANCE', 'name' => 'Order Balance'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Insert the data into the database
            DB::table('withdrawal_methods')->insert($this->refund_methods);
        });
    }
}
