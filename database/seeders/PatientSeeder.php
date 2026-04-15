<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'patient.schedule.view']);
        Permission::create(['name' => 'patient.schedule.modify']);
        Permission::create(['name' => 'patient.schedule.posted']);
        Permission::create(['name' => 'patient.schedule.print']);

        Permission::create(['name' => 'patient.service-charges.view']);
        Permission::create(['name' => 'patient.service-charges.create']);
        Permission::create(['name' => 'patient.service-charges.update']);
        Permission::create(['name' => 'patient.service-charges.delete']);

        Permission::create(['name' => 'patient.payment.view']);
        Permission::create(['name' => 'patient.payment.create']);
        Permission::create(['name' => 'patient.payment.update']);
        Permission::create(['name' => 'patient.payment.delete']);
        Permission::create(['name' => 'patient.payment.print']);

        Permission::create(['name' => 'patient.treatment.view']);
        Permission::create(['name' => 'patient.treatment.create']);
        Permission::create(['name' => 'patient.treatment.update']);
        Permission::create(['name' => 'patient.treatment.delete']);
        Permission::create(['name' => 'patient.treatment.print']);
        
        Permission::create(['name' => 'patient.philhealth.view']);
        Permission::create(['name' => 'patient.philhealth.create']);
        Permission::create(['name' => 'patient.philhealth.update']);
        Permission::create(['name' => 'patient.philhealth.delete']);
        Permission::create(['name' => 'patient.philhealth.print']);
        

        
    }
}
