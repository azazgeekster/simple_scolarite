<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions for admin guard
        $permissions = [
            // Role Management
            'view role',
            'create role',
            'update role',
            'delete role',

            // Permission Management
            'view permission',
            'create permission',
            'update permission',
            'delete permission',

            // User/Admin Management
            'view user',
            'create user',
            'update user',
            'delete user',

            // Student Management
            'view student',
            'create student',
            'update student',
            'delete student',

            // Enrollment Management
            'view enrollment',
            'create enrollment',
            'update enrollment',
            'delete enrollment',

            // Module Management
            'view module',
            'create module',
            'update module',
            'delete module',

            // Grade Management
            'view grade',
            'create grade',
            'update grade',
            'delete grade',
            'publish grade',

            // Exam Management
            'view exam',
            'create exam',
            'update exam',
            'delete exam',
            'publish exam',
            'import exam',

            // Document Management
            'view document',
            'create document',
            'update document',
            'delete document',
            'approve document',

            // Reclamation Management
            'view reclamation',
            'process reclamation',

            // Department Management
            'view department',
            'create department',
            'update department',
            'delete department',

            // Professor Management
            'view professor',
            'create professor',
            'update professor',
            'delete professor',

            // Filiere Management
            'view filiere',
            'create filiere',
            'update filiere',
            'delete filiere',

            // Academic Year Management
            'view academic_year',
            'create academic_year',
            'update academic_year',
            'delete academic_year',

            // Report Management
            'view report',
            'generate report',

            // System Settings
            'manage settings',
            'view logs',
        ];

        // Create permissions for admin guard
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'admin']
            );
        }

        // Create Super Admin role
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'Super Admin', 'guard_name' => 'admin']
        );

        // Create Admin role (limited permissions)
        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin', 'guard_name' => 'admin']
        );

        // Create Staff role (even more limited)
        $staffRole = Role::firstOrCreate(
            ['name' => 'Staff', 'guard_name' => 'admin']
        );

        // Assign ALL permissions to Super Admin
        $allPermissions = Permission::where('guard_name', 'admin')->pluck('name')->toArray();
        $superAdminRole->syncPermissions($allPermissions);

        // Assign limited permissions to Admin role
        $adminPermissions = [
            'view role', 'view permission', 'view user',
            'view student', 'create student', 'update student',
            'view enrollment', 'create enrollment', 'update enrollment',
            'view module', 'view grade', 'create grade', 'update grade',
            'view exam', 'view document', 'approve document',
            'view reclamation', 'process reclamation',
            'view department', 'view professor', 'view filiere',
            'view academic_year', 'view report',
        ];
        $adminRole->syncPermissions($adminPermissions);

        // Assign even more limited permissions to Staff
        $staffPermissions = [
            'view student', 'view enrollment', 'view module',
            'view grade', 'view exam', 'view document',
            'view reclamation', 'view department', 'view professor',
        ];
        $staffRole->syncPermissions($staffPermissions);

        // Assign Super Admin role to admin@university.ma
        $superAdmin = Admin::where('email', 'admin@university.ma')->first();

        if ($superAdmin) {
            // Remove any existing roles first
            $superAdmin->syncRoles([]);

            // Assign Super Admin role
            $superAdmin->assignRole($superAdminRole);

            $this->command->info('✓ Super Admin role assigned to admin@university.ma');
        } else {
            $this->command->warn('⚠ Admin with email admin@university.ma not found!');
        }

        // Assign Admin role to the second admin (malaoui@university.ma)
        $regularAdmin = Admin::where('email', 'malaoui@university.ma')->first();

        if ($regularAdmin) {
            $regularAdmin->syncRoles([]);
            $regularAdmin->assignRole($adminRole);
            $this->command->info('✓ Admin role assigned to malaoui@university.ma');
        }

        $this->command->info('✓ Created ' . count($allPermissions) . ' permissions');
        $this->command->info('✓ Created 3 roles: Super Admin, Admin, Staff');
    }
}
