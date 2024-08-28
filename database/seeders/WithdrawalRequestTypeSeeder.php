<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WithdrawalRequestTypeSeeder extends Seeder
{
    protected $withdrawal_request_types = [
        ['slug' => 'salary', 'name' => 'Salary', 'description' => 'Salary'],
        ['slug' => 'rent', 'name' => 'Rent', 'description' => 'Rent'],
        ['slug' => 'electricity', 'name' => 'Electricity', 'description' => 'Electricity'],
        ['slug' => 'airtime', 'name' => 'Airtime & Data', 'description' => 'Airtime & Data'],
        ['slug' => 'transport', 'name' => 'Transport', 'description' => 'Transport'],
        ['slug' => 'water', 'name' => 'Water', 'description' => 'Water'],
        ['slug' => 'freight', 'name' => 'Freight & Customs', 'description' => 'Freight & Customs'],
        ['slug' => 'dubai remittances', 'name' => 'Dubai remittances', 'description' => 'Dubai remittances'],
        ['slug' => 'dxb hub utilities', 'name' => 'DXB hub Utilities', 'description' => 'DXB hub Utilities'],
        ['slug' => 'staff rent', 'name' => 'Staff Rent', 'description' => 'Staff Rent'],
        ['slug' => 'wif', 'name' => 'WiFi', 'description' => 'WiFi'],
        ['slug' => 'taxi', 'name' => 'Taxi', 'description' => 'Taxi'],


    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {

            // Insert the data into the database
            DB::table('withdrawal_request_types')->insert($this->withdrawal_request_types);
        });
    }
}
