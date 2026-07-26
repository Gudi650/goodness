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
        Schema::create('machine_alarms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_id')->constrained('machines')->cascadeOnDelete();
            $table->string('alarm_type'); // Temp High, Temp Low, Humidity, Power Failure, Turning Fault...
            // Critical | Warning | Info
            $table->string('severity')->default('Warning');
            $table->dateTime('triggered_at');
            // Open | Resolved
            $table->string('status')->default('Open');
            $table->foreignId('resolved_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('resolved_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['machine_id', 'status']);
            $table->index(['severity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machine_alarms');
    }
};
