<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class MaintenanceFinancialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'chart-of-account.view']);
        Permission::create(['name' => 'chart-of-account.create']);
        Permission::create(['name' => 'chart-of-account.edit']);
        Permission::create(['name' => 'chart-of-account.delete']);

        Permission::create(['name' => 'payment-method.view']);
        Permission::create(['name' => 'payment-method.create']);
        Permission::create(['name' => 'payment-method.edit']);
        Permission::create(['name' => 'payment-method.delete']);

        Permission::create(['name' => 'payment-term.view']);
        Permission::create(['name' => 'payment-term.create']);
        Permission::create(['name' => 'payment-term.edit']);
        Permission::create(['name' => 'payment-term.delete']);

        Permission::create(['name' => 'tax-list.view']);
        Permission::create(['name' => 'tax-list.create']);
        Permission::create(['name' => 'tax-list.edit']);
        Permission::create(['name' => 'tax-list.delete']);

    }
}
