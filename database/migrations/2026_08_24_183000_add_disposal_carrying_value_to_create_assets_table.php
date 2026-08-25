<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('create_assets', function (Blueprint $table) {
            $table->decimal('disposal_carrying_value', 20, 2)->nullable()->after('disposal_proceeds');
        });
    }

    public function down(): void
    {
        Schema::table('create_assets', function (Blueprint $table) {
            $table->dropColumn('disposal_carrying_value');
        });
    }
};
