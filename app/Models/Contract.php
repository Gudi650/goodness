<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'contract_number',
    'contract_title',
    'contract_type',
    'contract_category',
    'contract_status',
    'contract_priority',
    'contract_ref_number',
    'contract_our_company',
    'contract_counterparty_type',
    'contract_counterparty_name',
    'contract_contact_person',
    'contract_counterparty_phone',
    'contract_counterparty_email',
    'contract_counterparty_address',
    'contract_start_date',
    'contract_end_date',
    'contract_renewal_type',
    'contract_renewal_notice',
    'contract_renewal_date',
    'contract_value',
    'contract_currency',
    'contract_exchange_rate',
    'contract_payment_schedule',
    'contract_payment_due_day',
    'contract_payment_terms',
    'contract_vat_applicable',
    'contract_vat_rate',
    'contract_vat_amount',
    'contract_penalty_clause',
    'contract_scope',
    'contract_deliverables',
    'contract_exclusions',
    'contract_kpis',
    'contract_governing_law',
    'contract_dispute_resolution',
    'contract_jurisdiction',
    'contract_confidentiality',
    'contract_liability_cap',
    'contract_warranty_period',
    'contract_termination_clause',
    'contract_our_signatory_name',
    'contract_our_signatory_title',
    'contract_our_signatory_date',
    'contract_counterparty_signatory_name',
    'contract_counterparty_signatory_title',
    'contract_counterparty_signatory_date',
    'contract_witness_name',
    'contract_approved_by',
    'contract_approval_date',
    'contract_signature_status',
    'contract_expiry_reminder',
    'contract_renewal_reminder',
    'contract_payment_reminder',
    'contract_notify_contract_manager',
    'contract_notify_finance',
    'contract_notify_ceo',
    'contract_notify_legal',
    'contract_reminder_notes',
    'contract_signed_document_path',
    'contract_signed_document_name',
    'contract_supporting_docs',
    'contract_doc_version',
    'contract_manager',
    'contract_related',
    'contract_internal_notes',
    'contract_tags',
])]
class Contract extends Model
{
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'contract_our_company');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contract_manager');
    }

    protected function casts(): array
    {
        return [
            'contract_start_date' => 'date',
            'contract_end_date' => 'date',
            'contract_renewal_date' => 'date',
            'contract_our_signatory_date' => 'date',
            'contract_counterparty_signatory_date' => 'date',
            'contract_approval_date' => 'date',
            'contract_vat_applicable' => 'boolean',
            'contract_notify_contract_manager' => 'boolean',
            'contract_notify_finance' => 'boolean',
            'contract_notify_ceo' => 'boolean',
            'contract_notify_legal' => 'boolean',
            'contract_value' => 'decimal:2',
            'contract_exchange_rate' => 'decimal:4',
            'contract_vat_rate' => 'decimal:2',
            'contract_vat_amount' => 'decimal:2',
            'contract_liability_cap' => 'decimal:2',
            'contract_supporting_docs' => 'array',
        ];
    }
}
