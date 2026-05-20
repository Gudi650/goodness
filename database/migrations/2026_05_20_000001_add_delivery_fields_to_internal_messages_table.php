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
        Schema::table('internal_messages', function (Blueprint $table) {
            $table->boolean('delivered')->default(false)->after('is_read');
            $table->boolean('seen')->default(false)->after('delivered');
            $table->timestamp('seen_at')->nullable()->after('seen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internal_messages', function (Blueprint $table) {
            $table->dropColumn(['delivered', 'seen', 'seen_at']);
        });
    }
};
