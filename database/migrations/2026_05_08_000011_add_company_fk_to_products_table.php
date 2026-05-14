<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Check if foreign key already exists before adding
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.TABLE_CONSTRAINTS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'products' 
            AND CONSTRAINT_NAME = 'products_company_id_foreign'
        ");

        if (empty($foreignKeys)) {
            Schema::table('products', function (Blueprint $table) {
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            });
        }
    }

    public function down()
    {
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.TABLE_CONSTRAINTS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'products' 
            AND CONSTRAINT_NAME = 'products_company_id_foreign'
        ");

        if (!empty($foreignKeys)) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropForeign(['company_id']);
            });
        }
    }
};