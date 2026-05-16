<div id="expensesPane" class="hidden">
    <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-lg font-semibold font-display">Expenses</h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
        <div class="bg-white rounded-lg border border-slate-200 border-l-4 border-l-blue-500 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Total Expenses</p>
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($totalExpensesCount ?? 0) }}</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-blue-100">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0Z" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 border-l-4 border-l-green-500 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Total Amount</p>
                    <p class="text-2xl font-bold text-slate-800">TZS {{ number_format($totalExpensesAmount ?? 0, 2) }}</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-green-100">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0Z" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 border-l-4 border-l-amber-500 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Pending Approvals</p>
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($pendingApprovals ?? 0) }}</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-amber-100">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c.866-1.5 2.945-2.625 5.303-2.625s4.437 1.125 5.303 2.625M3.75 4.5h16.5M3.75 12h16.5m-16.5 7.5h16.5" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 border-l-4 border-l-slate-500 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Drafted</p>
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($draftedCount ?? 0) }}</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-slate-100">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h8m-8 6h16" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 border-l-4 border-l-indigo-500 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Issued</p>
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($issuedCount ?? 0) }}</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-indigo-100">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 border-l-4 border-l-emerald-500 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Approved</p>
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($approvedCount ?? 0) }}</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-emerald-100">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
                </svg>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">ID</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Date</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Company</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Department</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Category</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Amount</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Status</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Description</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($expenses as $expense)
                    <tr class="align-top">
                        <td class="px-4 py-3 text-sm">{{ $expense['display_id'] }}</td>
                        <td class="px-4 py-3 text-sm">{{ $expense['expense_date'] }}</td>
                        <td class="px-4 py-3 text-sm">{{ $expense['company_name'] }}</td>
                        <td class="px-4 py-3 text-sm">{{ $expense['department_name'] }}</td>
                        <td class="px-4 py-3 text-sm">{{ $expense['category'] }}</td>
                        <td class="px-4 py-3 text-sm text-right mono">TZS {{ number_format($expense['amount']) }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span
                                class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $expense['status'] === 'draft' ? 'bg-slate-100 text-slate-700' : 'bg-emerald-50 text-emerald-700' }}">
                                {{ ucfirst($expense['status']) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $expense['description'] }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex items-center gap-6">

                                <button type="button"
                                    onclick="toggleExpenseDetails('expense-details-{{ $expense['id'] }}', this)"
                                    class="text-slate-600 hover:text-slate-800 transition-colors" title="Show details"
                                    aria-label="Show details">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>

                                {{-- 
                                    now here check if this has to be visible by the following rules
                                    1. if issued then the button shouldnt be visible to anyone
                                    2. if user is neither admin,CEO,HR manager nor manager then the button shouldnt be visible
                                    3. if manager or HR manager then the button should only be visible if the expense belongs to their company
                                    4. if user is manager then the approve button shouldnt be visible if the expense is not checked yet, only after its checked by the HR manager
                                    5. if user is HR manager then the approve button should only be visible if the expense is not approved yet, only after its checked by the manager
                                    6. if user is CEO then the approve button should be visible if the expense is not approved yet, only after its checked by the manager

                                    Then the best way to implement this is to do the checks in the controller and pass a variable to the view indicating whether the approve button should be shown or not, and then in the view just check that variable to decide whether to show the button or not
                                --}}

                                @if($expense['can_approve'])

                                <form method="POST" action="{{ route('expenses.approve', ['expense' => $expense['id']]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" onclick="showExpenseActionLoader()" class="text-blue-600 hover:text-blue-800 transition-colors"
                                        title="Approve expense" aria-label="Approve expense">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2z" />
                                        </svg>
                                    </button>
                                </form>
                                @endif


                                <form id="delete-expense-form-{{ $expense['id'] }}" method="POST"
                                    action="{{ route('expenses.destroy', ['expense' => $expense['id']]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        onclick="confirmExpenseDelete({{ $expense['id'] }}, @js($expense['display_id']))"
                                        class="text-red-600 hover:text-red-700 transition-colors" title="Delete expense"
                                        aria-label="Delete expense">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.245-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0V4.875c0-1.035-.84-1.875-1.875-1.875h-3.75C9.09 3 8.25 3.84 8.25 4.875v.518" />
                                        </svg>
                                    </button>
                                </form>

                                <button type="button" class="text-blue-600 hover:text-blue-800 transition-colors"
                                    title="Edit expense" aria-label="Edit expense">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a2.25 2.25 0 1 1 3.182 3.182L10.582 17.13a4.5 4.5 0 0 1-1.897 1.13L6 19l.74-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487ZM16.862 4.487 19.5 7.125" />
                                    </svg>
                                </button>

                            </div>
                        </td>
                    </tr>
                    <tr id="expense-details-{{ $expense['id'] }}" class="hidden bg-slate-50/60">
                        <td colspan="9" class="px-4 py-4">
                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Payment
                                        Method</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $expense['payment_method'] }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Reference
                                        Number</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $expense['reference_number'] }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Sub-category
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $expense['sub_category'] }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Recorded By
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $expense['creator_name'] }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Approved By</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $expense['approved_by_name'] }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Issued By</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $expense['issued_by_name'] }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Checked By</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $expense['checked_by_name'] }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Gross
                                        Amount
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700 mono">TZS
                                        {{ number_format($expense['gross_amount']) }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">VAT
                                        Included
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ $expense['vat_included'] ? 'Yes' : 'No' }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">VAT Amount
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700 mono">TZS
                                        {{ number_format($expense['vat_amount']) }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Submitted
                                        At
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $expense['submitted_at'] }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Approved At</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $expense['approved_at'] }}</p>
                                </div>
                                <div
                                    class="rounded-lg border border-slate-200 bg-white p-3 md:col-span-2 lg:col-span-4">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Notes</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $expense['notes'] }}</p>
                                </div>
                                <div
                                    class="rounded-lg border border-slate-200 bg-white p-3 md:col-span-2 lg:col-span-4">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Attachment
                                    </p>
                                    @if ($expense['attachment_url'])
                                        <div class="mt-2">
                                            @if ($expense['attachment_is_image'])
                                                <a href="{{ $expense['attachment_url'] }}" target="_blank"
                                                    rel="noopener" onclick="showExpenseDownloadLoader()"
                                                    class="inline-flex items-center gap-3 rounded-lg border border-slate-200 p-2 hover:border-brand-400">
                                                    <img src="{{ $expense['attachment_url'] }}"
                                                        alt="Expense attachment"
                                                        class="h-20 w-20 rounded-md object-cover">
                                                    <span class="text-sm font-medium text-slate-700">View
                                                        attachment</span>
                                                </a>
                                            @else
                                                <a href="{{ $expense['attachment_url'] }}" target="_blank"
                                                    rel="noopener" onclick="showExpenseDownloadLoader()"
                                                    class="inline-flex items-center gap-2 rounded-md border border-slate-200 px-3 py-2 text-sm font-medium text-brand-700 hover:border-brand-400 hover:text-brand-800">
                                                    View or download attachment
                                                </a>

                                                <!--download button-->
                                                <a href="{{ route('expenses.download', $expense['id']) }}">
                                                    Download Attachment
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        <p class="mt-1 text-sm text-slate-500">No attachment uploaded.</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-6 text-sm text-slate-500 text-center">No expenses yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<x-loading id="expenseActionLoader" fullPage="true" class="hidden" />

<script>
    function showExpenseActionLoader() {
        const loader = document.getElementById('expenseActionLoader');
        if (loader) {
            loader.classList.remove('hidden');
        }
    }

    function showExpenseDownloadLoader() {
        showExpenseActionLoader();
        window.setTimeout(() => {
            const loader = document.getElementById('expenseActionLoader');
            if (loader) {
                loader.classList.add('hidden');
            }
        }, 1200);
    }

    function confirmExpenseDelete(expenseId, expenseNumber) {
        if (typeof window.openConfirm !== 'function') {
            return;
        }

        window.openConfirm({
            title: 'Delete expense',
            message: `Are you sure you want to delete expense ${expenseNumber}? This cannot be undone.`,
            confirmText: 'Delete',
            cancelText: 'Cancel',
            variant: 'warning',
            onConfirm: () => {
                showExpenseActionLoader();
                window.setTimeout(() => {
                    const form = document.getElementById(`delete-expense-form-${expenseId}`);
                    if (form) {
                        form.submit();
                    }
                }, 75);
            },
        });
    }
</script>
