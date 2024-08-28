<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\DepartmentBudget;

class DepartmentBudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = Department::get();

        foreach ($departments as $department) {
            $department->budget()->create([
                'amount' => 300000
            ]);
        }
    }
}
