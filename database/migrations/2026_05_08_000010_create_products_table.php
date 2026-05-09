<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->string('barcode')->nullable()->unique();
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('status')->default('Active');
            $table->integer('stock')->default(0);
            $table->string('unit_of_measure')->nullable();
            $table->integer('reorder_level')->default(0);
            $table->string('location')->nullable();
            $table->date('last_restocked_date')->nullable();
            $table->string('last_stock_movement')->nullable();
            $table->decimal('cost_per_unit', 12, 2)->default(0);
            $table->decimal('selling_price', 12, 2)->default(0);
            $table->string('tax_vat')->nullable();
            $table->decimal('tax_vat_custom', 5, 2)->nullable();
            $table->decimal('profit_margin', 8, 2)->nullable();
            $table->string('supplier_id')->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('product_description')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();

            // Foreign key constraint linking to companies.id
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
