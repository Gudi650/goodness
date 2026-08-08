<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE expenses MODIFY term ENUM(
            'short_term',
            'long_term',
            'current_liabilities',
            'non_current_liabilities'
        ) NOT NULL DEFAULT 'short_term'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE expenses MODIFY term ENUM(
            'short_term',
            'long_term'
        ) NOT NULL DEFAULT 'short_term'");
    }
};
