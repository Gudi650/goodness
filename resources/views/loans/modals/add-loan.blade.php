<div id="modal-add-loan" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 px-4">
    <div class="w-full max-w-2xl rounded-lg bg-white shadow-lg">
        <form action="{{ route('loans.store') }}" method="POST" id="add-loan-form">
            @csrf
            <div class="flex items-center justify-between border-b px-5 py-4">
                <h3 class="text-lg font-semibold">Add Loan</h3>
                <button type="button" onclick="closeLoanModal('modal-add-loan')" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <div class="max-h-[70vh] space-y-4 overflow-y-auto px-5 py-4">
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Loan Type</label>
                    <select name="loan_type" id="loan_type" required class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                        <option value="external_borrow" @selected(old('loan_type', 'external_borrow') === 'external_borrow')>External borrow (from outside lender)</option>
                        <option value="intercompany" @selected(old('loan_type') === 'intercompany')>Inter-company (company → company)</option>
                        <option value="employee" @selected(old('loan_type') === 'employee')>Employee loan (company → employee)</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500" id="company_label">Company (Borrower)</label>
                        <select name="company_id" id="loan_company_id" required class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                            <option value="" disabled selected>Select company</option>
                            @foreach ($companies ?? [] as $company)
                                <option value="{{ $company->id }}" @selected((string) old('company_id') === (string) $company->id)>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="field_lender">
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Lender</label>
                        <input type="text" name="lender" id="loan_lender" maxlength="150" placeholder="e.g. CRDB Bank"
                            value="{{ old('lender') }}"
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>

                    <div id="field_counterparty" class="hidden">
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Borrowing Company</label>
                        <select name="counterparty_company_id" id="loan_counterparty_id" class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                            <option value="">Select borrowing company</option>
                            @foreach ($companies ?? [] as $company)
                                <option value="{{ $company->id }}" @selected((string) old('counterparty_company_id') === (string) $company->id)>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="field_employee" class="hidden">
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Employee</label>
                        <select name="employee_id" id="loan_employee_id" class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                            <option value="">Select employee</option>
                            @foreach ($employees ?? [] as $employee)
                                <option value="{{ $employee->id }}" data-company="{{ $employee->company_id }}" @selected((string) old('employee_id') === (string) $employee->id)>
                                    {{ $employee->name }}{{ $employee->company?->name ? ' ('.$employee->company->name.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div id="field_source_bank" class="hidden">
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Bank (Money From)</label>
                        <select name="source_bank_id" id="loan_source_bank_id" class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                            <option value="">-- Select source bank --</option>
                        </select>
                    </div>

                    <div id="field_receiving_bank">
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Bank (Money To)</label>
                        <select name="bank_id" id="loan_bank_id" class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                            <option value="">-- Select receiving bank --</option>
                        </select>
                    </div>
                </div>

                <p class="-mt-2 text-xs text-slate-400">Loan code is assigned automatically on save.</p>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Principal (TZS)</label>
                        <input type="number" step="0.01" min="0" name="principal" required value="{{ old('principal') }}"
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Interest Rate (% p.a.)</label>
                        <input type="number" step="0.01" min="0" max="100" name="interest_rate" required value="{{ old('interest_rate') }}"
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Interest Type</label>
                        <select name="interest_type" required class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                            <option value="Flat" @selected(old('interest_type', 'Flat') === 'Flat')>Flat</option>
                            <option value="Reducing Balance" @selected(old('interest_type') === 'Reducing Balance')>Reducing Balance</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Term (months)</label>
                        <input type="number" min="1" name="term_months" required value="{{ old('term_months') }}"
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Start Date</label>
                        <input type="date" name="start_date" required value="{{ old('start_date') }}"
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Maturity Date</label>
                        <input type="date" name="maturity_date" required value="{{ old('maturity_date') }}"
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Disbursement Date</label>
                        <input type="date" name="disbursement_date" value="{{ old('disbursement_date') }}"
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Status</label>
                        <select name="status" required class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                            <option value="Pending" @selected(old('status', 'Pending') === 'Pending')>Pending</option>
                            <option value="Active" @selected(old('status') === 'Active')>Active</option>
                            <option value="Closed" @selected(old('status') === 'Closed')>Closed</option>
                            <option value="Overdue" @selected(old('status') === 'Overdue')>Overdue</option>
                            <option value="Defaulted" @selected(old('status') === 'Defaulted')>Defaulted</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Guarantor</label>
                        <input type="text" name="guarantor" maxlength="150" value="{{ old('guarantor') }}"
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Approved By</label>
                        <select name="approved_by_id" class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                            <option value="">-- None --</option>
                            @foreach ($approvers ?? [] as $approver)
                                <option value="{{ $approver->id }}" @selected((string) old('approved_by_id') === (string) $approver->id)>{{ $approver->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Purpose</label>
                    <textarea name="purpose" rows="2" class="w-full rounded border border-slate-300 px-3 py-2 text-sm">{{ old('purpose') }}</textarea>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Collateral</label>
                    <textarea name="collateral" rows="2" class="w-full rounded border border-slate-300 px-3 py-2 text-sm">{{ old('collateral') }}</textarea>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Notes</label>
                    <textarea name="notes" rows="2" class="w-full rounded border border-slate-300 px-3 py-2 text-sm">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t px-5 py-4">
                <button type="button" onclick="closeLoanModal('modal-add-loan')"
                    class="rounded border border-slate-300 px-4 py-2 text-sm hover:bg-slate-50">Cancel</button>
                <button type="submit" class="rounded bg-brand-600 px-4 py-2 text-sm text-white hover:bg-brand-700">Save Loan</button>
            </div>
        </form>
    </div>
</div>

<script>
    window.loanVirtualAccounts = @json(($virtualAccounts ?? collect())->values());
    window.loanEmployees = @json(($employees ?? collect())->map(fn ($e) => ['id' => $e->id, 'company_id' => $e->company_id])->values());

    (function () {
        const typeEl = document.getElementById('loan_type');
        const companyEl = document.getElementById('loan_company_id');
        const counterpartyEl = document.getElementById('loan_counterparty_id');
        const sourceBankEl = document.getElementById('loan_source_bank_id');
        const bankEl = document.getElementById('loan_bank_id');
        const employeeEl = document.getElementById('loan_employee_id');
        const companyLabel = document.getElementById('company_label');

        function fillBanks(select, companyId, selected) {
            if (!select) return;
            const accounts = (window.loanVirtualAccounts || []).filter(a => String(a.company_id) === String(companyId || ''));
            select.innerHTML = '<option value="">-- Select bank --</option>';
            accounts.forEach(a => {
                const opt = document.createElement('option');
                opt.value = a.id;
                opt.textContent = `${a.bank_name} - ${a.account_name} (${a.account_number})`;
                if (String(selected) === String(a.id)) opt.selected = true;
                select.appendChild(opt);
            });
        }

        function filterEmployees() {
            if (!employeeEl || !companyEl) return;
            const companyId = companyEl.value;
            Array.from(employeeEl.options).forEach((opt, i) => {
                if (i === 0) return;
                opt.hidden = companyId && String(opt.dataset.company) !== String(companyId);
            });
        }

        function syncLoanTypeFields() {
            const type = typeEl?.value || 'external_borrow';
            const isExternal = type === 'external_borrow';
            const isInter = type === 'intercompany';
            const isEmployee = type === 'employee';

            document.getElementById('field_lender')?.classList.toggle('hidden', !isExternal);
            document.getElementById('field_counterparty')?.classList.toggle('hidden', !isInter);
            document.getElementById('field_employee')?.classList.toggle('hidden', !isEmployee);
            document.getElementById('field_source_bank')?.classList.toggle('hidden', isExternal);
            document.getElementById('field_receiving_bank')?.classList.toggle('hidden', isEmployee);

            if (companyLabel) {
                companyLabel.textContent = isExternal ? 'Company (Borrower)' : (isInter ? 'Lending Company' : 'Company');
            }

            document.getElementById('loan_lender').required = isExternal;
            if (counterpartyEl) counterpartyEl.required = isInter;
            if (employeeEl) employeeEl.required = isEmployee;
            if (sourceBankEl) sourceBankEl.required = isInter || isEmployee;
            if (bankEl) bankEl.required = isExternal || isInter;

            fillBanks(sourceBankEl, companyEl?.value, sourceBankEl?.value);
            fillBanks(bankEl, isInter ? counterpartyEl?.value : companyEl?.value, bankEl?.value);
            filterEmployees();
        }

        typeEl?.addEventListener('change', syncLoanTypeFields);
        companyEl?.addEventListener('change', syncLoanTypeFields);
        counterpartyEl?.addEventListener('change', syncLoanTypeFields);
        document.addEventListener('DOMContentLoaded', syncLoanTypeFields);
        syncLoanTypeFields();
    })();
</script>
