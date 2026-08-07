<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_repayment_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('loans')->cascadeOnDelete();
            $table->unsignedInteger('installment_number');
            $table->date('due_date');
            $table->decimal('principal_portion', 15, 2);
            $table->decimal('interest_portion', 15, 2);
            $table->decimal('total_installment', 15, 2);
            // Pending | Paid | Overdue
            $table->string('status')->default('Pending');
            $table->timestamps();

            $table->unique(['loan_id', 'installment_number']);
            $table->index(['due_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_repayment_schedules');
    }
};