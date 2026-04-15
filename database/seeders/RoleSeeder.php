<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        Role::create(['name' => 'SuperAdmin']);
        Role::create(['name' => 'Owner']); 
        Role::create(['name' => 'Manager']);
        Role::create(['name' => 'Administrator']);
        Role::create(['name' => 'Accounting']);
        Role::create(['name' => 'Production']);
        Role::create(['name' => 'Cashier']);
        Role::create(['name' => 'Inventory']);
        Role::create(['name' => 'Purchaser']);    
        Role::create(['name' => 'Nurse']);
        

    }

}
