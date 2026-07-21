<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            //
            Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('company_id')
                  ->nullable()
                  ->after('id')
                  ->constrained()
                  ->cascadeOnDelete();
            });

            // Drop the old string column now that company_id exists
            Schema::table('payments', function (Blueprint $table) {
                $table->dropColumn('company');
            });

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            //
            Schema::table('payments', function (Blueprint $table) {
                $table->string('company')->after('id');
            });

            Schema::table('payments', function (Blueprint $table) {
                $table->dropConstrainedForeignId('company_id');
            });
        });
    }
};
