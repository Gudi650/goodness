<div id="modalAddContract" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 overflow-y-auto">
    <div class="bg-white rounded-lg max-w-5xl w-full m-4 my-8">
        <div class="sticky top-0 bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between rounded-t-lg">
            <div>
                <h3 class="text-xl font-semibold text-slate-900">Add Contract</h3>
                <p class="text-xs text-slate-500 mt-1">Create a new contract with all terms and conditions</p>
            </div>
            <button onclick="closeLocalModal('modalAddContract'); resetContractForm();" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>

        <form id="contractForm" onsubmit="submitAddContract(event)">
            <div class="px-6 py-5 max-h-[80vh] overflow-y-auto space-y-8">

                <!-- SECTION 1: Contract Identity -->
                <section class="space-y-4">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700">Section 1 - Contract Identity</h4>
                        <p class="text-xs text-slate-500 mt-1">Core contract details and classification</p>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label class="block text-sm text-slate-600">Contract Number *</label>
                            <input id="contract_number" name="contract_number" type="text" readonly
                                class="mt-1 block w-full border border-slate-200 rounded p-2 bg-slate-50 text-slate-600" placeholder="Auto-generated (CON-XXXX)" />
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-sm text-slate-600">Contract Title *</label>
                            <input id="contract_title" name="contract_title" type="text" required
                                class="mt-1 block w-full border border-slate-200 rounded p-2" placeholder="e.g., Service Agreement with ABC Ltd" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Contract Type *</label>
                            <select id="contract_type" name="contract_type" required class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="">-- Select Type --</option>
                                <option value="Service Agreement">Service Agreement</option>
                                <option value="Supply Agreement">Supply Agreement</option>
                                <option value="Lease Agreement">Lease Agreement</option>
                                <option value="Maintenance Contract">Maintenance Contract</option>
                                <option value="Consultancy">Consultancy</option>
                                <option value="Partnership">Partnership</option>
                                <option value="NDA">NDA</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Category *</label>
                            <select id="contract_category" name="contract_category" required class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="">-- Select Category --</option>
                                <option value="Sales Contract">Sales Contract</option>
                                <option value="Procurement Contract">Procurement Contract</option>
                                <option value="Employment Contract">Employment Contract</option>
                                <option value="Rental Contract">Rental Contract</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Status *</label>
                            <select id="contract_status" name="contract_status" required class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="Draft">Draft</option>
                                <option value="Under Review">Under Review</option>
                                <option value="Active">Active</option>
                                <option value="Expired">Expired</option>
                                <option value="Terminated">Terminated</option>
                                <option value="Suspended">Suspended</option>
                                <option value="Renewed">Renewed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Priority</label>
                            <select id="contract_priority" name="contract_priority" class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="Normal">Normal</option>
                                <option value="Low">Low</option>
                                <option value="High">High</option>
                                <option value="Critical">Critical</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Reference Number</label>
                            <input id="contract_ref_number" name="contract_ref_number" type="text"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" placeholder="e.g., CLT-2026-001" />
                        </div>
                    </div>
                </section>

                <!-- SECTION 2: Parties Involved -->
                <section class="space-y-4 border-t pt-6">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700">Section 2 - Parties Involved</h4>
                        <p class="text-xs text-slate-500 mt-1">Our company and counterparty details</p>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm text-slate-600">Our Company *</label>
                            <select id="contract_our_company" name="contract_our_company" required class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="">-- Select Company --</option>
                                @isset($companies)
                                    @foreach ($companies as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Counterparty Type *</label>
                            <select id="contract_counterparty_type" name="contract_counterparty_type" required onchange="updateCounterpartyOptions()"
                                class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="">-- Select Type --</option>
                                <option value="Customer">Customer</option>
                                <option value="Supplier">Supplier</option>
                                <option value="Partner">Partner</option>
                                <option value="Government">Government</option>
                                <option value="Individual">Individual</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm text-slate-600">Counterparty Name *</label>
                            <select id="contract_counterparty_name" name="contract_counterparty_name" required onchange="autoFillCounterpartyDetails()"
                                class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="">-- Select or add --</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Contact Person</label>
                            <input id="contract_contact_person" name="contract_contact_person" type="text"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" placeholder="Auto-filled or enter manually" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Phone</label>
                            <input id="contract_counterparty_phone" name="contract_counterparty_phone" type="text"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Email</label>
                            <input id="contract_counterparty_email" name="contract_counterparty_email" type="email"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm text-slate-600">Address</label>
                            <textarea id="contract_counterparty_address" name="contract_counterparty_address"
                                class="mt-1 block w-full border border-slate-200 rounded p-2 h-16" placeholder="Full address"></textarea>
                        </div>
                    </div>
                </section>

                <!-- SECTION 3: Contract Duration -->
                <section class="space-y-4 border-t pt-6">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700">Section 3 - Contract Duration</h4>
                        <p class="text-xs text-slate-500 mt-1">Timeline and expiry information</p>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label class="block text-sm text-slate-600">Start Date *</label>
                            <input id="contract_start_date" name="contract_start_date" type="date" required
                                class="mt-1 block w-full border border-slate-200 rounded p-2" onchange="computeContractDuration()" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">End Date *</label>
                            <input id="contract_end_date" name="contract_end_date" type="date" required
                                class="mt-1 block w-full border border-slate-200 rounded p-2" onchange="computeContractDuration()" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Duration (readonly)</label>
                            <input id="contract_duration_display" type="text" readonly
                                class="mt-1 block w-full border border-slate-200 rounded p-2 bg-slate-50 text-slate-600" placeholder="e.g., 12 months" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Renewal Type</label>
                            <select id="contract_renewal_type" name="contract_renewal_type" class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="One Time">One Time</option>
                                <option value="Auto-Renewable">Auto-Renewable</option>
                                <option value="Manual Renewal">Manual Renewal</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Renewal Notice Period</label>
                            <select id="contract_renewal_notice" name="contract_renewal_notice" class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="">-- Select --</option>
                                <option value="7">7 Days</option>
                                <option value="14">14 Days</option>
                                <option value="30">30 Days</option>
                                <option value="60">60 Days</option>
                                <option value="90">90 Days</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Renewal Date</label>
                            <input id="contract_renewal_date" name="contract_renewal_date" type="date"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                    </div>
                    <div id="contract_duration_warning" class="mt-4"></div>
                </section>

                <!-- SECTION 4: Financial Terms -->
                <section class="space-y-4 border-t pt-6">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700">Section 4 - Financial Terms</h4>
                        <p class="text-xs text-slate-500 mt-1">Pricing, payments, and taxes</p>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label class="block text-sm text-slate-600">Contract Value (TZS) *</label>
                            <input id="contract_value" name="contract_value" type="number" step="0.01" required
                                class="mt-1 block w-full border border-slate-200 rounded p-2" placeholder="0.00" onchange="computeFinancials()" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Currency *</label>
                            <select id="contract_currency" name="contract_currency" required onchange="computeFinancials()"
                                class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="TZS">TZS</option>
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                                <option value="KES">KES</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Exchange Rate</label>
                            <input id="contract_exchange_rate" name="contract_exchange_rate" type="number" step="0.01" value="1.00"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" onchange="computeFinancials()" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">TZS Equivalent (readonly)</label>
                            <input id="contract_tzs_equivalent" type="text" readonly
                                class="mt-1 block w-full border border-slate-200 rounded p-2 bg-slate-50 text-slate-600" placeholder="0.00" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Payment Schedule</label>
                            <select id="contract_payment_schedule" name="contract_payment_schedule" onchange="computePaymentAmount()"
                                class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="One Time">One Time</option>
                                <option value="Monthly">Monthly</option>
                                <option value="Quarterly">Quarterly</option>
                                <option value="Semi-Annual">Semi-Annual</option>
                                <option value="Annual">Annual</option>
                                <option value="Milestone Based">Milestone Based</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Payment Due Day (of month)</label>
                            <input id="contract_payment_due_day" name="contract_payment_due_day" type="number" min="1" max="31"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" placeholder="e.g., 5" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Payment Terms</label>
                            <select id="contract_payment_terms" name="contract_payment_terms" class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="Cash on Delivery">Cash on Delivery</option>
                                <option value="7 Days">7 Days</option>
                                <option value="14 Days">14 Days</option>
                                <option value="30 Days">30 Days</option>
                                <option value="60 Days">60 Days</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="contract_vat_applicable" name="contract_vat_applicable" onchange="toggleVATFields()"
                                    class="w-4 h-4 border border-slate-200 rounded" />
                                <span class="text-sm text-slate-600">Tax / VAT Applicable</span>
                            </label>
                        </div>
                        <div id="vat_fields" class="hidden lg:col-span-3 grid gap-4 md:grid-cols-3">
                            <div>
                                <label class="block text-sm text-slate-600">VAT Rate %</label>
                                <input id="contract_vat_rate" name="contract_vat_rate" type="number" step="0.01" value="18"
                                    class="mt-1 block w-full border border-slate-200 rounded p-2" onchange="computeFinancials()" />
                            </div>
                            <div>
                                <label class="block text-sm text-slate-600">VAT Amount (readonly)</label>
                                <input id="contract_vat_amount" type="text" readonly
                                    class="mt-1 block w-full border border-slate-200 rounded p-2 bg-slate-50 text-slate-600" placeholder="0.00" />
                            </div>
                        </div>
                        <div class="lg:col-span-3">
                            <label class="block text-sm text-slate-600">Penalty Clause</label>
                            <textarea id="contract_penalty_clause" name="contract_penalty_clause"
                                class="mt-1 block w-full border border-slate-200 rounded p-2 h-16" placeholder="Describe penalties for late payment or non-performance"></textarea>
                        </div>
                    </div>
                </section>

                <!-- SECTION 5: Deliverables & Scope -->
                <section class="space-y-4 border-t pt-6">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700">Section 5 - Deliverables & Scope</h4>
                        <p class="text-xs text-slate-500 mt-1">What is covered and expected outcomes</p>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-slate-600">Scope of Work *</label>
                            <textarea id="contract_scope" name="contract_scope" required
                                class="mt-1 block w-full border border-slate-200 rounded p-2 h-24" placeholder="Detailed description of work covered"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Key Deliverables</label>
                            <textarea id="contract_deliverables" name="contract_deliverables"
                                class="mt-1 block w-full border border-slate-200 rounded p-2 h-20" placeholder="Specific outputs or milestones expected"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Exclusions</label>
                            <textarea id="contract_exclusions" name="contract_exclusions"
                                class="mt-1 block w-full border border-slate-200 rounded p-2 h-16" placeholder="What is NOT covered"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Performance Metrics / KPIs</label>
                            <textarea id="contract_kpis" name="contract_kpis"
                                class="mt-1 block w-full border border-slate-200 rounded p-2 h-16" placeholder="How success is measured"></textarea>
                        </div>
                    </div>
                </section>

                <!-- SECTION 6: Legal & Compliance -->
                <section class="space-y-4 border-t pt-6">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700">Section 6 - Legal & Compliance</h4>
                        <p class="text-xs text-slate-500 mt-1">Governing terms and legal framework</p>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label class="block text-sm text-slate-600">Governing Law</label>
                            <select id="contract_governing_law" name="contract_governing_law" class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="Tanzania">Tanzania</option>
                                <option value="Kenya">Kenya</option>
                                <option value="Uganda">Uganda</option>
                                <option value="International">International</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Dispute Resolution</label>
                            <select id="contract_dispute_resolution" name="contract_dispute_resolution" class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="Negotiation">Negotiation</option>
                                <option value="Mediation">Mediation</option>
                                <option value="Arbitration">Arbitration</option>
                                <option value="Court">Court</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Jurisdiction</label>
                            <input id="contract_jurisdiction" name="contract_jurisdiction" type="text"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" placeholder="e.g., Courts of Dar es Salaam" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Confidentiality Level</label>
                            <select id="contract_confidentiality" name="contract_confidentiality" class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="Public">Public</option>
                                <option value="Internal">Internal</option>
                                <option value="Confidential">Confidential</option>
                                <option value="Strictly Confidential">Strictly Confidential</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Liability Cap (TZS)</label>
                            <input id="contract_liability_cap" name="contract_liability_cap" type="number" step="0.01"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" placeholder="0.00" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Warranty Period</label>
                            <select id="contract_warranty_period" name="contract_warranty_period" class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="None">None</option>
                                <option value="30 Days">30 Days</option>
                                <option value="90 Days">90 Days</option>
                                <option value="6 Months">6 Months</option>
                                <option value="1 Year">1 Year</option>
                                <option value="2 Years">2 Years</option>
                            </select>
                        </div>
                        <div class="lg:col-span-3">
                            <label class="block text-sm text-slate-600">Termination Clause</label>
                            <textarea id="contract_termination_clause" name="contract_termination_clause"
                                class="mt-1 block w-full border border-slate-200 rounded p-2 h-16" placeholder="Conditions under which contract can be terminated"></textarea>
                        </div>
                    </div>
                </section>

                <!-- SECTION 7: Signatories & Authorization -->
                <section class="space-y-4 border-t pt-6">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700">Section 7 - Signatories & Authorization</h4>
                        <p class="text-xs text-slate-500 mt-1">Who signed and internal approvals</p>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label class="block text-sm text-slate-600">Our Signatory Name</label>
                            <input id="contract_our_signatory_name" name="contract_our_signatory_name" type="text"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" placeholder="Full name" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Our Signatory Title</label>
                            <input id="contract_our_signatory_title" name="contract_our_signatory_title" type="text"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" placeholder="e.g., CEO, Director" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Our Signatory Date</label>
                            <input id="contract_our_signatory_date" name="contract_our_signatory_date" type="date"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Counterparty Signatory Name</label>
                            <input id="contract_counterparty_signatory_name" name="contract_counterparty_signatory_name" type="text"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" placeholder="Full name" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Counterparty Signatory Title</label>
                            <input id="contract_counterparty_signatory_title" name="contract_counterparty_signatory_title" type="text"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" placeholder="e.g., Managing Director" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Counterparty Signatory Date</label>
                            <input id="contract_counterparty_signatory_date" name="contract_counterparty_signatory_date" type="date"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Witness Name</label>
                            <input id="contract_witness_name" name="contract_witness_name" type="text"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" placeholder="Optional" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Approved By</label>
                            <select id="contract_approved_by" name="contract_approved_by" class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="">-- Select Approver --</option>
                                <option value="Chairman">Chairman</option>
                                <option value="Managing Director">Managing Director</option>
                                <option value="Finance Director">Finance Director</option>
                                <option value="Legal Manager">Legal Manager</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Approval Date</label>
                            <input id="contract_approval_date" name="contract_approval_date" type="date"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Digital Signature Status</label>
                            <select id="contract_signature_status" name="contract_signature_status" class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="Not Signed">Not Signed</option>
                                <option value="Partially Signed">Partially Signed</option>
                                <option value="Fully Signed">Fully Signed</option>
                            </select>
                        </div>
                    </div>
                </section>

                <!-- SECTION 8: Reminders & Notifications -->
                <section class="space-y-4 border-t pt-6">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700">Section 8 - Reminders & Notifications</h4>
                        <p class="text-xs text-slate-500 mt-1">Alerts and who to notify</p>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label class="block text-sm text-slate-600">Expiry Reminder (days before)</label>
                            <select id="contract_expiry_reminder" name="contract_expiry_reminder" class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="">-- Select --</option>
                                <option value="7">7 Days</option>
                                <option value="14">14 Days</option>
                                <option value="30">30 Days</option>
                                <option value="60">60 Days</option>
                                <option value="90">90 Days</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Renewal Reminder (days before)</label>
                            <select id="contract_renewal_reminder" name="contract_renewal_reminder" class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="">-- Select --</option>
                                <option value="7">7 Days</option>
                                <option value="14">14 Days</option>
                                <option value="30">30 Days</option>
                                <option value="60">60 Days</option>
                                <option value="90">90 Days</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Payment Reminder (days before)</label>
                            <select id="contract_payment_reminder" name="contract_payment_reminder" class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="">-- Select --</option>
                                <option value="3">3 Days</option>
                                <option value="5">5 Days</option>
                                <option value="7">7 Days</option>
                                <option value="14">14 Days</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-600">Notify</label>
                        <div class="mt-2 space-y-2">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="contract_notify_contract_manager" value="1" class="w-4 h-4 border border-slate-200 rounded" />
                                <span class="text-sm text-slate-600">Contract Manager</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="contract_notify_finance" value="1" class="w-4 h-4 border border-slate-200 rounded" />
                                <span class="text-sm text-slate-600">Finance</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="contract_notify_ceo" value="1" class="w-4 h-4 border border-slate-200 rounded" />
                                <span class="text-sm text-slate-600">CEO</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="contract_notify_legal" value="1" class="w-4 h-4 border border-slate-200 rounded" />
                                <span class="text-sm text-slate-600">Legal</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-600">Reminder Notes</label>
                        <textarea id="contract_reminder_notes" name="contract_reminder_notes"
                            class="mt-1 block w-full border border-slate-200 rounded p-2 h-16" placeholder="Custom message to include in reminders"></textarea>
                    </div>
                </section>

                <!-- SECTION 9: Documents & Attachments -->
                <section class="space-y-4 border-t pt-6">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700">Section 9 - Documents & Attachments</h4>
                        <p class="text-xs text-slate-500 mt-1">Upload contract and supporting documents</p>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-slate-600">Signed Contract Document (PDF)</label>
                            <input id="contract_signed_document" name="contract_signed_document" type="file" accept=".pdf"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Supporting Documents</label>
                            <input id="contract_supporting_docs" name="contract_supporting_docs[]" type="file" multiple accept=".pdf,.doc,.docx"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Document Version</label>
                            <input id="contract_doc_version" name="contract_doc_version" type="text"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" placeholder="e.g., v1.0, v2.1" />
                        </div>
                    </div>
                </section>

                <!-- SECTION 10: Internal Notes -->
                <section class="space-y-4 border-t pt-6">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700">Section 10 - Internal Notes</h4>
                        <p class="text-xs text-slate-500 mt-1">Assign manager, add notes and tags</p>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm text-slate-600">Assigned Contract Manager</label>
                            <select id="contract_manager" name="contract_manager" class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="">-- Select Staff Member --</option>
                                @isset($staff)
                                    @foreach ($staff as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Related Contracts</label>
                            <input id="contract_related" name="contract_related" type="text"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" placeholder="e.g., CON-0012, CON-0015" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-600">Internal Notes</label>
                        <textarea id="contract_internal_notes" name="contract_internal_notes"
                            class="mt-1 block w-full border border-slate-200 rounded p-2 h-20" placeholder="Internal remarks only"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-600">Tags (comma-separated)</label>
                        <input id="contract_tags" name="contract_tags" type="text"
                            class="mt-1 block w-full border border-slate-200 rounded p-2" placeholder="e.g., Long-term, VIP, Renewal, Priority" />
                    </div>
                </section>

            </div>

            <div class="px-6 py-4 flex gap-3 justify-end border-t border-slate-100 sticky bottom-0 bg-white">
                <button type="button" onclick="closeLocalModal('modalAddContract'); resetContractForm();" class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm font-medium">Cancel</button>
                <button type="submit" id="submitContractBtn" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium">Create Contract</button>
            </div>
        </form>
    </div>
</div>
