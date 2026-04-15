<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class MaintenanceSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'users']);
        
        Permission::create(['name' => 'roles-and-permission']);

        Permission::create(['name' => 'location.view']);
        Permission::create(['name' => 'location.create']);
        Permission::create(['name' => 'location.edit']);
        Permission::create(['name' => 'location.delete']);

        Permission::create(['name' => 'location-group.view']);
        Permission::create(['name' => 'location-group.create']);
        Permission::create(['name' => 'location-group.edit']);
        Permission::create(['name' => 'location-group.delete']);

        Permission::create(['name' => 'option']);
    }
}
