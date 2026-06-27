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
        Schema::create('equity_distributions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id'); // link to companies table
            $table->string('shareholder');
            $table->string('equity_type')->nullable(); // Common, Preferred, Capital Contribution
            $table->bigInteger('shares')->default(0);
            $table->decimal('ownership_percentage', 5, 2)->nullable(); // e.g. 25.00
            $table->decimal('value_held', 15, 2)->nullable(); // monetary value in TZS
            $table->text('notes')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equity_distributions');
    }
};
