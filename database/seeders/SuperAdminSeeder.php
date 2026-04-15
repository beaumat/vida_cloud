<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $result = User::query()->where('name', 'superadmin')->get();

        if ($result->isEmpty()) {

            $user =  User::create([
                'name' => 'superadmin',
                'email' => 'super_admin@superadmin.com',
                'email_verified_at' => now(),
                'password' => Hash::make('311ahk6wfr2ty'),
                'remember_token' => Str::random(10),
                'contact_id' => null,
                'inactive' => false,
            ]);

            $user->assignRole('SuperAdmin');

            $role = Role::where('name', 'SuperAdmin')->first();
            // Get all permissions
            $permissions = Permission::all();
            // Assign all permissions to the role
            $role->syncPermissions($permissions);
        }
    }
}
