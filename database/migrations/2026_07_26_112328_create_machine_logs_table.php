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
        Schema::create('machine_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_id')->constrained('machines')->cascadeOnDelete();
            $table->date('log_date');
            $table->string('shift'); // Morning, Afternoon, Night
            $table->decimal('temperature', 5, 2);
            $table->decimal('humidity', 5, 2);
            $table->unsignedInteger('turning_count')->default(0);
            $table->foreignId('recorded_by_id')->nullable()->constrained('users')->nullOnDelete();
            // Normal | out-of-range flag text, e.g. "Temp High"
            $table->string('flag')->default('Normal');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['machine_id', 'log_date']);
            $table->index(['flag']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machine_logs');
    }
};
