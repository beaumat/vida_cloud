<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'vendor.purchase-order.view']);
        Permission::create(['name' => 'vendor.purchase-order.create']);
        Permission::create(['name' => 'vendor.purchase-order.update']);
        Permission::create(['name' => 'vendor.purchase-order.delete']);
        Permission::create(['name' => 'vendor.purchase-order.print']);

        Permission::create(['name' => 'vendor.bill.view']);
        Permission::create(['name' => 'vendor.bill.create']);
        Permission::create(['name' => 'vendor.bill.update']);
        Permission::create(['name' => 'vendor.bill.delete']);
        Permission::create(['name' => 'vendor.bill.print']);

        Permission::create(['name' => 'vendor.bill-credit.view']);
        Permission::create(['name' => 'vendor.bill-credit.create']);
        Permission::create(['name' => 'vendor.bill-credit.update']);
        Permission::create(['name' => 'vendor.bill-credit.delete']);
        Permission::create(['name' => 'vendor.bill-credit.print']);

        Permission::create(['name' => 'vendor.bill-payment.view']);
        Permission::create(['name' => 'vendor.bill-payment.create']);
        Permission::create(['name' => 'vendor.bill-payment.update']);
        Permission::create(['name' => 'vendor.bill-payment.delete']);
        Permission::create(['name' => 'vendor.bill-payment.print']);
    }
    
}
