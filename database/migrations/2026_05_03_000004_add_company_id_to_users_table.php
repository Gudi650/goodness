<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration adds a company_id field to the users table so each user
     * is linked to a specific company. The company_id is nullable (for system users
     * or before a company is assigned) and has a foreign key constraint that sets
     * the value to NULL if the company is deleted.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add company_id column after role_id
            // nullable() means a user doesn't have to belong to a company yet
            // foreignId() creates an unsigned bigint that links to companies table
            $table->foreignId('company_id')
                ->nullable()
                ->after('role_id')
                ->constrained('companies')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * This removes the company_id column if we ever need to rollback.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeignIdFor('companies');
        });
    }
};
