<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('lender');
            $table->decimal('principal', 15, 2);
            $table->decimal('interest_rate', 5, 2); // percentage, e.g. 12.50
            $table->string('interest_type'); // Flat | Reducing Balance
            $table->unsignedInteger('term_months');
            $table->date('disbursement_date')->nullable();
            $table->date('start_date');
            $table->date('maturity_date');
            $table->decimal('outstanding_balance', 15, 2)->default(0);
            $table->decimal('total_interest_payable', 15, 2)->default(0);
            $table->decimal('total_repayable', 15, 2)->default(0);
            // Active | Closed | Overdue | Defaulted
            $table->string('status')->default('Active');
            $table->text('purpose')->nullable();
            $table->text('collateral')->nullable();
            $table->string('guarantor')->nullable();
            $table->foreignId('approved_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};