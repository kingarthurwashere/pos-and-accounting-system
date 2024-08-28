<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    protected $departments = [
        ['name' => 'Buying / Procurement', 'slug' => 'buying-procurement'],
        ['name' => 'Sales Op', 'slug' => 'sales-op'],
        ['name' => 'Corporate', 'slug' => 'corporate'],
        ['name' => 'Finance & Admin', 'slug' => 'finance-and-admin'],
        ['name' => 'Instabucks', 'slug' => 'instabucks'],
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->insert($this->departments);
    }
}
