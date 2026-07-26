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
        Schema::create('machine_maintenance_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_id')->constrained('machines')->cascadeOnDelete();
            $table->string('maintenance_type'); // e.g. Belt inspection, Fan service, Cleaning
            $table->date('scheduled_date');
            $table->string('frequency'); // One-off, Weekly, Monthly, Quarterly, Annual
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
            // Upcoming | Overdue | Completed
            $table->string('status')->default('Upcoming');
            $table->date('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['machine_id', 'status']);
            $table->index(['scheduled_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machine_maintenance_schedules');
    }
};
