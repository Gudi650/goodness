<div id="dividendsPane">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Dividend ID</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Shareholder</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Company</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Amount</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Ownership %</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-center">Status</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">

                <!-- Example Row -->
                <tr>
                    <td class="px-4 py-3 text-sm text-slate-700">DIV-001</td>
                    <td class="px-4 py-3 text-sm text-slate-700">John Doe</td>
                    <td class="px-4 py-3 text-sm text-slate-700">Alpha Corp</td>
                    <td class="px-4 py-3 text-sm text-right text-slate-700">TZS 2,500,000</td>
                    <td class="px-4 py-3 text-sm text-right text-slate-700">25%</td>
                    <td class="px-4 py-3 text-sm text-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-50 text-green-700 font-medium text-xs">
                            Paid
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-center">
                        <button onclick="toggleDropdown('dividend-details-1')" class="text-slate-600 hover:text-slate-800 transition-colors">
                            
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </button>

                        <button class="text-blue-600 hover:text-blue-800 transition-colors ml-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m16.862 4.487 1.687-1.688a2.25 2.25 0 1 1 3.182 3.182L10.582 17.13a4.5 4.5 0 0 1-1.897 1.13L6 19l.74-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487ZM16.862 4.487 19.5 7.125" />
                            </svg>
                        </button>
                        <button class="text-red-600 hover:text-red-700 transition-colors ml-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.245-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0V4.875c0-1.035-.84-1.875-1.875-1.875h-3.75C9.09 3 8.25 3.84 8.25 4.875v.518" />
                            </svg>
                        </button>
                    </td>
                </tr>

                <!-- Dropdown row -->
                <tr id="dividend-details-1" class="hidden bg-slate-50/70">
                    <td colspan="7" class="px-4 py-4">
                        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                            <!-- Header -->
                            <div class="flex items-start justify-between gap-4 flex-wrap">
                                <div>
                                    <h4 class="text-sm font-semibold text-slate-900">Dividend: DIV-001</h4>
                                    <p class="text-xs text-slate-500 mt-1">Shareholder: John Doe</p>
                                    <p class="text-xs text-slate-500">Company: Alpha Corp</p>
                                </div>
                                <div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-50 text-green-700 font-medium text-xs">
                                        Paid
                                    </span>
                                </div>
                            </div>

                            <!-- Grid details -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4 text-sm">
                                <div class="rounded-lg bg-slate-50 p-3">
                                    <p class="text-xs uppercase tracking-wide text-slate-500">Dividend Info</p>
                                    <p class="mt-1 font-medium text-slate-900">Amount: TZS 2,500,000</p>
                                    <p class="text-slate-600">Ownership: 25%</p>
                                    <p class="text-slate-600">Equity Type: Common Stock</p>
                                </div>
                                <div class="rounded-lg bg-slate-50 p-3">
                                    <p class="text-xs uppercase tracking-wide text-slate-500">Dates</p>
                                    <p class="mt-1 text-slate-700">Declared: 2026-06-01</p>
                                    <p class="text-slate-700">Paid: 2026-06-15</p>
                                </div>
                                <div class="rounded-lg bg-slate-50 p-3">
                                    <p class="text-xs uppercase tracking-wide text-slate-500">Notes</p>
                                    <p class="mt-1 text-slate-700">Quarterly dividend payout for Q2 2026.</p>
                                </div>
                            </div>

                            <!-- Transactions -->
                            <div class="mt-4">
                                <p class="text-xs uppercase tracking-wide text-slate-500 mb-2">Payment Records</p>
                                <div class="overflow-x-auto border border-slate-200 rounded-lg">
                                    <table class="min-w-full text-sm">
                                        <thead class="bg-slate-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left font-medium text-slate-600">#</th>
                                                <th class="px-3 py-2 text-left font-medium text-slate-600">Description</th>
                                                <th class="px-3 py-2 text-right font-medium text-slate-600">Amount</th>
                                                <th class="px-3 py-2 text-right font-medium text-slate-600">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 bg-white">
                                            <tr>
                                                <td class="px-3 py-2 text-slate-500">1</td>
                                                <td class="px-3 py-2 text-slate-700">Dividend Payment</td>
                                                <td class="px-3 py-2 text-right text-slate-700">TZS 2,500,000</td>
                                                <td class="px-3 py-2 text-right text-slate-700">2026-06-15</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

<script>
function toggleDropdown(id) {
    const row = document.getElementById(id);
    row.classList.toggle('hidden');
}
</script>
