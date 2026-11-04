<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {

        // Create Permissions
        $permissionNames = [
            'user.view', 'user.create', 'user.edit', 'user.delete',
            'role.manage',
            'permission.manage',
            'case.manage', 'client.manage', 'invoice.manage'
        ];

        $permissionModels = [];
        foreach ($permissionNames as $name) {
            $permissionModels[$name] = Permission::firstOrCreate(
                ['name' => $name],
                ['guard_name' => 'web']
            );
        }

        // Create Roles and Assign Permissions
        
        // Super Admin Role (All permissions)
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'Super Admin'],
            ['guard_name' => 'web']
        );
        $superAdminRole->permissions()->syncWithoutDetaching(array_values($permissionModels));


        // Lawyer Role (specific permissions)
        $lawyerRole = Role::firstOrCreate(
            ['name' => 'Lawyer'],
            ['guard_name' => 'web']
        );
        $lawyerRole->permissions()->syncWithoutDetaching([
            $permissionModels['case.manage']->id,
            $permissionModels['client.manage']->id,
            $permissionModels['user.view']->id,
        ]);

        // Staff Role (basic permissions)
        $staffRole = Role::firstOrCreate(
            ['name' => 'Staff'],
            ['guard_name' => 'web']
        );
        $staffRole->permissions()->syncWithoutDetaching([
            $permissionModels['user.view']->id,
            $permissionModels['case.manage']->id,
        ]);




        // 1. Super Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'phone' => '9800000001',
                'status' => 1,
                'role' => 'super_admin',
            ]
        );
        $admin->roles()->syncWithoutDetaching([$superAdminRole->id]);

        // 2. Lawyer User
        $lawyer = User::firstOrCreate(
            ['email' => 'lawyer@example.com'],
            [
                'name' => 'John Lawyer',
                'password' => Hash::make('password123'),
                'phone' => '9800000002',
                'status' => 1,
                'role' => 'lawyer',
            ]
        );
        $lawyer->roles()->syncWithoutDetaching([$lawyerRole->id]);

        // 3. Staff User
        $staff = User::firstOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Jane Staff',
                'password' => Hash::make('password123'),
                'phone' => '9800000003',
                'status' => 1,
                'role' => 'staff',
            ]
        );
        $staff->roles()->syncWithoutDetaching([$staffRole->id]);
    }
}