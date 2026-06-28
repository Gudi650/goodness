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
        Schema::create('dividend_distributions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dividend_id');
            //$table->unsignedBigInteger('shareholder_id');
            $table->unsignedBigInteger('equity_id')->nullable();
            $table->string('shareholder_name');
            $table->bigInteger('shares');
            $table->decimal('ownership_percentage', 5, 2)->nullable();
            $table->decimal('amount', 15, 2);
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->foreign('dividend_id')->references('id')->on('dividends')->onDelete('cascade');
            //$table->foreign('shareholder_id')->references('id')->on('shareholders')->onDelete('cascade');
            $table->foreign('equity_id')->references('id')->on('equities')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dividend_distributions');
    }
};
