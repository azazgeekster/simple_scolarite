<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        Permission::create(['name' => 'view role']);
        Permission::create(['name' => 'create role']);
        Permission::create(['name' => 'update role']);
        Permission::create(['name' => 'delete role']);

        Permission::create(['name' => 'view permission']);
        Permission::create(['name' => 'create permission']);
        Permission::create(['name' => 'update permission']);
        Permission::create(['name' => 'delete permission']);

        Permission::create(['name' => 'view user']);
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'update user']);
        Permission::create(['name' => 'delete user']);


        $studentRole = Role::create(['name' => 'Student', 'guard_name' => 'student']);
        $adminRole = Role::create(['name' => 'Admin', 'guard_name' => 'admin']);


        // Create Roles
        // $adminRole = Role::create(['name' => 'admin']);
        // $staffRole = Role::create(['name' => 'staff']);
        // $UserRole = Role::create(['name' => 'user']);

        // // Lets give all Permission to super-admin Role.
        $allPermissionNames = Permission::pluck('name')->toArray();

        $adminRole->givePermissionTo($allPermissionNames);

        // Let's give few Permissions to admin Role.
        $studentRole->givePermissionTo(['view request', 'view timetable']);
        

        // Let's Create User and assign Role to it.

        // $superAdminUser = User::firstOrCreate([
        //             'email' => 'superadmin@gmail.com',
        //         ], [
        //             'name' => 'Super Admin',
        //             'email' => 'superadmin@gmail.com',
        //             'password' => Hash::make ('12345678'),
        //         ]);

        // $superAdminUser->assignRole($superAdminRole);


        $adminUser = User::firstOrCreate([
                            'email' => 'admin@gmail.com'
                        ], [
                            'name' => 'Admin',
                            'email' => 'admin@gmail.com',
                            'password' => Hash::make ('12345678'),
                        ]);

        $adminUser->assignRole($adminRole);


        $studentUser = User::firstOrCreate([
                            'email' => 'student@gmail.com',
                        ], [
                            'name' => 'Student',
                            'email' => 'student@gmail.com',
                            'password' => Hash::make('12345678'),
                        ]);

        $studentUser->assignRole($studentRole);
    }
}