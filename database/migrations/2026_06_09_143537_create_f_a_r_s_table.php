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
        Schema::create('f_a_r_s', function (Blueprint $table) {
            $table->id();
            //reference to the create_assets table
            $table->unsignedBigInteger('create_assets_id');
            $table->foreign('create_assets_id')->references('id')->on('create_assets')->onDelete('cascade');

            //depreciation value of the fixed asset
            $table->decimal('depreciation_value', 15, 2);

            //current value of the fixed asset
            $table->decimal('current_value', 15, 2);

            //accumulated depreciation of the fixed asset
            $table->decimal('accumulated_depreciation', 15, 2);

            //last depreciation date of the fixed asset
            $table->date('last_depreciation_date');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('f_a_r_s');
    }
};
