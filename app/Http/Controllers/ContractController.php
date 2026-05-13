<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_title' => 'required|string|max:255',
            'contract_type' => 'required|string|max:100',
            'contract_category' => 'required|string|max:100',
            'contract_status' => 'required|string|max:100',
            'contract_priority' => 'nullable|string|max:50',
            'contract_ref_number' => 'nullable|string|max:100',

            'contract_our_company' => 'required|exists:companies,id',
            'contract_counterparty_type' => 'required|string|max:100',
            'contract_counterparty_name' => 'required|string|max:255',
            'contract_contact_person' => 'nullable|string|max:255',
            'contract_counterparty_phone' => 'nullable|string|max:30',
            'contract_counterparty_email' => 'nullable|email|max:255',
            'contract_counterparty_address' => 'nullable|string',

            'contract_start_date' => 'required|date',
            'contract_end_date' => 'required|date|after_or_equal:contract_start_date',
            'contract_renewal_type' => 'nullable|string|max:100',
            'contract_renewal_notice' => 'nullable|integer|min:1|max:365',
            'contract_renewal_date' => 'nullable|date',

            'contract_value' => 'required|numeric|min:0',
            'contract_currency' => 'required|string|max:10',
            'contract_exchange_rate' => 'nullable|numeric|min:0',
            'contract_payment_schedule' => 'nullable|string|max:100',
            'contract_payment_due_day' => 'nullable|integer|min:1|max:31',
            'contract_payment_terms' => 'nullable|string|max:100',
            'contract_vat_applicable' => 'nullable|boolean',
            'contract_vat_rate' => 'nullable|numeric|min:0|max:100',
            'contract_vat_amount' => 'nullable|numeric|min:0',
            'contract_penalty_clause' => 'nullable|string',

            'contract_scope' => 'required|string',
            'contract_deliverables' => 'nullable|string',
            'contract_exclusions' => 'nullable|string',
            'contract_kpis' => 'nullable|string',

            'contract_governing_law' => 'nullable|string|max:100',
            'contract_dispute_resolution' => 'nullable|string|max:100',
            'contract_jurisdiction' => 'nullable|string|max:255',
            'contract_confidentiality' => 'nullable|string|max:100',
            'contract_liability_cap' => 'nullable|numeric|min:0',
            'contract_warranty_period' => 'nullable|string|max:100',
            'contract_termination_clause' => 'nullable|string',

            'contract_our_signatory_name' => 'nullable|string|max:255',
            'contract_our_signatory_title' => 'nullable|string|max:255',
            'contract_our_signatory_date' => 'nullable|date',
            'contract_counterparty_signatory_name' => 'nullable|string|max:255',
            'contract_counterparty_signatory_title' => 'nullable|string|max:255',
            'contract_counterparty_signatory_date' => 'nullable|date',
            'contract_witness_name' => 'nullable|string|max:255',
            'contract_approved_by' => 'nullable|string|max:255',
            'contract_approval_date' => 'nullable|date',
            'contract_signature_status' => 'nullable|string|max:100',

            'contract_expiry_reminder' => 'nullable|integer|min:1|max:365',
            'contract_renewal_reminder' => 'nullable|integer|min:1|max:365',
            'contract_payment_reminder' => 'nullable|integer|min:1|max:365',
            'contract_notify_contract_manager' => 'nullable|boolean',
            'contract_notify_finance' => 'nullable|boolean',
            'contract_notify_ceo' => 'nullable|boolean',
            'contract_notify_legal' => 'nullable|boolean',
            'contract_reminder_notes' => 'nullable|string',

            'contract_signed_document' => 'nullable|file|mimes:pdf|max:10240',
            'contract_supporting_docs' => 'nullable|array',
            'contract_supporting_docs.*' => 'file|mimes:pdf,doc,docx|max:10240',
            'contract_doc_version' => 'nullable|string|max:50',

            'contract_manager' => 'nullable|exists:users,id',
            'contract_related' => 'nullable|string|max:255',
            'contract_internal_notes' => 'nullable|string',
            'contract_tags' => 'nullable|string|max:255',
        ]);

        $contractNumber = 'CON-' . str_pad((string) ((Contract::max('id') ?? 0) + 1), 5, '0', STR_PAD_LEFT);

        $signedPath = null;
        $signedName = null;
        if ($request->hasFile('contract_signed_document')) {
            $signed = $request->file('contract_signed_document');
            $signedPath = $signed->store('contracts/signed', 'public');
            $signedName = $signed->getClientOriginalName();
        }

        $supportingDocs = null;
        if ($request->hasFile('contract_supporting_docs')) {
            $supportingDocs = [];
            foreach ($request->file('contract_supporting_docs') as $document) {
                $supportingDocs[] = [
                    'path' => $document->store('contracts/supporting', 'public'),
                    'name' => $document->getClientOriginalName(),
                ];
            }
        }

        $contract = Contract::create([
            'contract_number' => $contractNumber,
            'contract_title' => $validated['contract_title'],
            'contract_type' => $validated['contract_type'],
            'contract_category' => $validated['contract_category'],
            'contract_status' => $validated['contract_status'],
            'contract_priority' => $validated['contract_priority'] ?? 'Normal',
            'contract_ref_number' => $validated['contract_ref_number'] ?? null,
            'contract_our_company' => $validated['contract_our_company'],
            'contract_counterparty_type' => $validated['contract_counterparty_type'],
            'contract_counterparty_name' => $validated['contract_counterparty_name'],
            'contract_contact_person' => $validated['contract_contact_person'] ?? null,
            'contract_counterparty_phone' => $validated['contract_counterparty_phone'] ?? null,
            'contract_counterparty_email' => $validated['contract_counterparty_email'] ?? null,
            'contract_counterparty_address' => $validated['contract_counterparty_address'] ?? null,
            'contract_start_date' => $validated['contract_start_date'],
            'contract_end_date' => $validated['contract_end_date'],
            'contract_renewal_type' => $validated['contract_renewal_type'] ?? null,
            'contract_renewal_notice' => $validated['contract_renewal_notice'] ?? null,
            'contract_renewal_date' => $validated['contract_renewal_date'] ?? null,
            'contract_value' => $validated['contract_value'],
            'contract_currency' => $validated['contract_currency'],
            'contract_exchange_rate' => $validated['contract_exchange_rate'] ?? 1,
            'contract_payment_schedule' => $validated['contract_payment_schedule'] ?? null,
            'contract_payment_due_day' => $validated['contract_payment_due_day'] ?? null,
            'contract_payment_terms' => $validated['contract_payment_terms'] ?? null,
            'contract_vat_applicable' => $request->boolean('contract_vat_applicable'),
            'contract_vat_rate' => $validated['contract_vat_rate'] ?? null,
            'contract_vat_amount' => $validated['contract_vat_amount'] ?? null,
            'contract_penalty_clause' => $validated['contract_penalty_clause'] ?? null,
            'contract_scope' => $validated['contract_scope'],
            'contract_deliverables' => $validated['contract_deliverables'] ?? null,
            'contract_exclusions' => $validated['contract_exclusions'] ?? null,
            'contract_kpis' => $validated['contract_kpis'] ?? null,
            'contract_governing_law' => $validated['contract_governing_law'] ?? null,
            'contract_dispute_resolution' => $validated['contract_dispute_resolution'] ?? null,
            'contract_jurisdiction' => $validated['contract_jurisdiction'] ?? null,
            'contract_confidentiality' => $validated['contract_confidentiality'] ?? null,
            'contract_liability_cap' => $validated['contract_liability_cap'] ?? null,
            'contract_warranty_period' => $validated['contract_warranty_period'] ?? null,
            'contract_termination_clause' => $validated['contract_termination_clause'] ?? null,
            'contract_our_signatory_name' => $validated['contract_our_signatory_name'] ?? null,
            'contract_our_signatory_title' => $validated['contract_our_signatory_title'] ?? null,
            'contract_our_signatory_date' => $validated['contract_our_signatory_date'] ?? null,
            'contract_counterparty_signatory_name' => $validated['contract_counterparty_signatory_name'] ?? null,
            'contract_counterparty_signatory_title' => $validated['contract_counterparty_signatory_title'] ?? null,
            'contract_counterparty_signatory_date' => $validated['contract_counterparty_signatory_date'] ?? null,
            'contract_witness_name' => $validated['contract_witness_name'] ?? null,
            'contract_approved_by' => $validated['contract_approved_by'] ?? null,
            'contract_approval_date' => $validated['contract_approval_date'] ?? null,
            'contract_signature_status' => $validated['contract_signature_status'] ?? 'Not Signed',
            'contract_expiry_reminder' => $validated['contract_expiry_reminder'] ?? null,
            'contract_renewal_reminder' => $validated['contract_renewal_reminder'] ?? null,
            'contract_payment_reminder' => $validated['contract_payment_reminder'] ?? null,
            'contract_notify_contract_manager' => $request->boolean('contract_notify_contract_manager'),
            'contract_notify_finance' => $request->boolean('contract_notify_finance'),
            'contract_notify_ceo' => $request->boolean('contract_notify_ceo'),
            'contract_notify_legal' => $request->boolean('contract_notify_legal'),
            'contract_reminder_notes' => $validated['contract_reminder_notes'] ?? null,
            'contract_signed_document_path' => $signedPath,
            'contract_signed_document_name' => $signedName,
            'contract_supporting_docs' => $supportingDocs,
            'contract_doc_version' => $validated['contract_doc_version'] ?? null,
            'contract_manager' => $validated['contract_manager'] ?? null,
            'contract_related' => $validated['contract_related'] ?? null,
            'contract_internal_notes' => $validated['contract_internal_notes'] ?? null,
            'contract_tags' => $validated['contract_tags'] ?? null,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => "Contract '{$contract->contract_number}' created successfully.",
                'contract_id' => $contract->id,
                'contract_number' => $contract->contract_number,
            ]);
        }

        return redirect()->back()->with('success', "Contract '{$contract->contract_number}' created successfully.");
    }
}
