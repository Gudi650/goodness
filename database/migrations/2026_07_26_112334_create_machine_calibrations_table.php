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
        Schema::create('machine_calibrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_id')->constrained('machines')->cascadeOnDelete();
            $table->string('component'); // Temperature sensor, Humidity sensor, Thermostat...
            $table->date('calibration_date');
            $table->date('next_due');
            $table->foreignId('performed_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('certificate_path')->nullable(); // stored file, if uploaded
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['machine_id']);
            $table->index(['next_due']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machine_calibrations');
    }
};
