<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ReportSeeder extends Seeder
{   

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'report.patient.sales']);
        
    }
}
