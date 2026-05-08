<div id="purchaseOrdersPane" class="tab-content">
    <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-lg font-semibold font-display">Purchase Orders</h2>
        <button onclick="openLocalModal('modalAddPO')"
            class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium transition-colors">
            Add Purchase Order
        </button>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">PO Number</th>
                        <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Supplier ID</th>
                        <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Order Date</th>
                        <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Expected Delivery Date</th>
                        <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Status</th>
                        <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Total Order Value</th>
                        <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Created By</th>
                        <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr>
                        <td class="px-4 py-3 text-sm">PO-001</td>
                        <td class="px-4 py-3 text-sm">SUP-001</td>
                        <td class="px-4 py-3 text-sm">2026-05-01</td>
                        <td class="px-4 py-3 text-sm">2026-05-08</td>
                        <td class="px-4 py-3 text-sm"><span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-700">Pending</span></td>
                        <td class="px-4 py-3 text-sm text-right">$1,200.00</td>
                        <td class="px-4 py-3 text-sm">EMP-001</td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex justify-end items-center gap-2 whitespace-nowrap">
                                <button type="button" title="View" onclick="togglePODetails('po1')" class="p-1.5 rounded-md text-slate-600 hover:bg-slate-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                                <button type="button" title="Edit" class="p-1.5 rounded-md text-blue-600 hover:bg-blue-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a2.25 2.25 0 1 1 3.182 3.182L10.582 17.13a4.5 4.5 0 0 1-1.897 1.13L6 19l.74-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487ZM16.862 4.487 19.5 7.125" />
                                    </svg>
                                </button>
                                <button type="button" title="Delete" class="p-1.5 rounded-md text-red-600 hover:bg-red-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.245-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0V4.875c0-1.035-.84-1.875-1.875-1.875h-3.75C9.09 3 8.25 3.84 8.25 4.875v.518" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr id="details-po1" class="hidden bg-slate-50/40">
                        <td colspan="8" class="px-4 py-3">
                            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                                <div class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Purchase Order Details</div>
                                <div class="grid grid-cols-1 gap-3 text-sm md:grid-cols-2 lg:grid-cols-3">
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">PO Number:</span> <span class="font-medium text-slate-800">PO-001</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Supplier ID:</span> <span class="font-medium text-slate-800">SUP-001</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Order Date:</span> <span class="font-medium text-slate-800">2026-05-01</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Expected Delivery Date:</span> <span class="font-medium text-slate-800">2026-05-08</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Status:</span> <span class="font-medium text-slate-800">Pending</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Total Order Value:</span> <span class="font-medium text-slate-800">$1,200.00</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Created By:</span> <span class="font-medium text-slate-800">EMP-001</span></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-sm">PO-002</td>
                        <td class="px-4 py-3 text-sm">SUP-002</td>
                        <td class="px-4 py-3 text-sm">2026-05-02</td>
                        <td class="px-4 py-3 text-sm">2026-05-10</td>
                        <td class="px-4 py-3 text-sm"><span class="inline-flex rounded-full bg-brand-100 px-2.5 py-1 text-xs font-medium text-brand-700">Approved</span></td>
                        <td class="px-4 py-3 text-sm text-right">$450.50</td>
                        <td class="px-4 py-3 text-sm">EMP-002</td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex justify-end items-center gap-2 whitespace-nowrap">
                                <button type="button" title="View" onclick="togglePODetails('po2')" class="p-1.5 rounded-md text-slate-600 hover:bg-slate-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                                <button type="button" title="Edit" class="p-1.5 rounded-md text-blue-600 hover:bg-blue-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a2.25 2.25 0 1 1 3.182 3.182L10.582 17.13a4.5 4.5 0 0 1-1.897 1.13L6 19l.74-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487ZM16.862 4.487 19.5 7.125" />
                                    </svg>
                                </button>
                                <button type="button" title="Delete" class="p-1.5 rounded-md text-red-600 hover:bg-red-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.245-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0V4.875c0-1.035-.84-1.875-1.875-1.875h-3.75C9.09 3 8.25 3.84 8.25 4.875v.518" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr id="details-po2" class="hidden bg-slate-50/40">
                        <td colspan="8" class="px-4 py-3">
                            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                                <div class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Purchase Order Details</div>
                                <div class="grid grid-cols-1 gap-3 text-sm md:grid-cols-2 lg:grid-cols-3">
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">PO Number:</span> <span class="font-medium text-slate-800">PO-002</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Supplier ID:</span> <span class="font-medium text-slate-800">SUP-002</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Order Date:</span> <span class="font-medium text-slate-800">2026-05-02</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Expected Delivery Date:</span> <span class="font-medium text-slate-800">2026-05-10</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Status:</span> <span class="font-medium text-slate-800">Approved</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Total Order Value:</span> <span class="font-medium text-slate-800">450.50</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Created By:</span> <span class="font-medium text-slate-800">EMP-002</span></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
