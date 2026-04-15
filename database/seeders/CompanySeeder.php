<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        Permission::create(['name' => 'company.stock-transfer.view']);
        Permission::create(['name' => 'company.stock-transfer.create']);
        Permission::create(['name' => 'company.stock-transfer.update']);
        Permission::create(['name' => 'company.stock-transfer.delete']);
        Permission::create(['name' => 'company.stock-transfer.print']);

        Permission::create(['name' => 'company.build-assembly.view']);
        Permission::create(['name' => 'company.build-assembly.create']);
        Permission::create(['name' => 'company.build-assembly.update']);
        Permission::create(['name' => 'company.build-assembly.delete']);
        Permission::create(['name' => 'company.build-assembly.print']);

        Permission::create(['name' => 'company.inventory-adjustment.view']);
        Permission::create(['name' => 'company.inventory-adjustment.create']);
        Permission::create(['name' => 'company.inventory-adjustment.update']);
        Permission::create(['name' => 'company.inventory-adjustment.delete']);
        Permission::create(['name' => 'company.inventory-adjustment.print']);

        Permission::create(['name' => 'company.general-journal.view']);
        Permission::create(['name' => 'company.general-journal.create']);
        Permission::create(['name' => 'company.general-journal.update']);
        Permission::create(['name' => 'company.general-journal.delete']);
        Permission::create(['name' => 'company.general-journal.print']);

    }
}
