<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            try {
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            } catch (\Exception $e) {
                // Foreign key may already exist, skip silently
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            try {
                $table->dropForeign(['company_id']);
            } catch (\Exception $e) {
                // Foreign key may not exist, skip silently
            }
        });
    }
};