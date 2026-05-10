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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique()->index();
            $table->date('po_date');
            $table->date('expected_delivery_date');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->enum('priority_level', ['Low', 'Normal', 'High', 'Urgent'])->default('Normal');
            $table->enum('status', [
                'Draft',
                'Pending Approval',
                'Approved',
                'Ordered',
                'Partially Received',
                'Fully Received',
                'Cancelled'
            ])->default('Draft');
            
            // Supplier Information
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->longText('delivery_address')->nullable();
            $table->string('delivery_method')->nullable();
            
            // Financial Summary
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('vat_percent', 5, 2)->default(18);
            $table->decimal('vat_amount', 10, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            
            // Payment Information
            $table->string('payment_terms')->nullable();
            $table->string('payment_method')->nullable();
            $table->decimal('deposit_amount', 10, 2)->default(0);
            $table->decimal('balance_due', 10, 2)->default(0);
            
            // Approval & Authorization
            $table->unsignedBigInteger('requested_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->date('approval_date')->nullable();
            $table->longText('authorization_notes')->nullable();
            
            // Attachments & Notes
            $table->string('supporting_document_path')->nullable();
            $table->string('supporting_document_name')->nullable();
            $table->longText('internal_notes')->nullable();
            $table->longText('terms_and_conditions')->nullable();
            
            $table->timestamps();
            $table->index('po_date');
            $table->index('status');
            $table->index('supplier_id');
            $table->index('company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
