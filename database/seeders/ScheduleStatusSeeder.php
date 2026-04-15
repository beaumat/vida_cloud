<?php

namespace Database\Seeders;

use App\Models\ScheduleStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {

            ScheduleStatus::create(['ID' => 0, 'DESCRIPTION' => 'Waiting']);
            ScheduleStatus::create(['ID' => 1, 'DESCRIPTION' => 'Present']);
            ScheduleStatus::create(['ID' => 2, 'DESCRIPTION' => 'Absent']);
            ScheduleStatus::create(['ID' => 3, 'DESCRIPTION' => 'Cancelled']);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
