<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            // Nullable foreign key referencing virtual_accounts table
            $table->foreignId('bank_id')
                ->nullable()
                ->after('company_id')
                ->constrained('virtual_accounts')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign(['bank_id']);
            $table->dropColumn('bank_id');
        });
    }
};