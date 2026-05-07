<div id="addExpenseModal" class="hidden">
    <input type="hidden" id="expenseSubmitMode" value="submit">

        <section class="rounded-lg border border-slate-100 bg-white p-4 shadow-sm">
            <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-700">Expense Details</h3>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Expense Number</label>
                    <input type="text" id="expenseId" readonly placeholder="EXP-0001" class="w-full cursor-not-allowed rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-slate-500">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Expense Date</label>
                    <input type="date" id="expenseDate" class="w-full rounded-md border border-slate-200 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Company</label>
                    <select id="expenseCompany" class="w-full rounded-md border border-slate-200 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-brand-500">
                        <option value="">Select company...</option>
                        @if (isset($companies))
                            @foreach ($companies as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Department</label>
                    <select id="expenseDepartment" class="w-full rounded-md border border-slate-200 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-brand-500">
                        <option value="">Select department...</option>
                        <option value="HR">HR</option>
                        <option value="Finance">Finance</option>
                        <option value="Operations">Operations</option>
                        <option value="Sales">Sales</option>
                        <option value="IT">IT</option>
                        <option value="Administration">Administration</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Recorded By</label>
                    <select id="expenseRecordedBy" class="w-full rounded-md border border-slate-200 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-brand-500">
                        <option value="{{ auth()->user()?->id ?? '' }}">{{ auth()->user()?->name ?? 'Current user' }}</option>
                    </select>
                </div>
            </div>
        </section>

        <section class="rounded-lg border border-slate-100 bg-white p-4 shadow-sm">
            <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-700">Expense Classification</h3>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Category</label>
                    <select id="expenseCategory" class="w-full rounded-md border border-slate-200 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-brand-500">
                        <option value="">Select category...</option>
                        <option value="Operational">Operational</option>
                        <option value="Payroll">Payroll</option>
                        <option value="Travel">Travel</option>
                        <option value="Procurement">Procurement</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Maintenance">Maintenance</option>
                        <option value="Miscellaneous">Miscellaneous</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Sub-category</label>
                    <select id="expenseSubCategory" class="w-full rounded-md border border-slate-200 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-brand-500">
                        <option value="">Select sub-category...</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Payment Method</label>
                    <select id="expensePaymentMethod" class="w-full rounded-md border border-slate-200 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-brand-500">
                        <option value="">Select payment method...</option>
                        <option value="Cash">Cash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Mobile Money">Mobile Money</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Petty Cash">Petty Cash</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Reference Number</label>
                    <input type="text" id="expenseReference" placeholder="Receipt / Cheque / M-Pesa Txn ID" class="w-full rounded-md border border-slate-200 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-brand-500">
                </div>
            </div>
        </section>

        <section class="rounded-lg border border-slate-100 bg-white p-4 shadow-sm">
            <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-700">Amount</h3>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Amount (TZS)</label>
                    <input type="number" id="expenseAmount" min="0" step="0.01" placeholder="0.00" class="w-full rounded-md border border-slate-200 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-brand-500">
                </div>
                <div class="flex items-center justify-between rounded-md border border-slate-100 bg-slate-50 px-3 py-2">
                    <span class="text-sm font-medium text-slate-700">VAT Included</span>
                    <label class="inline-flex cursor-pointer items-center">
                        <input id="expenseVatIncluded" type="checkbox" class="peer sr-only">
                        <span class="relative h-6 w-11 rounded-full bg-slate-300 transition-colors peer-checked:bg-brand-600">
                            <span class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white transition-transform peer-checked:translate-x-5"></span>
                        </span>
                    </label>
                </div>
                <div id="expenseVatRateWrap" class="hidden">
                    <label class="mb-1 block text-sm font-medium text-slate-700">VAT Rate %</label>
                    <input type="number" id="expenseVatRate" min="0" step="0.01" value="18" class="w-full rounded-md border border-slate-200 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-brand-500">
                </div>
                <div id="expenseVatAmountWrap" class="hidden">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Computed VAT Amount</label>
                    <input type="text" id="expenseVatAmount" readonly value="0.00" class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-slate-700">
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Net Amount</label>
                    <input type="text" id="expenseNetAmount" readonly value="0.00" class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 font-semibold text-slate-700">
                </div>
            </div>
        </section>

        <section class="rounded-lg border border-slate-100 bg-white p-4 shadow-sm">
            <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-700">Attachment & Notes</h3>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Receipt / Document</label>
                    <input type="file" id="expenseAttachment" accept=".jpg,.jpeg,.png,.pdf" class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-brand-50 file:px-3 file:py-1.5 file:text-brand-700 hover:file:bg-brand-100">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">File Preview</label>
                    <div id="expenseFilePreview" class="flex min-h-[44px] items-center rounded-md border border-dashed border-slate-200 px-3 py-2 text-sm text-slate-500">No file selected</div>
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Notes / Description</label>
                    <textarea id="expenseDesc" rows="3" placeholder="Explain what this expense was for..." class="w-full rounded-md border border-slate-200 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-brand-500"></textarea>
                </div>
            </div>
        </section>

        <div class="flex flex-col gap-2 border-t border-slate-100 pt-2 sm:flex-row sm:justify-end">
            <button type="button" onclick="window.closeModal && window.closeModal()" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">Cancel</button>
            <button type="button" onclick="setExpenseSubmitMode('draft')" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">Save as Draft</button>
            <button type="button" onclick="setExpenseSubmitMode('submit')" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-emerald-700">Submit Expense</button>
        </div>
    </div>
