<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add role_id column to users table so each user has one role.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add role_id as a foreign key pointing to roles table.
            // cascade on delete ensures if a role is deleted, users revert to null.
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
        });
    }
};
