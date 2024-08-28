<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSourceSeeder extends Seeder
{
    protected $sources = [
        ['name' => 'AgentX Cash', 'slug' => 'AGENTX-CASH'],
        ['name' => 'Cash', 'slug' => 'CASH'],
        ['name' => 'USD Swipe', 'slug' => 'USD-SWIPE'],
        ['name' => 'Refund', 'slug' => 'REFUND'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_sources')->insert($this->sources);
    }
}
