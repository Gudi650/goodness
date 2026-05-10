<div id="tab-contracts" class="tab-content hidden">
    <div class="flex flex-col gap-3 mb-6 lg:flex-row lg:items-center lg:justify-between">
        <h2 class="text-lg font-semibold font-display">Contracts</h2>
        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto lg:items-center">
            <button onclick="openAddContractModal()"
                class="w-full sm:w-auto flex-shrink-0 whitespace-nowrap px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium transition-colors">Add
                Contract</button>
        </div>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Contract No.</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Client</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Value</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Start Date</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">End Date</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Status</th>
                    </tr>
                </thead>
                <tbody id="contractsTable" class="divide-y divide-slate-100">
                    <tr>
                        <td class="px-4 py-3">CTR-2026-1001</td>
                        <td class="px-4 py-3">Acme Corp</td>
                        <td class="px-4 py-3">$120,000</td>
                        <td class="px-4 py-3">2026-01-01</td>
                        <td class="px-4 py-3">2026-12-31</td>
                        <td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs bg-green-50 text-green-700">Active</span></td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3">CTR-2026-1005</td>
                        <td class="px-4 py-3">Bright Solutions</td>
                        <td class="px-4 py-3">$18,500</td>
                        <td class="px-4 py-3">2026-03-15</td>
                        <td class="px-4 py-3">2026-09-14</td>
                        <td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs bg-yellow-50 text-yellow-700">Expiring</span></td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3">CTR-2025-0902</td>
                        <td class="px-4 py-3">Cedar Industries</td>
                        <td class="px-4 py-3">$7,200</td>
                        <td class="px-4 py-3">2025-06-01</td>
                        <td class="px-4 py-3">2026-05-31</td>
                        <td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs bg-red-50 text-red-700">Expired</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
