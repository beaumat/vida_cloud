<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class OtherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'others.requirement.view']);
        Permission::create(['name' => 'others.requirement.create']);
        Permission::create(['name' => 'others.requirement.edit']);
        Permission::create(['name' => 'others.requirement.delete']);


        Permission::create(['name' => 'others.shift.view']);
        Permission::create(['name' => 'others.shift.create']);
        Permission::create(['name' => 'others.shift.edit']);
        Permission::create(['name' => 'others.shift.delete']);

        Permission::create(['name' => 'others.hemodialysis-machine.view']);
        Permission::create(['name' => 'others.hemodialysis-machine.create']);
        Permission::create(['name' => 'others.hemodialysis-machine.edit']);
        Permission::create(['name' => 'others.hemodialysis-machine.delete']);

        Permission::create(['name' => 'others.item-active-list.view']);

        Permission::create(['name' => 'others.item-treatment.view']);
        Permission::create(['name' => 'others.item-treatment.create']);
        Permission::create(['name' => 'others.item-treatment.edit']);
        Permission::create(['name' => 'others.item-treatment.delete']);
    }
}
