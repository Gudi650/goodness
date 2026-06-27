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
        Schema::create('shares_definitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id'); // link to companies table
            $table->bigInteger('authorized_shares')->nullable(); // max shares company can issue
            $table->bigInteger('issued_shares')->nullable();     // shares already issued
            $table->bigInteger('remaining_shares')->nullable();  // authorized - issued
            $table->decimal('share_value', 15, 2)->nullable();   // nominal value per share
            $table->text('notes')->nullable();

            $table->timestamps();

            // Foreign key to companies table
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shares_definitions');
    }
};
