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
        //These are the permission levels users can have.
        $adminRole = Role::Create([
            'name' => 'Admin',
            'description' => 'Administrator with full access',
        ]);

        $managerRole = Role::Create([
            'name' => 'Manager',
            'description' => 'Manager with limited administrative access',
        ]);

        $employeeRole = Role::Create([
            'name' => 'Employee',
            'description' => 'Standard employee with basic access',
        ]);

        $viewerRole = Role::Create([
            'name' => 'Viewer',
            'description' => 'Read-only access to reports and data',
        ]);

        $this->call([
            FinanceItemsSeeder::class,
        ]);
        

        $this->call([
            FinanceItemsSeeder::class,
            IncomeItemsSeeder::class,
        ]);

        $this->call([
            AssetCategorySeeder::class,
        ]);

        $this->call([
            AssetCategorySeeder::class,
            LiabilityCategorySeeder::class,
        ]);

    }

}


