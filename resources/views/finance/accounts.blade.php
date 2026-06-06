<div id="accountsPane" class="hidden">

    <div class="overflow-x-auto bg-white ">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-4 py-2 text-left">Account Name</th>
                    <th class="px-4 py-2 text-left">Account Number</th>
                    <th class="px-4 py-2 text-left">Company</th>
                    <th class="px-4 py-2 text-left">Currency</th>
                    <th class="px-4 py-2 text-left">Balance</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">

                <!-- Account Row -->
                <tr class="cursor-pointer" onclick="toggleDetails('acc1')">
                    <td class="px-4 py-2">Main Operating Account</td>
                    <td class="px-4 py-2">ACC-0001</td>
                    <td class="px-4 py-2">Goodness Group</td>
                    <td class="px-4 py-2">TZS</td>
                    <td class="px-4 py-2 font-semibold">85,000,000</td>
                    <td class="px-4 py-2">Active</td>
                    <td>
                        <div class="inline-flex items-center gap-6">

                            <button type="button" {{-- onclick="toggleExpenseDetails('expense-details-',this)" --}}
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
                         --}}

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
                <tr id="acc1" class="hidden bg-slate-50">
                    {{-- 
                    <td colspan="7" class="px-6 py-4">
                        <p><strong>Account Number:</strong> ACC-0001</p>
                        <p><strong>Card Number:</strong> 4111 1111 1111 1111</p>
                        <p><strong>Remaining Balance:</strong> TZS 85,000,000</p>
                        <p><strong>Description:</strong> Primary operating account for Goodness Group</p>
                        <div class="mt-3">
                            <a href="#" class="text-green-600 hover:underline">View Transactions</a>
                        </div>
                    </td>
                     --}}

                    <td colspan="9" class="px-4 py-4">
                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Account Number</p>
                                    <p class="mt-1 text-sm text-slate-700">xxxxxxxxxxxxx</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Card Number:</p>
                                    <p class="mt-1 text-sm text-slate-700">4111 1111 1111 1111</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Remaining Balance:</p>
                                    <p class="mt-1 text-sm text-slate-700">123456789</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Description :
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">Primary operating account for Goodness Group</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Incoming:</p>
                                    <p class="mt-1 text-sm text-slate-700">123456789</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Outgoing:</p>
                                    <p class="mt-1 text-sm text-slate-700">123456789</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Bank Statement:
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">Download</p>
                                </div>
                                
                                <div
                                    class="rounded-lg border border-slate-200 bg-white p-3 md:col-span-2 lg:col-span-4">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Attachment
                                    </p>
                                    {{-- 
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
                                                <a href="{{ route('expenses.download', ['expense' => $expense['id']]) }}">
                                                    Download Attachment
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        <p class="mt-1 text-sm text-slate-500">No attachment uploaded.</p>
                                    @endif
                                     --}}

                                     <p class="mt-1 text-sm text-slate-500">Attachment handling coming soon.</p>
                                </div>

                                
                            </div>
                        </td>
                </tr>

                <!-- Add more accounts in same pattern -->

            </tbody>
        </table>
    </div>


    <script>
        function toggleDetails(id) {
            const row = document.getElementById(id);
            row.classList.toggle('hidden');
        }
    </script>


</div>
