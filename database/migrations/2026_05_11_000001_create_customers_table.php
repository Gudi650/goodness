<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code')->unique();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_type', 50);
            $table->string('customer_name');
            $table->string('business_trading_name')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('industry_sector')->nullable();
            $table->string('status')->default('Active');
            $table->string('contact_person_name')->nullable();
            $table->string('phone_number', 30);
            $table->string('alternative_phone_number', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('whatsapp_number', 30)->nullable();
            $table->string('website')->nullable();
            $table->string('country')->default('Tanzania');
            $table->string('region')->nullable();
            $table->string('district')->nullable();
            $table->string('street_address')->nullable();
            $table->string('po_box')->nullable();
            $table->foreignId('assigned_sales_rep_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_source')->nullable();
            $table->string('price_category')->nullable();
            $table->decimal('credit_limit', 15, 2)->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('preferred_payment_method')->nullable();
            $table->string('currency_preference', 10)->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('mobile_money_number', 30)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('national_id_number')->nullable();
            $table->string('tags')->nullable();
            $table->unsignedTinyInteger('customer_rating')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};