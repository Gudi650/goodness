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
        Schema::create('create_liabilities', function (Blueprint $table) {
            $table->id();
            // Basic identifiers
            $table->string('code')->unique();
            $table->string('name'); // liability name (sub-category label)

            // Relationships
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained('liability_categories')->onDelete('set null');

            // Classification
            $table->string('type'); // e.g. Current Liability, Long-term Liability
            $table->enum('term', ['Short-term', 'Long-term']);

            // Values
            $table->decimal('original_amount', 20, 2)->nullable();
            $table->decimal('current_amount', 20, 2)->nullable();

            // Creditor details
            $table->string('creditor')->nullable();
            $table->decimal('interest_rate', 5, 2)->nullable(); // percentage
            $table->date('due_date')->nullable();

            // Status
            $table->enum('status', ['Active', 'Settled', 'Defaulted', 'Written Off'])->default('Active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('create_liabilities');
    }
};
