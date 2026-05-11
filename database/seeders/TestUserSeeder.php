<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create admin role
        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin'],
            ['description' => 'Administrator']
        );

        // Get or create default company
        $company = Company::firstOrCreate(
            ['name' => 'Goodness Group'],
            ['country' => 'Tanzania', 'status' => 'Active']
        );

        // Create admin test user
        User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'company_id' => $company->id,
                'email_verified_at' => now(),
            ]
        );

        // Create sales user
        $salesRole = Role::firstOrCreate(
            ['name' => 'Sales'],
            ['description' => 'Sales Representative']
        );

        User::firstOrCreate(
            ['email' => 'sales@test.com'],
            [
                'name' => 'Sales User',
                'password' => Hash::make('password'),
                'role_id' => $salesRole->id,
                'company_id' => $company->id,
                'email_verified_at' => now(),
            ]
        );
    }
}

