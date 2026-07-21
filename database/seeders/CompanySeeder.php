<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('companies')->updateOrInsert(
            ['name' => 'Goodness Group',
             'country' => 'Tanzania',
             'status' => 'Active',
            ], // Matching condition
            ['updated_at' => now(), 'created_at' => now()]
        );
    }
}
