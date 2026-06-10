<div id="itemsPane" class="hidden">

    

    <!-- Category Filter -->
    <div class="bg-white border rounded shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Filter Items by Category</h2>
        <form class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700">Select Category</label>
                <select class="mt-1 block w-full border rounded px-3 py-2">
                    <option>All Categories</option>
                    <option>Office Supplies</option>
                    <option>Travel</option>
                    <option>Utilities</option>
                </select>
            </div>
            <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Apply
                Filter</button>
        </form>
    </div>

    <!-- Items Table -->
    <div class="bg-white border rounded shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Item Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Category</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Default Value</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Notes</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium">Printer Paper</td>
                    <td class="px-4 py-3">Office Supplies</td>
                    <td class="px-4 py-3 text-right mono">TZS 50,000</td>
                    <td class="px-4 py-3 text-slate-600">Used for printing invoices</td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center gap-2">
                            <button class="px-3 py-1 bg-yellow-500 text-white rounded text-xs">Edit</button>
                            <button class="px-3 py-1 bg-red-600 text-white rounded text-xs">Delete</button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium">Flight Ticket</td>
                    <td class="px-4 py-3">Travel</td>
                    <td class="px-4 py-3 text-right mono">TZS 1,200,000</td>
                    <td class="px-4 py-3 text-slate-600">Business trip to Nairobi</td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center gap-2">
                            <button class="px-3 py-1 bg-yellow-500 text-white rounded text-xs">Edit</button>
                            <button class="px-3 py-1 bg-red-600 text-white rounded text-xs">Delete</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
