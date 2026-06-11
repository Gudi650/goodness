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
            //Add the term column (short_term / long_term)
            $table->enum('term', ['short_term', 'long_term'])
                  ->default('short_term')
                  ->after('sub_category');

            // Index the term column for faster filtering
            $table->index('term');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('term');
            $table->dropIndex(['term']);
        });
    }
};
