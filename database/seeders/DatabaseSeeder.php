<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(DepartmentBudgetSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(LocationSeeder::class);
        $this->call(PaymentSourceSeeder::class);
        //\App\Models\StockProduct::factory(100)->create();
        //$this->call(OrderItemSeeder::class);
        $this->call(AgentSeeder::class);
        $this->call(PayableSeeder::class);
        $this->call(RefundMethodSeeder::class);
        $this->call(WithdrawalMethodSeeder::class);
        $this->call(WithdrawalRequestTypeSeeder::class);
        //$this->call(InvoiceSeeder::class);
        //$this->call(PaymentSeeder::class);
        //$this->call(PaymentReceiveSeeder::class);
        $this->call(RemittanceSeeder::class);
        //$this->call(RemittanceDailySeeder::class);
        //$this->call(OrderDailySeeder::class);
        //\App\Models\WithdrawalRequest::factory(100)->create();
        //\App\Models\Refund::factory(20)->create();


    }
}
