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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_reference')->unique();
            $table->date('payment_date');
            $table->string('company');
            $table->enum('payment_direction', ['Incoming', 'Outgoing']);
            $table->string('party_name')->nullable();
            $table->enum('payment_method', ['Cash', 'Bank Transfer', 'Mobile Money', 'Cheque', 'Direct Debit'])->nullable();
            $table->string('reference_number')->nullable();
            $table->enum('payment_category', ['Invoice Settlement', 'Expense Reimbursement', 'Loan Repayment', 'Advance Payment', 'Refund', 'Other'])->nullable();
            $table->string('linked_to')->nullable();
            $table->decimal('amount', 15, 2);
            $table->enum('currency', ['TZS', 'USD', 'EUR', 'KES']);
            $table->decimal('exchange_rate', 15, 4)->default(1.00);
            $table->enum('payment_status', ['Completed', 'Pending', 'Failed', 'Reversed'])->default('Completed');
            $table->text('notes')->nullable();
            $table->string('proof_of_payment_path')->nullable();
            $table->string('original_proof_filename')->nullable();
            $table->enum('submit_mode', ['draft', 'submit'])->default('submit');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
