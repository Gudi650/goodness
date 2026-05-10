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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_id')->unique();
            $table->string('supplier_name');
            $table->string('supplier_type', 50);
            $table->string('registration_number')->nullable();
            $table->string('status', 50)->default('Active');
            $table->string('contact_person_name');
            $table->string('phone_number', 30);
            $table->string('alternative_phone_number', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('country', 100)->default('Tanzania');
            $table->string('region', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('street_address')->nullable();
            $table->string('po_box', 100)->nullable();
            $table->text('categories_supplied')->nullable();
            $table->text('products_supplied')->nullable();
            $table->string('lead_time', 50)->nullable();
            $table->decimal('minimum_order_value', 15, 2)->nullable();
            $table->string('payment_terms', 50)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number', 100)->nullable();
            $table->string('branch_name', 100)->nullable();
            $table->string('mobile_money_number', 30)->nullable();
            $table->string('preferred_payment_method', 50)->nullable();
            $table->unsignedTinyInteger('rating')->nullable();
            $table->date('contract_start_date')->nullable();
            $table->date('contract_end_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('business_registration_certificate_path')->nullable();
            $table->string('tin_certificate_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
