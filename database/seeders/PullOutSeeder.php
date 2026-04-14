<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PullOutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'company.pull-out.view']);
        Permission::create(['name' => 'company.pull-out.create']);
        Permission::create(['name' => 'company.pull-out.update']);
        Permission::create(['name' => 'company.pull-out.delete']);
        Permission::create(['name' => 'company.pull-out.print']);
    }
}
