<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->string('loan_type')->default('external_borrow')->after('company_id');
            $table->foreignId('counterparty_company_id')->nullable()->after('loan_type')->constrained('companies')->nullOnDelete();
            $table->foreignId('employee_id')->nullable()->after('counterparty_company_id')->constrained('users')->nullOnDelete();
            $table->foreignId('source_bank_id')->nullable()->after('bank_id')->constrained('virtual_accounts')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('source_bank_id');
            $table->dropConstrainedForeignId('employee_id');
            $table->dropConstrainedForeignId('counterparty_company_id');
            $table->dropColumn('loan_type');
        });
    }
};
