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
        Schema::table('expenses', function (Blueprint $table) {
            // Add a foreign key to finance_items
            $table->unsignedBigInteger('sub_category_id')->nullable()->after('sub_category');
            
            $table->foreign('sub_category_id')
                  ->references('id')
                  ->on('finance_items')
                  ->onDelete('set null');

            // Add description column
            $table->text('description')->nullable()->after('sub_category_id');

            // Index for faster lookups
            $table->index('sub_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            // Drop foreign key and column
            $table->dropForeign(['sub_category_id']);
            $table->dropIndex(['sub_category_id']);
            $table->dropColumn('sub_category_id');

            // Drop description column
            $table->dropColumn('description');

        });
    }
};
