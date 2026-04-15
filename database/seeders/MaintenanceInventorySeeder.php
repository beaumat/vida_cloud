<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class MaintenanceInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'items.view']);
        Permission::create(['name' => 'items.create']);
        Permission::create(['name' => 'items.edit']);
        Permission::create(['name' => 'items.delete']);

        Permission::create(['name' => 'item-group.view']);
        Permission::create(['name' => 'item-group.create']);
        Permission::create(['name' => 'item-group.edit']);
        Permission::create(['name' => 'item-group.delete']);

        Permission::create(['name' => 'item-class.view']);
        Permission::create(['name' => 'item-class.create']);
        Permission::create(['name' => 'item-class.edit']);
        Permission::create(['name' => 'item-class.delete']);

        Permission::create(['name' => 'item-sub-class.view']);
        Permission::create(['name' => 'item-sub-class.create']);
        Permission::create(['name' => 'item-sub-class.edit']);
        Permission::create(['name' => 'item-sub-class.delete']);         

        Permission::create(['name' => 'price-level.view']);
        Permission::create(['name' => 'price-level.create']);
        Permission::create(['name' => 'price-level.edit']);
        Permission::create(['name' => 'price-level..delete']); 

        Permission::create(['name' => 'manufacturer.view']);
        Permission::create(['name' => 'manufacturer.create']);
        Permission::create(['name' => 'manufacturer.edit']);
        Permission::create(['name' => 'manufacturer.delete']);

        Permission::create(['name' => 'ship-via.view']);
        Permission::create(['name' => 'ship-via.create']);
        Permission::create(['name' => 'ship-via.edit']);
        Permission::create(['name' => 'ship-via.delete']);

        Permission::create(['name' => 'stock-bin.view']);
        Permission::create(['name' => 'stock-bin.create']);
        Permission::create(['name' => 'stock-bin.edit']);
        Permission::create(['name' => 'stock-bin.delete']);

        Permission::create(['name' => 'unit-of-measure.view']);
        Permission::create(['name' => 'unit-of-measure.create']);
        Permission::create(['name' => 'unit-of-measure.edit']);
        Permission::create(['name' => 'unit-of-measure.delete']);

        Permission::create(['name' => 'inventory-adjustment-type.view']);
        Permission::create(['name' => 'inventory-adjustment-type.create']);
        Permission::create(['name' => 'inventory-adjustment-type.edit']);
        Permission::create(['name' => 'inventory-adjustment-type.delete']);

    }
}
