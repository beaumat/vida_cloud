<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Permission::create(['name' => 'customer.sales-order.view']);
        Permission::create(['name' => 'customer.sales-order.create']);
        Permission::create(['name' => 'customer.sales-order.update']);
        Permission::create(['name' => 'customer.sales-order.delete']);
        Permission::create(['name' => 'customer.sales-order.print']);

        Permission::create(['name' => 'customer.invoice.view']);
        Permission::create(['name' => 'customer.invoice.create']);
        Permission::create(['name' => 'customer.invoice.update']);
        Permission::create(['name' => 'customer.invoice.delete']);
        Permission::create(['name' => 'customer.invoice.print']);

        Permission::create(['name' => 'customer.received-payment.view']);
        Permission::create(['name' => 'customer.received-payment.create']);
        Permission::create(['name' => 'customer.received-payment.update']);
        Permission::create(['name' => 'customer.received-payment.delete']);
        Permission::create(['name' => 'customer.received-payment.print']);

        Permission::create(['name' => 'customer.credit-memo.view']);
        Permission::create(['name' => 'customer.credit-memo.create']);
        Permission::create(['name' => 'customer.credit-memo.update']);
        Permission::create(['name' => 'customer.credit-memo.delete']);
        Permission::create(['name' => 'customer.credit-memo.print']);
        Permission::create(['name' => 'customer.statement.modify']);

        Permission::create(['name' => 'customer.statement']);

    }
}
