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
        Schema::create('internal_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id'); // Foreign key to users table
            $table->unsignedBigInteger('receiver_id'); // Foreign key to users table
            $table->text('message')->nullable(); // Message content, can be null if only attachment is sent

            $table->unsignedBigInteger('company_id')->nullable(); // Optional company association
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');

            //conversation attachment if any 
            $table->string('attachment_path')->nullable(); //path of the attchament file if any
            $table->string('attachment_name')->nullable(); //original file name of the attachment


            $table->boolean('is_read')->default(false); //to track if the message has been read by the receiver

            //deleted by which user 
            $table->unsignedBigInteger('deleted_by')->nullable();

            //when was the message deleted
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internal_messages');
    }
};
