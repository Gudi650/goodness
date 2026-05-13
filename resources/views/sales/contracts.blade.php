<div id="tab-contracts" class="tab-content hidden">
    <div class="flex flex-col gap-3 mb-6 lg:flex-row lg:items-center lg:justify-between">
        <h2 class="text-lg font-semibold font-display">Contracts</h2>
        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto lg:items-center">
            <button onclick="openAddContractModal()"
                class="w-full sm:w-auto flex-shrink-0 whitespace-nowrap px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium transition-colors">Add
                Contract</button>
        </div>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Contract No.</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Client</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Value</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Start Date</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">End Date</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Status</th>
                        <th class="px-4 py-3 text-center text-xs uppercase tracking-wider font-medium text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody id="contractsTable" class="divide-y divide-slate-100">
                    @forelse(($contracts ?? []) as $contract)
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-4 py-3 text-sm text-slate-700 font-medium">{{ $contract->contract_number }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700">
                                {{ $contract->contract_counterparty_name }}
                                <div class="text-xs text-slate-500 mt-1">
                                    {{ $contract->company->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-700">
                                {{ $contract->contract_currency }} {{ number_format((float) $contract->contract_value, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ optional($contract->contract_start_date)->format('Y-m-d') }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ optional($contract->contract_end_date)->format('Y-m-d') }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $status = $contract->contract_status ?? 'Draft';
                                    $statusStyles = [
                                        'Active' => 'bg-green-50 text-green-700',
                                        'Draft' => 'bg-slate-100 text-slate-700',
                                        'Under Review' => 'bg-blue-50 text-blue-700',
                                        'Expired' => 'bg-red-50 text-red-700',
                                        'Terminated' => 'bg-red-50 text-red-700',
                                        'Suspended' => 'bg-amber-50 text-amber-700',
                                        'Renewed' => 'bg-emerald-50 text-emerald-700',
                                    ];
                                    $statusClass = $statusStyles[$status] ?? 'bg-slate-100 text-slate-700';
                                @endphp
                                <span class="px-2 py-1 rounded text-xs {{ $statusClass }}">{{ $status }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" onclick="toggleContractDetails({{ $contract->id }}, this)" 
                                        class="inline-flex h-8 w-8 items-center justify-center rounded border border-slate-200 text-slate-600 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 transition-colors"
                                        title="View details">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button type="button" onclick="editContract({{ $contract->id }})" 
                                        class="inline-flex h-8 w-8 items-center justify-center rounded border border-slate-200 text-slate-600 hover:bg-amber-50 hover:text-amber-600 hover:border-amber-300 transition-colors"
                                        title="Edit contract">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button type="button" onclick="confirmDeleteContract({{ $contract->id }}, '{{ $contract->contract_number }}')" 
                                        class="inline-flex h-8 w-8 items-center justify-center rounded border border-slate-200 text-slate-600 hover:bg-red-50 hover:text-red-600 hover:border-red-300 transition-colors"
                                        title="Delete contract">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-6 0h6" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr id="contract-details-{{ $contract->id }}" class="hidden">
                            <td colspan="7" class="px-4 py-4 bg-slate-50">
                                <div class="space-y-6">
                                    <!-- Contract Basics -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                        <div>
                                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-3">Basic Info</h4>
                                            <div class="space-y-2 text-sm">
                                                <div>
                                                    <span class="text-slate-500">Title:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_title ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Type:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_type ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Category:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_category ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Priority:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_priority ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Ref Number:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_ref_number ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-3">Dates</h4>
                                            <div class="space-y-2 text-sm">
                                                <div>
                                                    <span class="text-slate-500">Start Date:</span>
                                                    <span class="text-slate-700 font-medium">{{ optional($contract->contract_start_date)->format('Y-m-d') ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">End Date:</span>
                                                    <span class="text-slate-700 font-medium">{{ optional($contract->contract_end_date)->format('Y-m-d') ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Duration:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_duration ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Renewal Date:</span>
                                                    <span class="text-slate-700 font-medium">{{ optional($contract->contract_renewal_date)->format('Y-m-d') ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-3">Renewal</h4>
                                            <div class="space-y-2 text-sm">
                                                <div>
                                                    <span class="text-slate-500">Renewal Type:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_renewal_type ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Renewal Notice (days):</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_renewal_notice ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Expiry Reminder (days):</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_expiry_reminder ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Renewal Reminder (days):</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_renewal_reminder ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-3">Currency</h4>
                                            <div class="space-y-2 text-sm">
                                                <div>
                                                    <span class="text-slate-500">Currency:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_currency ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Exchange Rate:</span>
                                                    <span class="text-slate-700 font-medium">{{ number_format((float) $contract->contract_exchange_rate, 4) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Financial Information -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 border-t border-slate-200 pt-4">
                                        <div>
                                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-3">Financial Values</h4>
                                            <div class="space-y-2 text-sm">
                                                <div>
                                                    <span class="text-slate-500">Contract Value:</span>
                                                    <span class="text-slate-700 font-medium">{{ number_format((float) $contract->contract_value, 2) }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">TZS Equivalent:</span>
                                                    <span class="text-slate-700 font-medium">{{ number_format((float) $contract->contract_tzs_equivalent, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-3">VAT Information</h4>
                                            <div class="space-y-2 text-sm">
                                                <div>
                                                    <span class="text-slate-500">VAT Applicable:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_vat_applicable ? 'Yes' : 'No' }}</span>
                                                </div>
                                                @if($contract->contract_vat_applicable)
                                                    <div>
                                                        <span class="text-slate-500">VAT Rate:</span>
                                                        <span class="text-slate-700 font-medium">{{ $contract->contract_vat_rate ?? 0 }}%</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-slate-500">VAT Amount:</span>
                                                        <span class="text-slate-700 font-medium">{{ number_format((float) $contract->contract_vat_amount, 2) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div>
                                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-3">Payment Terms</h4>
                                            <div class="space-y-2 text-sm">
                                                <div>
                                                    <span class="text-slate-500">Payment Schedule:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_payment_schedule ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Due Day:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_payment_due_day ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Payment Terms:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_payment_terms ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Payment Reminder (days):</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_payment_reminder ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-3">Penalties & Warranty</h4>
                                            <div class="space-y-2 text-sm">
                                                <div>
                                                    <span class="text-slate-500">Liability Cap:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_liability_cap ? number_format((float) $contract->contract_liability_cap, 2) : 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Warranty Period:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_warranty_period ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Parties Information -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 border-t border-slate-200 pt-4">
                                        <div>
                                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-3">Our Company</h4>
                                            <div class="space-y-2 text-sm">
                                                <div>
                                                    <span class="text-slate-500">Company:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->company->name ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-3">Counterparty</h4>
                                            <div class="space-y-2 text-sm">
                                                <div>
                                                    <span class="text-slate-500">Type:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_counterparty_type ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Name:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_counterparty_name ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Contact Person:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_contact_person ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Phone:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_counterparty_phone ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Email:</span>
                                                    <span class="text-slate-700 font-medium break-all">{{ $contract->contract_counterparty_email ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Address:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_counterparty_address ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-3">Signatories</h4>
                                            <div class="space-y-2 text-sm">
                                                <div>
                                                    <span class="text-slate-500">Our Signatory:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_our_signatory_name ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Our Signatory Title:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_our_signatory_title ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Our Signed Date:</span>
                                                    <span class="text-slate-700 font-medium">{{ optional($contract->contract_our_signatory_date)->format('Y-m-d') ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Counterparty Signatory:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_counterparty_signatory_name ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Counterparty Title:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_counterparty_signatory_title ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Counterparty Signed Date:</span>
                                                    <span class="text-slate-700 font-medium">{{ optional($contract->contract_counterparty_signatory_date)->format('Y-m-d') ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-3">Approvals</h4>
                                            <div class="space-y-2 text-sm">
                                                <div>
                                                    <span class="text-slate-500">Status:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_signature_status ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Approved By:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_approved_by ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Approval Date:</span>
                                                    <span class="text-slate-700 font-medium">{{ optional($contract->contract_approval_date)->format('Y-m-d') ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Witness:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_witness_name ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Manager:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->manager->name ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Scope & Deliverables -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-slate-200 pt-4">
                                        <div>
                                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Scope</h4>
                                            <p class="text-sm text-slate-700">{{ $contract->contract_scope ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Deliverables</h4>
                                            <p class="text-sm text-slate-700">{{ $contract->contract_deliverables ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Exclusions</h4>
                                            <p class="text-sm text-slate-700">{{ $contract->contract_exclusions ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">KPIs</h4>
                                            <p class="text-sm text-slate-700">{{ $contract->contract_kpis ?? 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <!-- Legal & Compliance -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-slate-200 pt-4">
                                        <div>
                                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Governing Law</h4>
                                            <p class="text-sm text-slate-700">{{ $contract->contract_governing_law ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Dispute Resolution</h4>
                                            <p class="text-sm text-slate-700">{{ $contract->contract_dispute_resolution ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Jurisdiction</h4>
                                            <p class="text-sm text-slate-700">{{ $contract->contract_jurisdiction ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Confidentiality</h4>
                                            <p class="text-sm text-slate-700">{{ $contract->contract_confidentiality ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Penalty Clause</h4>
                                            <p class="text-sm text-slate-700">{{ $contract->contract_penalty_clause ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Termination Clause</h4>
                                            <p class="text-sm text-slate-700">{{ $contract->contract_termination_clause ?? 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <!-- Notifications & Documents -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-slate-200 pt-4">
                                        <div>
                                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-3">Notifications</h4>
                                            <div class="space-y-2 text-sm">
                                                <div class="flex items-center gap-2">
                                                    <input type="checkbox" disabled {{ $contract->contract_notify_contract_manager ? 'checked' : '' }} class="rounded">
                                                    <span class="text-slate-700">Notify Contract Manager</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <input type="checkbox" disabled {{ $contract->contract_notify_finance ? 'checked' : '' }} class="rounded">
                                                    <span class="text-slate-700">Notify Finance</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <input type="checkbox" disabled {{ $contract->contract_notify_ceo ? 'checked' : '' }} class="rounded">
                                                    <span class="text-slate-700">Notify CEO</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <input type="checkbox" disabled {{ $contract->contract_notify_legal ? 'checked' : '' }} class="rounded">
                                                    <span class="text-slate-700">Notify Legal</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-3">Documents</h4>
                                            <div class="space-y-2 text-sm">
                                                <div>
                                                    <span class="text-slate-500">Version:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_doc_version ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Signed Document:</span>
                                                    <span class="text-slate-700 font-medium">{{ $contract->contract_signed_document_name ?? 'No file' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-500">Supporting Docs:</span>
                                                    @if($contract->contract_supporting_docs && is_array($contract->contract_supporting_docs))
                                                        <div class="mt-1 space-y-1">
                                                            @foreach($contract->contract_supporting_docs as $doc)
                                                                <div class="text-slate-700">• {{ $doc['name'] ?? 'Document' }}</div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="text-slate-700">No files</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Additional Information -->
                                    @if($contract->contract_tags || $contract->contract_related || $contract->contract_internal_notes || $contract->contract_reminder_notes)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-slate-200 pt-4">
                                            @if($contract->contract_tags)
                                                <div>
                                                    <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Tags</h4>
                                                    <p class="text-sm text-slate-700">{{ $contract->contract_tags }}</p>
                                                </div>
                                            @endif
                                            @if($contract->contract_related)
                                                <div>
                                                    <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Related Contracts</h4>
                                                    <p class="text-sm text-slate-700">{{ $contract->contract_related }}</p>
                                                </div>
                                            @endif
                                            @if($contract->contract_internal_notes)
                                                <div>
                                                    <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Internal Notes</h4>
                                                    <p class="text-sm text-slate-700">{{ $contract->contract_internal_notes }}</p>
                                                </div>
                                            @endif
                                            @if($contract->contract_reminder_notes)
                                                <div>
                                                    <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Reminder Notes</h4>
                                                    <p class="text-sm text-slate-700">{{ $contract->contract_reminder_notes }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-sm text-slate-500">
                                No contracts found. Create one using the Add Contract button.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
