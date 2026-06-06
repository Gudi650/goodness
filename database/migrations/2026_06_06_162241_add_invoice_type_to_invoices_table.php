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
        Schema::table('invoices', function (Blueprint $table) {

            $table->enum('invoice_type', ['income', 'expense'])
                  ->after('invoice_number')
                  ->default('income');

            //add the bank id as well 
            $table->foreignId('bank_id')->nullable()->after('department_id')->constrained('virtual_accounts')->nullOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('invoice_type');
            $table->dropForeign(['bank_id']);
            $table->dropColumn('bank_id');
        });
    }
};
