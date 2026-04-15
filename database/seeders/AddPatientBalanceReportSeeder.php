<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AddPatientBalanceReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'report.patient.balance']);
        Permission::create(['name' => 'report.financial.income-statement']);
        Permission::create(['name' => 'report.financial.balance-sheet']);
        Permission::create(['name' => 'report.financial.cash-flow']);
        Permission::create(['name' => 'report.accounting.general-ledger']);
        Permission::create(['name' => 'report.accounting.trial-balance']);
        Permission::create(['name' => 'report.accounting.transaction-details']);
        Permission::create(['name' => 'report.accounting.transaction-journal']);
    }
}
