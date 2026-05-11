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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique()->index();
            $table->date('order_date');
            $table->date('expected_delivery_date')->nullable();
            
            // Company & Department
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            
            // Customer Information
            $table->unsignedBigInteger('customer_id');
            $table->longText('billing_address')->nullable();
            $table->longText('shipping_address')->nullable();
            
            // Order Classification
            $table->enum('order_type', ['Sale', 'Quotation', 'Proforma Invoice', 'Return'])->default('Sale');
            $table->enum('priority', ['Normal', 'High', 'Urgent'])->default('Normal');
            $table->enum('status', ['Draft', 'Confirmed', 'Processing', 'Ready for Delivery', 'Delivered', 'Cancelled', 'Returned'])->default('Draft');
            
            // Financial Summary
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->boolean('vat_enabled')->default(true);
            $table->decimal('vat_percent', 5, 2)->default(18);
            $table->decimal('vat_amount', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('other_charges', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->decimal('balance_due', 15, 2)->default(0);
            
            // Payment Information
            $table->enum('payment_status', ['Unpaid', 'Partially Paid', 'Fully Paid'])->default('Unpaid');
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('credit_terms')->nullable();
            $table->date('credit_due_date')->nullable();
            
            // Delivery Information
            $table->string('delivery_method')->nullable();
            $table->date('delivery_date')->nullable();
            $table->enum('delivery_status', ['Not Dispatched', 'In Transit', 'Delivered', 'Failed Delivery'])->default('Not Dispatched');
            $table->string('driver_name')->nullable();
            $table->string('vehicle_plate_number')->nullable();
            $table->longText('delivery_notes')->nullable();
            
            // Sales Rep & Authorization
            $table->unsignedBigInteger('sales_rep_id')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->decimal('commission_percent', 5, 2)->default(0);
            $table->decimal('commission_amount', 15, 2)->default(0);
            
            // Attachments & Notes
            $table->string('lpo_file_path')->nullable();
            $table->string('lpo_file_name')->nullable();
            $table->longText('internal_notes')->nullable();
            $table->longText('customer_notes')->nullable();
            $table->longText('terms_and_conditions')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('order_date');
            $table->index('customer_id');
            $table->index('company_id');
            $table->index('status');
            $table->index('payment_status');
            $table->index('sales_rep_id');
            
            // Foreign Keys
            $table->foreign('company_id')->references('id')->on('companies')->nullOnDelete();
            $table->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
            $table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete();
            $table->foreign('sales_rep_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
