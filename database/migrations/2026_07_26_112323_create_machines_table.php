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
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('type'); // Setter, Hatcher, Combo
            $table->string('location')->nullable();
            $table->unsignedInteger('capacity')->default(0); // eggs
            $table->string('serial_number')->nullable();
            $table->string('manufacturer')->nullable();
            $table->date('installed_date')->nullable();
            // Active | Under Maintenance | Inactive
            $table->string('status')->default('Active');
            $table->boolean('iot_enabled')->default(false);
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status']);
            $table->index(['type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machines');
    }
};
