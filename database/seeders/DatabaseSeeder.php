<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * 
     * This creates:
     * - 4 roles: Admin, Manager, Employee, Viewer
     * - 1 admin user for you to manage others
     * - 2 test employee users
     */
    public function run(): void
    {
        // Step 1: Create all available roles/positions.
        // These are the permission levels users can have.
        $adminRole = Role::create([
            'name' => 'Admin',
            'description' => 'Administrator with full access',
        ]);

        $managerRole = Role::create([
            'name' => 'Manager',
            'description' => 'Manager with limited administrative access',
        ]);

        $employeeRole = Role::create([
            'name' => 'Employee',
            'description' => 'Standard employee with basic access',
        ]);

        $viewerRole = Role::create([
            'name' => 'Viewer',
            'description' => 'Read-only access to reports and data',
        ]);

        // Step 2: Create an admin user.
        // This is the first admin account you can use to manage others.
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@goodness.com',
            'password' => Hash::make('password123'),
            'role_id' => $adminRole->id,
        ]);

        // Step 3: Create test employee users.
        // These users start with the 'Employee' role.
        User::create([
            'name' => 'John Employee',
            'email' => 'john@goodness.com',
            'password' => Hash::make('password123'),
            'role_id' => $employeeRole->id,
        ]);

        User::create([
            'name' => 'Jane Employee',
            'email' => 'jane@goodness.com',
            'password' => Hash::make('password123'),
            'role_id' => $employeeRole->id,
        ]);
    }
}


