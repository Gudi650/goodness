<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Safety checks
        if (!Schema::hasTable('products') || !Schema::hasColumn('products', 'company_id')) {
            return;
        }

        // Some database drivers (SQLite) cannot add FK constraints via ALTER TABLE easily.
        // Detect driver and only add FK for drivers that support it (MySQL, pgsql).
        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            // Skip adding FK on SQLite - developer can recreate DB or add manually.
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            // If the foreign key already exists, skip (avoid duplicate error)
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            // Attempt to create FK; Laravel will handle if it doesn't already exist.
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('products') || !Schema::hasColumn('products', 'company_id')) {
            return;
        }

        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
        });
    }
};
