<div id="productsPane" class="tab-content">
    <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-lg font-semibold font-display">Products</h2>
        <button onclick="openLocalModal('modalAddProduct')"
            class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium transition-colors">
            Add Product
        </button>
    </div>

    <!-- Summary Cards -->
    {{-- 
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Card 1: Total Products -->
        <div class="bg-white rounded-lg border border-slate-200 border-l-4 border-l-blue-500 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Total Products</p>
                    <p class="text-2xl font-bold text-slate-800">3</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-blue-100">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5L9 3.5M21 7.5l9.5-3.5M21 7.5v11.25m0 0l-9.5 5.25M21 18.75l9.5 5.25M9 3.5L0 7.5m0 0v11.25M0 7.5l9 5.25m9-5.25v11.25m0 0l9.5 5.25M9 18.75l-9-5.25" />
                </svg>
            </div>
        </div>

        <!-- Card 2: Total Stock Value -->
        <div class="bg-white rounded-lg border border-slate-200 border-l-4 border-l-green-500 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Total Stock Value</p>
                    <p class="text-2xl font-bold text-slate-800">TZS 63,475.00</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-green-100">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0Z" />
                </svg>
            </div>
        </div>

        <!-- Card 3: Low Stock Items -->
        <div class="bg-white rounded-lg border border-slate-200 border-l-4 border-l-amber-500 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Low Stock Items</p>
                    <p class="text-2xl font-bold text-slate-800">1</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-amber-100">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c.866-1.5 2.945-2.625 5.303-2.625s4.437 1.125 5.303 2.625M3.75 4.5h16.5M3.75 12h16.5m-16.5 7.5h16.5" />
                </svg>
            </div>
        </div>

        <!-- Card 4: Expiring Soon -->
        <div class="bg-white rounded-lg border border-slate-200 border-l-4 border-l-red-500 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Expiring Soon</p>
                    <p class="text-2xl font-bold text-slate-800">1</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-red-100">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5-15a9 9 0 11-18 0 9 9 0 0118 0Z" />
                </svg>
            </div>
        </div>
    </div> --}}

    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Inventory ID</th>
                        <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Product Name</th>
                        <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">SKU</th>
                        <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Company</th>
                        <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Quantity in Stock</th>
                        <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Total Value</th>
                        <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Status</th>
                        <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr>
                        <td class="px-4 py-3 text-sm">INV-1001</td>
                        <td class="px-4 py-3 text-sm">Hydraulic Drill Bit</td>
                        <td class="px-4 py-3 text-sm">MIN-DRL-001</td>
                        <td class="px-4 py-3 text-sm">Goodness Mining</td>
                        <td class="px-4 py-3 text-sm text-right">120<span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">Sufficient</span></td>
                        <td class="px-4 py-3 text-sm text-right">54,000.00</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 border border-green-200">Active</span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex justify-end items-center gap-2 whitespace-nowrap">
                                <button type="button" title="View" onclick="toggleProductDetails('p1')" class="p-1.5 rounded-md text-slate-600 hover:bg-slate-100">
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
                    <tr id="details-p1" class="hidden bg-slate-50/40">
                        <td colspan="8" class="px-4 py-3">
                            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                                <div class="mb-4 flex items-start justify-between">
                                    <div>
                                        <div class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Product Details</div>
                                        <p class="text-sm italic text-slate-600 mb-4">Heavy-duty hydraulic drill bit designed for hard rock mining operations. Precision engineered for depth drilling.</p>
                                    </div>
                                    <div class="flex-shrink-0 w-16 h-16 bg-slate-200 rounded-lg border border-slate-300 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-slate-400">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.5v2.25m0-4.5v2.25m9-9L3.75 7.5" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-3 text-sm md:grid-cols-3">
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Category:</span> <span class="block font-medium text-slate-800">Mining Equipment</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Brand / Manufacturer:</span> <span class="block font-medium text-slate-800">Bosch Industrial</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Unit of Measure:</span> <span class="block font-medium text-slate-800">pieces</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Cost per Unit:</span> <span class="block font-medium text-slate-800">450.00</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Unit Selling Price:</span> <span class="block font-medium text-slate-800">625.00</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Profit Margin %:</span> <span class="block font-medium text-green-700">38.89%</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Barcode:</span> <span class="block font-medium text-slate-800 font-mono text-xs">HDB-2024-001285</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Expiry Date:</span> <span class="block font-medium text-slate-800">No Expiry</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Tax / VAT:</span> <span class="block font-medium text-slate-800">18% VAT</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Location:</span> <span class="block font-medium text-slate-800">Main Warehouse - Bin A5</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Reorder Level:</span> <span class="block font-medium text-slate-800">40</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Supplier:</span> <span class="block font-medium text-slate-800">SUP-001 (Bosch Ltd)</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2 md:col-span-2"><span class="text-xs text-slate-500">Last Restocked Date:</span> <span class="block font-medium text-slate-800">15 May 2026</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2 md:col-span-3"><span class="text-xs text-slate-500">Last Stock Movement:</span> <span class="block font-medium text-slate-800">Stock IN — 120 units on 15 May 2026 (Transfer from Manufacturing)</span></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-sm">INV-1002</td>
                        <td class="px-4 py-3 text-sm">Agro-Vet Antibiotic</td>
                        <td class="px-4 py-3 text-sm">AGV-MED-014</td>
                        <td class="px-4 py-3 text-sm">Goodness Agro Vet</td>
                        <td class="px-4 py-3 text-sm text-right">65<span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-700">Low Stock</span></td>
                        <td class="px-4 py-3 text-sm text-right">2,112.50</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700 border border-amber-200">Expiring Soon</span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex justify-end items-center gap-2 whitespace-nowrap">
                                <button type="button" title="View" onclick="toggleProductDetails('p2')" class="p-1.5 rounded-md text-slate-600 hover:bg-slate-100">
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
                    <tr id="details-p2" class="hidden bg-slate-50/40">
                        <td colspan="8" class="px-4 py-3">
                            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                                <div class="mb-4 flex items-start justify-between">
                                    <div>
                                        <div class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Product Details</div>
                                        <p class="text-sm italic text-slate-600 mb-4">Broad-spectrum antibiotic solution for livestock. Effective against common bacterial infections in cattle and poultry.</p>
                                    </div>
                                    <div class="flex-shrink-0 w-16 h-16 bg-slate-200 rounded-lg border border-slate-300 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-slate-400">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.5v2.25m0-4.5v2.25m9-9L3.75 7.5" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-3 text-sm md:grid-cols-3">
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Category:</span> <span class="block font-medium text-slate-800">Agro-Vet Medicine</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Brand / Manufacturer:</span> <span class="block font-medium text-slate-800">Unga Limited</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Unit of Measure:</span> <span class="block font-medium text-slate-800">liters</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Cost per Unit:</span> <span class="block font-medium text-slate-800">32.50</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Unit Selling Price:</span> <span class="block font-medium text-slate-800">45.00</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Profit Margin %:</span> <span class="block font-medium text-green-700">38.46%</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Barcode:</span> <span class="block font-medium text-slate-800 font-mono text-xs">AGV-2024-008541</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Expiry Date:</span> <span class="block font-medium text-red-700">20 June 2026</span><span class="text-xs text-red-600 mt-1 block">⚠️ Expiring Soon (13 days)</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Tax / VAT:</span> <span class="block font-medium text-slate-800">No VAT (Medicine)</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Location:</span> <span class="block font-medium text-slate-800">Site Store A - Refrigerated</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Reorder Level:</span> <span class="block font-medium text-slate-800">80</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Supplier:</span> <span class="block font-medium text-slate-800">SUP-002 (Unga Ltd)</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2 md:col-span-2"><span class="text-xs text-slate-500">Last Restocked Date:</span> <span class="block font-medium text-slate-800">02 May 2026</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2 md:col-span-3"><span class="text-xs text-slate-500">Last Stock Movement:</span> <span class="block font-medium text-slate-800">Stock OUT — 25 units on 06 May 2026 (Sale Order #SO-2026-1245)</span></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-sm">INV-1003</td>
                        <td class="px-4 py-3 text-sm">Hybrid Maize Seeds</td>
                        <td class="px-4 py-3 text-sm">SED-MAZ-210</td>
                        <td class="px-4 py-3 text-sm">Goodness Trading</td>
                        <td class="px-4 py-3 text-sm text-right">75<span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">Sufficient</span></td>
                        <td class="px-4 py-3 text-sm text-right">7,362.50</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 border border-green-200">Active</span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex justify-end items-center gap-2 whitespace-nowrap">
                                <button type="button" title="View" onclick="toggleProductDetails('p3')" class="p-1.5 rounded-md text-slate-600 hover:bg-slate-100">
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
                    <tr id="details-p3" class="hidden bg-slate-50/40">
                        <td colspan="8" class="px-4 py-3">
                            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                                <div class="mb-4 flex items-start justify-between">
                                    <div>
                                        <div class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Product Details</div>
                                        <p class="text-sm italic text-slate-600 mb-4">High-yielding hybrid maize variety. Disease-resistant with superior grain quality and drought tolerance for East African growing conditions.</p>
                                    </div>
                                    <div class="flex-shrink-0 w-16 h-16 bg-slate-200 rounded-lg border border-slate-300 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-slate-400">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.5v2.25m0-4.5v2.25m9-9L3.75 7.5" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-3 text-sm md:grid-cols-3">
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Category:</span> <span class="block font-medium text-slate-800">Seeds</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Brand / Manufacturer:</span> <span class="block font-medium text-slate-800">Yara Tanzania</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Unit of Measure:</span> <span class="block font-medium text-slate-800">kg</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Cost per Unit:</span> <span class="block font-medium text-slate-800">98.00</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Unit Selling Price:</span> <span class="block font-medium text-slate-800">130.00</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Profit Margin %:</span> <span class="block font-medium text-green-700">32.65%</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Barcode:</span> <span class="block font-medium text-slate-800 font-mono text-xs">SED-2024-012963</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Expiry Date:</span> <span class="block font-medium text-slate-800">31 Dec 2027</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Tax / VAT:</span> <span class="block font-medium text-slate-800">18% VAT</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Location:</span> <span class="block font-medium text-slate-800">Regional Warehouse - Dry Store</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Reorder Level:</span> <span class="block font-medium text-slate-800">25</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Supplier:</span> <span class="block font-medium text-slate-800">SUP-001 (Yara Tanzania)</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2 md:col-span-2"><span class="text-xs text-slate-500">Last Restocked Date:</span> <span class="block font-medium text-slate-800">08 May 2026</span></div>
                                    <div class="rounded-md bg-slate-50 px-3 py-2 md:col-span-3"><span class="text-xs text-slate-500">Last Stock Movement:</span> <span class="block font-medium text-slate-800">Stock IN — 200 kg on 08 May 2026 (Purchase Order #PO-2026-0845)</span></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
