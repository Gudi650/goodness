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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_number')->unique();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('submitted');
            $table->date('expense_date');
            $table->string('category');
            $table->string('sub_category')->nullable();
            $table->string('payment_method');
            $table->string('reference_number')->nullable();
            $table->decimal('amount', 15, 2);
            $table->boolean('vat_included')->default(false);
            $table->decimal('vat_rate', 8, 2)->default(0);
            $table->decimal('vat_amount', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2)->default(0);
            $table->string('attachment_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'department_id']);
            $table->index(['status', 'expense_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
