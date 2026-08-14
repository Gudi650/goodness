<div id="modal-add-loan" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 px-4">
    <div class="w-full max-w-2xl rounded-lg bg-white shadow-lg">
        <form action="{{ url('/loans') }}" method="POST">
            @csrf
            <div class="flex items-center justify-between border-b px-5 py-4">
                <h3 class="text-lg font-semibold">Add Loan</h3>
                <button type="button" onclick="closeLoanModal('modal-add-loan')" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <div class="max-h-[70vh] space-y-4 overflow-y-auto px-5 py-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Company</label>
                        <select name="company_id" required class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                            <option value="" disabled selected>Select company</option>
                            @foreach ($companies ?? [] as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Receiving Bank Account</label>
                        <select name="bank_id" class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                            <option value="" selected>-- Select Bank Account --</option>
                            @foreach ($virtualAccounts ?? [] as $account)
                                <option value="{{ $account->id }}" {{ old('bank_id') == $account->id ? 'selected' : '' }}>
                                    {{ $account->bank_name }} - {{ $account->account_name }} ({{ $account->account_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <p class="-mt-2 text-xs text-slate-400">The loan code (e.g. LN-2026-001) is assigned automatically when you save — no need to enter one.</p>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Lender</label>
                    <input type="text" name="lender" required maxlength="150" placeholder="e.g. CRDB Bank"
                        class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Principal (TZS)</label>
                        <input type="number" step="0.01" min="0" name="principal" required
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Interest Rate (% p.a.)</label>
                        <input type="number" step="0.01" min="0" max="100" name="interest_rate" required
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Interest Type</label>
                        <select name="interest_type" required class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                            <option value="Flat">Flat</option>
                            <option value="Reducing Balance">Reducing Balance</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Term (months)</label>
                        <input type="number" min="1" name="term_months" required
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Start Date</label>
                        <input type="date" name="start_date" required
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Maturity Date</label>
                        <input type="date" name="maturity_date" required
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Disbursement Date</label>
                        <input type="date" name="disbursement_date"
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Status</label>
                        <select name="status" required class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                            <option value="Active">Active</option>
                            <option value="Closed">Closed</option>
                            <option value="Overdue">Overdue</option>
                            <option value="Defaulted">Defaulted</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Guarantor</label>
                        <input type="text" name="guarantor" maxlength="150"
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Approved By</label>
                        <select name="approved_by_id" class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                            <option value="">-- None --</option>
                            @foreach ($approvers ?? [] as $approver)
                                <option value="{{ $approver->id }}">{{ $approver->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Purpose</label>
                    <textarea name="purpose" rows="2" class="w-full rounded border border-slate-300 px-3 py-2 text-sm"></textarea>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Collateral</label>
                    <textarea name="collateral" rows="2" class="w-full rounded border border-slate-300 px-3 py-2 text-sm"></textarea>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Notes</label>
                    <textarea name="notes" rows="2" class="w-full rounded border border-slate-300 px-3 py-2 text-sm"></textarea>
                </div>

                <p class="text-xs text-slate-400">The full repayment schedule (installments, interest split, due dates) is generated automatically from the terms above — no need to enter it manually.</p>
            </div>

            <div class="flex justify-end gap-3 border-t px-5 py-4">
                <button type="button" onclick="closeLoanModal('modal-add-loan')"
                    class="rounded border border-slate-300 px-4 py-2 text-sm hover:bg-slate-50">Cancel</button>
                <button type="submit" class="rounded bg-brand-600 px-4 py-2 text-sm text-white hover:bg-brand-700">Save Loan</button>
            </div>
        </form>
    </div>
</div>