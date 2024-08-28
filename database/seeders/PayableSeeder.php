<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PayableSeeder extends Seeder
{
    protected $payables = [
        ['name' => 'Order', 'slug' => 'order'],
        ['name' => 'Remittance', 'slug' => 'remittance'],
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payables')->insert($this->payables);
    }
}
