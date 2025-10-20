<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $adminPermission = Permission::firstOrCreate(['name' => 'admin']);
        $userPermission  = Permission::firstOrCreate(['name' => 'user']);

        // Create roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo($adminPermission);

        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->givePermissionTo($userPermission);

        // Create normal user
        $user = User::updateOrCreate(
            ['email' => 'user@local.com'],
            [
                'name' => 'Normal User',
                'password' => Hash::make('password')
            ]
        );
        $user->assignRole('user');

        // Create admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@local.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password')
            ]
        );
        $admin->assignRole('admin');
    }
}
