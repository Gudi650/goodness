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
        Schema::create('create_assets', function (Blueprint $table) {
            $table->id();
            // Basic identifiers
            $table->string('code')->unique();
            $table->string('name'); // actual item name (sub-category label)

            // Relationships
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained('assets_categories')->onDelete('set null');

            // Accounting classification
            $table->string('type'); // e.g. Fixed Asset, Current Asset, Intangible Asset, Investment
            $table->enum('term', ['Short-term', 'Long-term']);

            // Values
            $table->decimal('original_value', 20, 2)->nullable();
            $table->decimal('current_value', 20, 2)->nullable();

            // Other details
            $table->decimal('depreciation_value', 10, 2)->nullable(); // e.g. 5.00
            $table->date('acquired')->nullable();
            $table->enum('status', ['Active', 'Disposed', 'Sold', 'Written Off'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('create_assets');
    }
};
