<div id="equityPane">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Shareholder</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Company</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Equity Type</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Shares</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Ownership %</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-center">Status</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">

                <tr>
                    <td class="px-4 py-3 text-sm text-slate-700">John Doe</td>
                    <td class="px-4 py-3 text-sm text-slate-700">Alpha Corp</td>
                    <td class="px-4 py-3 text-sm text-slate-700">Common Stock</td>
                    <td class="px-4 py-3 text-sm text-right text-slate-700">10,000</td>
                    <td class="px-4 py-3 text-sm text-right text-slate-700">25%</td>
                    <td class="px-4 py-3 text-sm text-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                            Active
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-center">
                        <!-- View -->
                        <button onclick="toggleDropdown(this)" class="text-slate-600 hover:text-slate-800 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </button>
                        <!-- Edit -->
                        <button class="text-blue-600 hover:text-blue-800 transition-colors ml-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m16.862 4.487 1.687-1.688a2.25 2.25 0 1 1 3.182 3.182L10.582 17.13a4.5 4.5 0 0 1-1.897 1.13L6 19l.74-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487ZM16.862 4.487 19.5 7.125" />
                            </svg>
                        </button>
                        <!-- Delete -->
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
                <tr class="hidden">
                    <td colspan="7" class="px-4 py-3">
                        <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-4 text-sm text-slate-700">
                            <h3 class="font-semibold text-slate-800 mb-2">Equity Details</h3>
                            <ul class="space-y-1">
                                <li><strong>Shareholder:</strong> John Doe</li>
                                <li><strong>Company:</strong> Alpha Corp</li>
                                <li><strong>Equity Type:</strong> Common Stock</li>
                                <li><strong>Shares:</strong> 10,000</li>
                                <li><strong>Ownership:</strong> 25%</li>
                                <li><strong>Status:</strong> Active</li>
                            </ul>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

<script>
function toggleDropdown(button) {
    const row = button.closest('tr');
    const nextRow = row.nextElementSibling;
    nextRow.classList.toggle('hidden');
}
</script>
