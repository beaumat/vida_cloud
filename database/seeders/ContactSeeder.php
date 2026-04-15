<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    
        Permission::create(['name' => 'contact.customer.view']);
        Permission::create(['name' => 'contact.customer.create']);
        Permission::create(['name' => 'contact.customer.update']);
        Permission::create(['name' => 'contact.customer.delete']);
        Permission::create(['name' => 'contact.customer.print']);

        Permission::create(['name' => 'contact.vendor.view']);
        Permission::create(['name' => 'contact.vendor.create']);
        Permission::create(['name' => 'contact.vendor.update']);
        Permission::create(['name' => 'contact.vendor.delete']);
        Permission::create(['name' => 'contact.vendor.print']);

        Permission::create(['name' => 'contact.employee.view']);
        Permission::create(['name' => 'contact.employee.create']);
        Permission::create(['name' => 'contact.employee.update']);
        Permission::create(['name' => 'contact.employee.delete']);
        Permission::create(['name' => 'contact.employee.print']);

        Permission::create(['name' => 'contact.patient.view']);
        Permission::create(['name' => 'contact.patient.create']);
        Permission::create(['name' => 'contact.patient.update']);
        Permission::create(['name' => 'contact.patient.delete']);
        Permission::create(['name' => 'contact.patient.print']);

        Permission::create(['name' => 'contact.doctor.view']);
        Permission::create(['name' => 'contact.doctor.create']);
        Permission::create(['name' => 'contact.doctor.update']);
        Permission::create(['name' => 'contact.doctor.delete']);
        Permission::create(['name' => 'contact.doctor.print']);
        
    }
}
