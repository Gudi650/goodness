<div id="tab-customers" class="tab-content">
    <div class="flex flex-col gap-3 mb-6 lg:flex-row lg:items-center lg:justify-between">
        <h2 class="text-lg font-semibold font-display">Customers</h2>
        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto lg:items-center">
            <button onclick="openAddCustomerModal()"
                class="w-full sm:w-auto flex-shrink-0 whitespace-nowrap px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium transition-colors">Add
                Customer</button>
        </div>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Customer Name</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Company</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Phone</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Email</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Assigned To</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Status</th>
                    </tr>
                </thead>
                <tbody id="customersTable" class="divide-y divide-slate-100">
                    <tr>
                        <td class="px-4 py-3">Alice Johnson</td>
                        <td class="px-4 py-3">Acme Corp</td>
                        <td class="px-4 py-3">+1 (555) 123-4567</td>
                        <td class="px-4 py-3">alice.johnson@acme.example</td>
                        <td class="px-4 py-3">Samuel Lee</td>
                        <td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs bg-green-50 text-green-700">Active</span></td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3">Bright Solutions</td>
                        <td class="px-4 py-3">Bright Solutions</td>
                        <td class="px-4 py-3">+1 (555) 987-6543</td>
                        <td class="px-4 py-3">info@brightsolutions.example</td>
                        <td class="px-4 py-3">Marta Ruiz</td>
                        <td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs bg-yellow-50 text-yellow-700">Pending</span></td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3">Cedar Industries</td>
                        <td class="px-4 py-3">Cedar Industries</td>
                        <td class="px-4 py-3">+1 (555) 222-3344</td>
                        <td class="px-4 py-3">sales@cedar.example</td>
                        <td class="px-4 py-3">Daniel Okoro</td>
                        <td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs bg-red-50 text-red-700">Inactive</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
