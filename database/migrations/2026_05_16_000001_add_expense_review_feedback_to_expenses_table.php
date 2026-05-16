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
            $table->unsignedTinyInteger('review_rating')->nullable()->after('approved_at');
            $table->text('review_feedback')->nullable()->after('review_rating');
            $table->timestamp('reviewed_at')->nullable()->after('review_feedback');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('reviewed_at');
            $table->dropColumn('review_feedback');
            $table->dropColumn('review_rating');
        });
    }
};