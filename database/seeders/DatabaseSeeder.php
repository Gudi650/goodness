<?php

namespace Database\Seeders;

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
     * This method creates test users for development and testing.
     * In production, you would use a different approach to create users.
     */
    public function run(): void
    {
        /**
         * Create multiple test users for development
         * 
         * Test credentials:
         * - Email: admin@goodness.com, Password: password123
         * - Email: test@example.com, Password: password123
         */

        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@goodness.com',
            'password' => Hash::make('password123'), // Hashed password
        ]);

        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'), // Hashed password
        ]);
    }
}

