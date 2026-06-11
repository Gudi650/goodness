<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (! $this->hasCompanyForeignKey()) {
            Schema::table('products', function (Blueprint $table) {
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            });
        }
    }

    public function down()
    {
        if ($this->hasCompanyForeignKey()) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropForeign(['company_id']);
            });
        }
    }

    private function hasCompanyForeignKey(): bool
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            $foreignKeys = DB::select("PRAGMA foreign_key_list('products')");

            foreach ($foreignKeys as $foreignKey) {
                if (($foreignKey->from ?? null) === 'company_id' && ($foreignKey->table ?? null) === 'companies') {
                    return true;
                }
            }

            return false;
        }

        if ($driver === 'mysql' || $driver === 'mariadb') {
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.TABLE_CONSTRAINTS
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'products'
                AND CONSTRAINT_NAME = 'products_company_id_foreign'
            ");

            return ! empty($foreignKeys);
        }

        return false;
    }
};