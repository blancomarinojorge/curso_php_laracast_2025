<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Job;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::factory(30)->create();
        Job::factory(200)->create();
    }
}
