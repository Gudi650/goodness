<div id="companySharesPane" >
    <div class="overflow-x-auto">
        <table class="min-w-full">
            
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Company</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Authorized Shares</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Issued Shares</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Remaining Shares</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Share Value (TZS)</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <!-- Example Row -->
                <tr>
                    <td class="px-4 py-3 text-sm text-slate-700">Alpha Corp</td>
                    <td class="px-4 py-3 text-sm text-right text-slate-700">100,000</td>
                    <td class="px-4 py-3 text-sm text-right text-slate-700">75,000</td>
                    <td class="px-4 py-3 text-sm text-right text-slate-700">25,000</td>
                    <td class="px-4 py-3 text-sm text-right text-slate-700">TZS 100</td>
                    <td class="px-4 py-3 text-sm text-center">
                        <button onclick="toggleDropdown('company-shares-details-1')" class="text-slate-600 hover:text-slate-800 transition-colors">View</button>
                        <button class="text-blue-600 hover:text-blue-800 transition-colors ml-2">Edit</button>
                        <button class="text-red-600 hover:text-red-700 transition-colors ml-2">Delete</button>
                    </td>
                </tr>

                <!-- Dropdown row -->
                <tr id="company-shares-details-1" class="hidden bg-slate-50/70">
                    <td colspan="6" class="px-4 py-4">
                        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                            <h4 class="text-sm font-semibold text-slate-900">Company Shares: Alpha Corp</h4>
                            <p class="text-xs text-slate-500 mt-1">Capital structure details</p>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4 text-sm">
                                <div class="rounded-lg bg-slate-50 p-3">
                                    <p class="text-xs uppercase tracking-wide text-slate-500">Authorized Shares</p>
                                    <p class="mt-1 font-medium text-slate-900">100,000</p>
                                </div>
                                <div class="rounded-lg bg-slate-50 p-3">
                                    <p class="text-xs uppercase tracking-wide text-slate-500">Issued Shares</p>
                                    <p class="mt-1 font-medium text-slate-900">75,000</p>
                                </div>
                                <div class="rounded-lg bg-slate-50 p-3">
                                    <p class="text-xs uppercase tracking-wide text-slate-500">Remaining Shares</p>
                                    <p class="mt-1 font-medium text-slate-900">25,000</p>
                                </div>
                                <div class="rounded-lg bg-slate-50 p-3 md:col-span-3">
                                    <p class="text-xs uppercase tracking-wide text-slate-500">Share Value</p>
                                    <p class="mt-1 font-medium text-slate-900">TZS 100 per share</p>
                                </div>
                                <div class="rounded-lg bg-slate-50 p-3 md:col-span-3">
                                    <p class="text-xs uppercase tracking-wide text-slate-500">Notes</p>
                                    <p class="mt-1 text-slate-700">Series A issuance, founders retain 25% unissued shares.</p>
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
