<div id="addExpenseModal" class="hidden">
    <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4"
        onsubmit="showExpenseCreateLoader()">
        @csrf

        @php
            $currentUser = auth()->user();
            $currentCompanyId = $currentUser?->company_id;
            $currentCompanyName = $currentUser?->company?->name ?? 'Current company';
            $canChooseCompany = in_array($currentUser?->role?->name, ['Admin', 'CEO', 'Accountant']);
        @endphp

        <section class="rounded-lg border border-slate-100 bg-white p-4 shadow-sm">
            <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-700">Expense Details</h3>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Expense Number</label>
                    <input type="text" id="expenseId" name="expense_number" readonly placeholder="EXP-0001"
                        class="w-full cursor-not-allowed rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-slate-500">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Expense Date</label>
                    <input type="date" id="expenseDate" name="expense_date" required
                        class="w-full rounded-md border border-slate-200 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Company</label>

                    @if ($canChooseCompany)
                        <select id="expenseCompany" name="company_id" required
                            class="w-full rounded-md border border-slate-200 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-brand-500">
                            <option value="">Select company...</option>
                            @if (isset($companies))
                                @foreach ($companies as $id => $name)
                                    <option value="{{ $id }}" @selected((string) $currentCompanyId === (string) $id)>{{ $name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    @else
                        <input type="hidden" name="company_id" value="{{ $currentCompanyId }}">
                        <input type="text" value="{{ $currentCompanyName }}" readonly
                            class="w-full cursor-not-allowed rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-slate-600">
                    @endif

                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Bank </label>
                    <select id="expenseBank" name="bank_id" required
                        class="w-full rounded-md border border-slate-200 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-brand-500">
                        <option value="">Select bank...</option>
                        @foreach ($virtualAccounts as $account)
                            <option value="{{ $account['id'] }}" @selected(isset($currentBankId) && (string) $currentBankId === (string) $account['id'])>
                                {{ $account['account_name'] }}
                            </option>
                        @endforeach

                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Department</label>
                    <select id="expenseDepartment" name="department_id" required
                        class="w-full rounded-md border border-slate-200 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-brand-500">
                        <option value="">Select department...</option>

                        @if (isset($departments))
                            @foreach ($departments as $key => $department)
                                @if (is_object($department))
                                    <option value="{{ $department->id }}"
                                        data-company-id="{{ $department->company_id }}">{{ $department->name }}</option>
                                @else
                                    <option value="{{ $key }}">{{ $department }}</option>
                                @endif
                            @endforeach
                        @endif

                    </select>
                </div>
                <div class="md:col-span-2 hidden">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Recorded By</label>
                    <select id="expenseRecordedBy"
                        class="w-full rounded-md border border-slate-200 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-brand-500">
                        <option value="{{ auth()->user()?->id ?? '' }}">{{ auth()->user()?->name ?? 'Current user' }}
                        </option>
                    </select>
                </div>
            </div>
        </section>

        <section class="rounded-lg border border-slate-100 bg-white p-4 shadow-sm">
            <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-700">Expense Classification</h3>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Category</label>
                    <select id="expenseCategory" name="category" required
                        class="w-full rounded-md border border-slate-200 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-brand-500">
                        <option value="">Select category...</option>

                        @if (isset($itemsCategories))
                            @foreach ($itemsCategories as $category)
                                <option value="{{ $category['id'] }}">{{ $category['category_name'] }}
                                </option>
                            @endforeach
                        @endif

                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Sub-category</label>
                    <select id="expenseSubCategory" name="sub_category"
                        class="w-full rounded-md border border-slate-200 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-brand-500">
                        <option value="">Select sub-category...</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Payment Method</label>
                    <select id="expensePaymentMethod" name="payment_method" required
                        class="w-full rounded-md border border-slate-200 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-brand-500">
                        <option value="">Select payment method...</option>
                        <option value="Cash">Cash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Mobile Money">Mobile Money</option>
                        <option value="Cheque">Cheque</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Reference Number</label>
                    <input type="text" id="expenseReference" name="reference_number"
                        placeholder="Receipt / Cheque / M-Pesa Txn ID"
                        class="w-full rounded-md border border-slate-200 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-brand-500">
                </div>
                <!-- Long Term / Short Term -->
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Classification</label>
                    <select id="expenseTerm" name="term"
                        class="w-full rounded-md border border-slate-200 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-brand-500">
                        <option value="">Select classification...</option>
                        <option value="short_term">Short Term</option>
                        <option value="long_term">Long Term</option>
                    </select>
                </div>
            </div>
        </section>

        <section class="rounded-lg border border-slate-100 bg-white p-4 shadow-sm">
            <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-700">Amount</h3>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Amount (TZS)</label>
                    <input type="number" id="expenseAmount" name="amount" min="0" step="0.01" required
                        placeholder="0.00"
                        class="w-full rounded-md border border-slate-200 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-brand-500">
                </div>
                <div class="flex items-center justify-between rounded-md border border-slate-100 bg-slate-50 px-3 py-2">
                    <span class="text-sm font-medium text-slate-700">VAT Included</span>
                    <label class="inline-flex cursor-pointer items-center">
                        <input type="hidden" name="vat_included" value="0">
                        <input id="expenseVatIncluded" name="vat_included" value="1" type="checkbox"
                            class="peer sr-only">
                        <span
                            class="relative h-6 w-11 rounded-full bg-slate-300 transition-colors peer-checked:bg-brand-600">
                            <span
                                class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white transition-transform peer-checked:translate-x-5"></span>
                        </span>
                    </label>
                </div>
                <div id="expenseVatRateWrap" class="hidden">
                    <label class="mb-1 block text-sm font-medium text-slate-700">VAT Rate %</label>
                    <input type="number" id="expenseVatRate" name="vat_rate" min="0" step="0.01"
                        value="18"
                        class="w-full rounded-md border border-slate-200 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-brand-500">
                </div>
                <div id="expenseVatAmountWrap" class="hidden">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Computed VAT Amount</label>
                    <input type="text" id="expenseVatAmount" name="vat_amount" readonly value="0.00"
                        class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-slate-700">
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Net Amount</label>
                    <input type="text" id="expenseNetAmount" name="net_amount" readonly value="0.00"
                        class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 font-semibold text-slate-700">
                </div>
            </div>
        </section>

        <section class="rounded-lg border border-slate-100 bg-white p-4 shadow-sm">
            <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-700">Attachment & Notes</h3>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Receipt / Document</label>
                    <input type="file" id="expenseAttachment" name="attachment" accept=".jpg,.jpeg,.png,.pdf"
                        class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-brand-50 file:px-3 file:py-1.5 file:text-brand-700 hover:file:bg-brand-100">
                </div>
                {{-- 
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">File Preview</label>
                    <div id="expenseFilePreview" class="flex min-h-[44px] items-center rounded-md border border-dashed border-slate-200 px-3 py-2 text-sm text-slate-500">No file selected</div>
                </div>
                 --}}

                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Notes / Description</label>
                    <textarea id="expenseDesc" name="notes" rows="3" placeholder="Explain what this expense was for..."
                        class="w-full rounded-md border border-slate-200 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-brand-500"></textarea>
                </div>
            </div>
        </section>

        <div class="flex flex-col gap-2 border-t border-slate-100 pt-2 sm:flex-row sm:justify-end">
            <button type="button" onclick="window.closeModal && window.closeModal()"
                class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">Cancel</button>
            <button type="submit" name="submit_mode" value="draft"
                class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">Save
                as Draft</button>
            <!-- <button type="submit" name="submit_mode" value="submit" class="rounded-md bg-brand-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-brand-700">Submit Expense</button> -->
        </div>
    </form>
</div>

<x-loading id="expenseCreateLoader" fullPage="true" class="hidden" />

<script>
    function showExpenseCreateLoader() {
        const loader = document.getElementById('expenseCreateLoader');
        if (loader) {
            loader.classList.remove('hidden');
        }
    }
</script>
