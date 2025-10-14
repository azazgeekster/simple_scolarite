<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Student;
use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentRole = Role::create(['name' => 'Student', 'guard_name' => 'student']);
        $adminRole = Role::create(['name' => 'Admin', 'guard_name' => 'admin']);

        // Create Permissions with guard_name
        Permission::create(['name' => 'view role', 'guard_name' => 'admin']);
        Permission::create(['name' => 'create role', 'guard_name' => 'admin']);
        Permission::create(['name' => 'update role', 'guard_name' => 'admin']);
        Permission::create(['name' => 'delete role', 'guard_name' => 'admin']);

        Permission::create(['name' => 'view permission', 'guard_name' => 'admin']);
        Permission::create(['name' => 'create permission', 'guard_name' => 'admin']);
        Permission::create(['name' => 'update permission', 'guard_name' => 'admin']);
        Permission::create(['name' => 'delete permission', 'guard_name' => 'admin']);

        Permission::create(['name' => 'view user', 'guard_name' => 'admin']);
        Permission::create(['name' => 'create user', 'guard_name' => 'admin']);
        Permission::create(['name' => 'update user', 'guard_name' => 'admin']);
        Permission::create(['name' => 'delete user', 'guard_name' => 'admin']);

        // Create student-specific permissions
        Permission::create(['name' => 'view request', 'guard_name' => 'student']);
        Permission::create(['name' => 'view timetable', 'guard_name' => 'student']);

        // Assign all permissions to Admin role
        $allPermissionNames = Permission::where('guard_name', 'admin')->pluck('name')->toArray();
        $adminRole->givePermissionTo($allPermissionNames);

        // Assign specific permissions to Student role
        $studentRole->givePermissionTo(['view request', 'view timetable']);

        // Create and assign Admin User
        $adminUser = Admin::firstOrCreate([
            'email' => 'admin@gmail.com'
        ], [
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        $adminUser->assignRole($adminRole);

        // Create and assign Student User
        $studentUser = Student::firstOrCreate([
            'email' => 'student@gmail.com',
        ], [
            'prenom' => 'Student',
            'nom' => 'Student',
            'email' => 'student@gmail.com',
            'password' => Hash::make('12345678'),
            'cne' => '123456', // Add CNE field
            'apogee' => '123456', // Add Apogee field
            'cin' => 'JC22121', // Add Apogee field
        ]);
        $studentUser->assignRole($studentRole);
    }
}
