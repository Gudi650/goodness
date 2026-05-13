<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->unique()->index();
            $table->string('contract_title');
            $table->string('contract_type');
            $table->string('contract_category');
            $table->string('contract_status')->default('Draft');
            $table->string('contract_priority')->default('Normal');
            $table->string('contract_ref_number')->nullable();

            $table->foreignId('contract_our_company')->nullable()->constrained('companies')->nullOnDelete();
            $table->string('contract_counterparty_type');
            $table->string('contract_counterparty_name');
            $table->string('contract_contact_person')->nullable();
            $table->string('contract_counterparty_phone', 30)->nullable();
            $table->string('contract_counterparty_email')->nullable();
            $table->longText('contract_counterparty_address')->nullable();

            $table->date('contract_start_date');
            $table->date('contract_end_date');
            $table->string('contract_renewal_type')->nullable();
            $table->unsignedSmallInteger('contract_renewal_notice')->nullable();
            $table->date('contract_renewal_date')->nullable();

            $table->decimal('contract_value', 15, 2);
            $table->string('contract_currency', 10)->default('TZS');
            $table->decimal('contract_exchange_rate', 10, 4)->default(1);
            $table->string('contract_payment_schedule')->nullable();
            $table->unsignedTinyInteger('contract_payment_due_day')->nullable();
            $table->string('contract_payment_terms')->nullable();
            $table->boolean('contract_vat_applicable')->default(false);
            $table->decimal('contract_vat_rate', 5, 2)->nullable();
            $table->decimal('contract_vat_amount', 15, 2)->nullable();
            $table->longText('contract_penalty_clause')->nullable();

            $table->longText('contract_scope');
            $table->longText('contract_deliverables')->nullable();
            $table->longText('contract_exclusions')->nullable();
            $table->longText('contract_kpis')->nullable();

            $table->string('contract_governing_law')->nullable();
            $table->string('contract_dispute_resolution')->nullable();
            $table->string('contract_jurisdiction')->nullable();
            $table->string('contract_confidentiality')->nullable();
            $table->decimal('contract_liability_cap', 15, 2)->nullable();
            $table->string('contract_warranty_period')->nullable();
            $table->longText('contract_termination_clause')->nullable();

            $table->string('contract_our_signatory_name')->nullable();
            $table->string('contract_our_signatory_title')->nullable();
            $table->date('contract_our_signatory_date')->nullable();
            $table->string('contract_counterparty_signatory_name')->nullable();
            $table->string('contract_counterparty_signatory_title')->nullable();
            $table->date('contract_counterparty_signatory_date')->nullable();
            $table->string('contract_witness_name')->nullable();
            $table->string('contract_approved_by')->nullable();
            $table->date('contract_approval_date')->nullable();
            $table->string('contract_signature_status')->nullable();

            $table->unsignedSmallInteger('contract_expiry_reminder')->nullable();
            $table->unsignedSmallInteger('contract_renewal_reminder')->nullable();
            $table->unsignedSmallInteger('contract_payment_reminder')->nullable();
            $table->boolean('contract_notify_contract_manager')->default(false);
            $table->boolean('contract_notify_finance')->default(false);
            $table->boolean('contract_notify_ceo')->default(false);
            $table->boolean('contract_notify_legal')->default(false);
            $table->longText('contract_reminder_notes')->nullable();

            $table->string('contract_signed_document_path')->nullable();
            $table->string('contract_signed_document_name')->nullable();
            $table->json('contract_supporting_docs')->nullable();
            $table->string('contract_doc_version')->nullable();

            $table->foreignId('contract_manager')->nullable()->constrained('users')->nullOnDelete();
            $table->string('contract_related')->nullable();
            $table->longText('contract_internal_notes')->nullable();
            $table->string('contract_tags')->nullable();

            $table->timestamps();

            $table->index('contract_start_date');
            $table->index('contract_end_date');
            $table->index('contract_status');
            $table->index('contract_counterparty_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
