<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('create_assets', function (Blueprint $table) {
            $table->date('disposal_date')->nullable()->after('status');
            $table->decimal('disposal_proceeds', 20, 2)->nullable()->after('disposal_date');
            $table->unsignedBigInteger('disposal_bank_id')->nullable()->after('disposal_proceeds');
            $table->text('disposal_notes')->nullable()->after('disposal_bank_id');
        });
    }

    public function down(): void
    {
        Schema::table('create_assets', function (Blueprint $table) {
            $table->dropColumn(['disposal_date', 'disposal_proceeds', 'disposal_bank_id', 'disposal_notes']);
        });
    }
};
