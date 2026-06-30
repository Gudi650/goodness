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
        Schema::create('share_premuims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('shares_issued');
            $table->decimal('nominal_value', 15, 2);
            $table->decimal('issue_price', 15, 2);
            $table->decimal('premium_per_share', 15, 2);
            $table->decimal('total_premium', 20, 2);
            $table->text('notes')->nullable();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('share_premuims');
    }
};
