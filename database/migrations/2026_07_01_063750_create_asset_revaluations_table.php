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
        Schema::create('asset_revaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('asset_id');
            $table->decimal('book_value', 15, 2);
            $table->decimal('revalued_amount', 15, 2);
            $table->decimal('surplus', 15, 2);
            $table->date('date_of_revaluation')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('asset_id')->references('id')->on('create_assets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_revaluations');
    }
};
