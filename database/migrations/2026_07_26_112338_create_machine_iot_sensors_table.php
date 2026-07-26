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
        Schema::create('machine_iot_sensors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_id')->constrained('machines')->cascadeOnDelete();
            $table->string('sensor_code')->unique();
            $table->string('type'); // Temperature, Humidity, CO2...
            $table->decimal('last_reading', 8, 2)->nullable();
            $table->string('last_reading_unit')->nullable(); // °C, %, ppm
            $table->dateTime('last_sync_at')->nullable();
            // Online | Offline
            $table->string('status')->default('Offline');
            $table->timestamps();

            $table->index(['machine_id']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machine_iot_sensors');
    }
};
