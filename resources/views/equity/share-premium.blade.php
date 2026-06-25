{{-- Loading components for invoice actions
    <x-loading id="invoiceDeleteLoader" message="Deleting invoice..." :show="false" full-page="true" />
    <x-loading id="invoiceEditLoader" message="Updating invoice..." :show="false" full-page="true" />
--}}

{{-- Loading components for share premium actions --}}
<x-loading id="sharePremiumDeleteLoader" message="Deleting share premium record..." :show="false" full-page="true" />
<x-loading id="sharePremiumEditLoader" message="Updating share premium record..." :show="false" full-page="true" />

<div id="sharePremiumPane">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Record ID</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Shareholder</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Company</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Premium Amount</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Shares Issued</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-center">Status</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">

                <!-- Example Row -->
                <tr>
                    <td class="px-4 py-3 text-sm text-slate-700">SP-001</td>
                    <td class="px-4 py-3 text-sm text-slate-700">Jane Smith</td>
                    <td class="px-4 py-3 text-sm text-slate-700">Beta Ltd</td>
                    <td class="px-4 py-3 text-sm text-right text-slate-700">TZS 1,200,000</td>
                    <td class="px-4 py-3 text-sm text-right text-slate-700">5,000</td>
                    <td class="px-4 py-3 text-sm text-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-yellow-50 text-yellow-700 font-medium text-xs">
                            Pending
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-center">
                        <button onclick="toggleDropdown('share-premium-details-1')" class="text-slate-600 hover:text-slate-800 transition-colors">View</button>
                        <button class="text-blue-600 hover:text-blue-800 transition-colors ml-2">Edit</button>
                        <button class="text-red-600 hover:text-red-700 transition-colors ml-2">Delete</button>
                    </td>
                </tr>

                <!-- Dropdown row -->
                <tr id="share-premium-details-1" class="hidden bg-slate-50/70">
                    <td colspan="7" class="px-4 py-4">
                        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                            <!-- Header -->
                            <div class="flex items-start justify-between gap-4 flex-wrap">
                                <div>
                                    <h4 class="text-sm font-semibold text-slate-900">Share Premium Record: SP-001</h4>
                                    <p class="text-xs text-slate-500 mt-1">Shareholder: Jane Smith</p>
                                    <p class="text-xs text-slate-500">Company: Beta Ltd</p>
                                </div>
                                <div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-yellow-50 text-yellow-700 font-medium text-xs">
                                        Pending
                                    </span>
                                </div>
                            </div>

                            <!-- Grid details -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4 text-sm">
                                <div class="rounded-lg bg-slate-50 p-3">
                                    <p class="text-xs uppercase tracking-wide text-slate-500">Premium Info</p>
                                    <p class="mt-1 font-medium text-slate-900">Premium Amount: TZS 1,200,000</p>
                                    <p class="text-slate-600">Shares Issued: 5,000</p>
                                    <p class="text-slate-600">Equity Type: Preferred Stock</p>
                                </div>
                                <div class="rounded-lg bg-slate-50 p-3">
                                    <p class="text-xs uppercase tracking-wide text-slate-500">Dates</p>
                                    <p class="mt-1 text-slate-700">Recorded: 2026-06-10</p>
                                    <p class="text-slate-700">Approval Due: 2026-06-30</p>
                                </div>
                                <div class="rounded-lg bg-slate-50 p-3">
                                    <p class="text-xs uppercase tracking-wide text-slate-500">Notes</p>
                                    <p class="mt-1 text-slate-700">Premium applied for new issuance of preferred shares.</p>
                                </div>
                            </div>

                            <!-- Transactions -->
                            <div class="mt-4">
                                <p class="text-xs uppercase tracking-wide text-slate-500 mb-2">Premium Records</p>
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
                                                <td class="px-3 py-2 text-slate-700">Premium Payment</td>
                                                <td class="px-3 py-2 text-right text-slate-700">TZS 1,200,000</td>
                                                <td class="px-3 py-2 text-right text-slate-700">2026-06-10</td>
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
