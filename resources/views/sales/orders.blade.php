<div id="tab-orders" class="tab-content hidden">
    <div class="flex flex-col gap-3 mb-6 lg:flex-row lg:items-center lg:justify-between">
        <h2 class="text-lg font-semibold font-display">Orders</h2>
        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto lg:items-center">
            <button onclick="openAddOrderModal()"
                class="w-full sm:w-auto flex-shrink-0 whitespace-nowrap px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium transition-colors">Add
                Order</button>
        </div>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Order No.</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Customer</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Description</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Total</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Date</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Status</th>
                    </tr>
                </thead>
                <tbody id="ordersTable" class="divide-y divide-slate-100">
                    <tr>
                        <td class="px-4 py-3">PO-2026-001</td>
                        <td class="px-4 py-3">Alice Johnson</td>
                        <td class="px-4 py-3">Office chairs (50 units)</td>
                        <td class="px-4 py-3">$3,250.00</td>
                        <td class="px-4 py-3">2026-04-28</td>
                        <td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs bg-green-50 text-green-700">Fulfilled</span></td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3">SO-2026-045</td>
                        <td class="px-4 py-3">Bright Solutions</td>
                        <td class="px-4 py-3">Monthly subscription - Pro</td>
                        <td class="px-4 py-3">$240.00</td>
                        <td class="px-4 py-3">2026-05-01</td>
                        <td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs bg-yellow-50 text-yellow-700">Processing</span></td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3">SO-2026-060</td>
                        <td class="px-4 py-3">Cedar Industries</td>
                        <td class="px-4 py-3">Replacement filters</td>
                        <td class="px-4 py-3">$120.00</td>
                        <td class="px-4 py-3">2026-05-06</td>
                        <td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs bg-red-50 text-red-700">Cancelled</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
