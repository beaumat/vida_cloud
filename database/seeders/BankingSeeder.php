<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
class BankingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Permission::create(['name' => 'banking.deposit.view']);
        Permission::create(['name' => 'banking.deposit.create']);
        Permission::create(['name' => 'banking.deposit.update']);
        Permission::create(['name' => 'banking.deposit.delete']);
        Permission::create(['name' => 'banking.deposit.print']);
        
        Permission::create(['name' => 'banking.fund-transfer.view']);
        Permission::create(['name' => 'banking.fund-transfer.create']);
        Permission::create(['name' => 'banking.fund-transfer.update']);
        Permission::create(['name' => 'banking.fund-transfer.delete']);
        Permission::create(['name' => 'banking.fund-transfer.print']);

        Permission::create(['name' => 'banking.make-cheque.view']);
        Permission::create(['name' => 'banking.make-cheque.create']);
        Permission::create(['name' => 'banking.make-cheque.update']);
        Permission::create(['name' => 'banking.make-cheque.delete']);
        Permission::create(['name' => 'banking.make-cheque.print']);
        
    }
}
