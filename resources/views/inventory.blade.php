<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Inventory - Goodness Group</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fff8e5',
                            100: '#fde6a1',
                            500: '#f0b73a',
                            600: '#eaa106',
                            700: '#c88600'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: Inter, sans-serif
        }

        h1,
        h2,
        nav,
        button {
            font-family: Outfit, sans-serif
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800">
    @include('components.topbar')
    @include('components.sidebar')

    <main class="ml-0 lg:ml-64 pt-16 lg:pt-20 px-4 lg:p-6 min-h-screen">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Inventory & Procurement</h1>
            <p class="text-sm text-slate-500">Products, suppliers and purchase orders</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-lg p-4">
            <div class="flex flex-col gap-3 mb-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex flex-wrap gap-2">
                    <button id="tabProducts"
                        class="px-3 py-2 text-brand-700 border-b-2 border-brand-600">Products</button>
                    <button id="tabSuppliers" class="px-3 py-2 text-slate-600">Suppliers</button>
                    <button id="tabPO" class="px-3 py-2 text-slate-600">Purchase Orders</button>
                </div>
                <div id="actionButton" class="w-full lg:w-auto">
                    <div id="actionSuppliers" class="w-full lg:w-auto hidden">
                        <button onclick="openLocalModal('modalAddSupplier')"
                            class="w-full lg:w-auto flex-shrink-0 whitespace-nowrap px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium">Add
                            Supplier</button>
                    </div>
                    <div id="actionProducts" class="w-full lg:w-auto hidden">
                        <button onclick="openLocalModal('modalAddProduct')"
                            class="w-full lg:w-auto flex-shrink-0 whitespace-nowrap px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium">Add
                            Product</button>
                    </div>
                    <div id="actionPO" class="w-full lg:w-auto hidden">
                        <button onclick="openLocalModal('modalAddPO')"
                            class="w-full lg:w-auto flex-shrink-0 whitespace-nowrap px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium">Add
                            Purchase Order</button>
                    </div>
                </div>
            </div>
            <!-- Products (server-rendered/hard-coded HTML table) -->
            <div id="productsSection" class="inv-section">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Inventory ID</th>
                                <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Product Name</th>
                                <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">SKU</th>
                                <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Quantity in Stock</th>
                                <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Total Value</th>
                                <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="px-4 py-3 text-sm">INV-1001</td>
                                <td class="px-4 py-3 text-sm">Hydraulic Drill Bit</td>
                                <td class="px-4 py-3 text-sm">MIN-DRL-001</td>
                                <td class="px-4 py-3 text-sm text-right">120</td>
                                <td class="px-4 py-3 text-sm text-right">54,000.00</td>
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
                                <td colspan="6" class="px-4 py-3">
                                    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                                        <div class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Product Details</div>
                                        <div class="grid grid-cols-1 gap-3 text-sm md:grid-cols-3">
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Category:</span> <span class="font-medium text-slate-800">Mining Equipment</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Unit of Measure:</span> <span class="font-medium text-slate-800">pieces</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Location:</span> <span class="font-medium text-slate-800">Main Warehouse</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Reorder Level:</span> <span class="font-medium text-slate-800">40</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Supplier ID:</span> <span class="font-medium text-slate-800">SUP-001</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Cost per Unit:</span> <span class="font-medium text-slate-800">450.00</span></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm">INV-1002</td>
                                <td class="px-4 py-3 text-sm">Agro-Vet Antibiotic</td>
                                <td class="px-4 py-3 text-sm">AGV-MED-014</td>
                                <td class="px-4 py-3 text-sm text-right">250</td>
                                <td class="px-4 py-3 text-sm text-right">8,125.00</td>
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
                                <td colspan="6" class="px-4 py-3">
                                    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                                        <div class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Product Details</div>
                                        <div class="grid grid-cols-1 gap-3 text-sm md:grid-cols-3">
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Category:</span> <span class="font-medium text-slate-800">Agro-Vet Medicine</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Unit of Measure:</span> <span class="font-medium text-slate-800">liters</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Location:</span> <span class="font-medium text-slate-800">Site Store A</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Reorder Level:</span> <span class="font-medium text-slate-800">80</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Supplier ID:</span> <span class="font-medium text-slate-800">SUP-002</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Cost per Unit:</span> <span class="font-medium text-slate-800">32.50</span></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm">INV-1003</td>
                                <td class="px-4 py-3 text-sm">Hybrid Maize Seeds</td>
                                <td class="px-4 py-3 text-sm">SED-MAZ-210</td>
                                <td class="px-4 py-3 text-sm text-right">75</td>
                                <td class="px-4 py-3 text-sm text-right">1,350.00</td>
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
                                <td colspan="6" class="px-4 py-3">
                                    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                                        <div class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Product Details</div>
                                        <div class="grid grid-cols-1 gap-3 text-sm md:grid-cols-3">
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Category:</span> <span class="font-medium text-slate-800">Seeds</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Unit of Measure:</span> <span class="font-medium text-slate-800">kg</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Location:</span> <span class="font-medium text-slate-800">Regional Warehouse</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Reorder Level:</span> <span class="font-medium text-slate-800">25</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Supplier ID:</span> <span class="font-medium text-slate-800">SUP-001</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Cost per Unit:</span> <span class="font-medium text-slate-800">18.00</span></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Suppliers (server-rendered/hard-coded HTML) -->
            <div id="suppliersSection" class="inv-section hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Supplier ID</th>
                                <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Supplier Name</th>
                                <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Phone</th>
                                <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Email</th>
                                <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Rating / Reliability</th>
                                <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="px-4 py-3 text-sm">SUP-001</td>
                                <td class="px-4 py-3 text-sm">Mwamba Industrial Supplies</td>
                                <td class="px-4 py-3 text-sm">+255 742 100 001</td>
                                <td class="px-4 py-3 text-sm">mwamba@supplies.co.tz</td>
                                <td class="px-4 py-3 text-sm text-right">4.7 / 5</td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="flex justify-end items-center gap-2 whitespace-nowrap">
                                        <button type="button" title="View" onclick="toggleSupplierDetails('s1')" class="p-1.5 rounded-md text-slate-600 hover:bg-slate-100">
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
                            <tr id="details-s1" class="hidden bg-slate-50/40">
                                <td colspan="6" class="px-4 py-3">
                                    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                                        <div class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Supplier Details</div>
                                        <div class="grid grid-cols-1 gap-3 text-sm md:grid-cols-2 lg:grid-cols-3">
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Contact Person:</span> <span class="font-medium text-slate-800">John Mushi</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Phone:</span> <span class="font-medium text-slate-800">+255 742 100 001</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Email:</span> <span class="font-medium text-slate-800">mwamba@supplies.co.tz</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Address:</span> <span class="font-medium text-slate-800">Nyerere Rd, Dar es Salaam</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Category of Supply:</span> <span class="font-medium text-slate-800">Mining Tools</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Lead Time:</span> <span class="font-medium text-slate-800">5 days</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Payment Terms:</span> <span class="font-medium text-slate-800">30 days credit</span></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm">SUP-002</td>
                                <td class="px-4 py-3 text-sm">GreenField Agro Vet</td>
                                <td class="px-4 py-3 text-sm">+255 765 220 002</td>
                                <td class="px-4 py-3 text-sm">support@greenfield.co.tz</td>
                                <td class="px-4 py-3 text-sm text-right">4.9 / 5</td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="flex justify-end items-center gap-2 whitespace-nowrap">
                                        <button type="button" title="View" onclick="toggleSupplierDetails('s2')" class="p-1.5 rounded-md text-slate-600 hover:bg-slate-100">
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
                            <tr id="details-s2" class="hidden bg-slate-50/40">
                                <td colspan="6" class="px-4 py-3">
                                    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                                        <div class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Supplier Details</div>
                                        <div class="grid grid-cols-1 gap-3 text-sm md:grid-cols-2 lg:grid-cols-3">
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Contact Person:</span> <span class="font-medium text-slate-800">Jane Nanyaro</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Phone:</span> <span class="font-medium text-slate-800">+255 765 220 002</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Email:</span> <span class="font-medium text-slate-800">support@greenfield.co.tz</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Address:</span> <span class="font-medium text-slate-800">Mbezi Beach, Dar es Salaam</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Category of Supply:</span> <span class="font-medium text-slate-800">Agro-Vet Drugs</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Lead Time:</span> <span class="font-medium text-slate-800">3 days</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Payment Terms:</span> <span class="font-medium text-slate-800">50% upfront, 50% on delivery</span></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm">SUP-003</td>
                                <td class="px-4 py-3 text-sm">AgriPlus Inputs Ltd</td>
                                <td class="px-4 py-3 text-sm">+255 719 330 003</td>
                                <td class="px-4 py-3 text-sm">sales@agriplus.co.tz</td>
                                <td class="px-4 py-3 text-sm text-right">4.5 / 5</td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="flex justify-end items-center gap-2 whitespace-nowrap">
                                        <button type="button" title="View" onclick="toggleSupplierDetails('s3')" class="p-1.5 rounded-md text-slate-600 hover:bg-slate-100">
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
                            <tr id="details-s3" class="hidden bg-slate-50/40">
                                <td colspan="6" class="px-4 py-3">
                                    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                                        <div class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Supplier Details</div>
                                        <div class="grid grid-cols-1 gap-3 text-sm md:grid-cols-2 lg:grid-cols-3">
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Contact Person:</span> <span class="font-medium text-slate-800">Peter Kibona</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Phone:</span> <span class="font-medium text-slate-800">+255 719 330 003</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Email:</span> <span class="font-medium text-slate-800">sales@agriplus.co.tz</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Address:</span> <span class="font-medium text-slate-800">Arusha Industrial Area</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Category of Supply:</span> <span class="font-medium text-slate-800">Fertilizers</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Lead Time:</span> <span class="font-medium text-slate-800">7 days</span></div>
                                            <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Payment Terms:</span> <span class="font-medium text-slate-800">45 days credit</span></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Purchase Orders (server-rendered/hard-coded HTML) -->
            <div id="poSection" class="inv-section hidden">
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
    </main>

    <!-- Static Add Product Modal -->
    <div id="modalAddProduct" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40">
        <div class="bg-white rounded-lg max-w-lg w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium">Add Product</h3>
                <button onclick="closeLocalModal('modalAddProduct')"
                    class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form method="POST" action="#" onsubmit="submitAddProduct(event)">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Product Name</label>
                        <input type="text" name="name" placeholder="Widget C"
                            class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Category</label>
                        <input type="text" name="category" placeholder="Gadgets"
                            class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">SKU</label>
                        <input type="text" name="sku" placeholder="W-C"
                            class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Price</label>
                        <input type="number" step="0.01" name="price" placeholder="0.00"
                            class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Stock Quantity</label>
                        <input type="number" name="stock" placeholder="0"
                            class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" onclick="closeLocalModal('modalAddProduct')"
                        class="px-4 py-2 border rounded-md">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-brand-600 text-white rounded-md">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Static Add Supplier Modal -->
    <div id="modalAddSupplier" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40">
        <div class="bg-white rounded-lg max-w-lg w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium">Add Supplier</h3>
                <button onclick="closeLocalModal('modalAddSupplier')"
                    class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form method="POST" action="#" onsubmit="submitAddSupplier(event)">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Supplier Name</label>
                        <input type="text" name="name" placeholder="Supplier Inc"
                            class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Contact Person</label>
                        <input type="text" name="contact" placeholder="John Doe"
                            class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" placeholder="contact@supplier.com"
                            class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" onclick="closeLocalModal('modalAddSupplier')"
                        class="px-4 py-2 border rounded-md">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-brand-600 text-white rounded-md">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Static Add Purchase Order Modal -->
    <div id="modalAddPO" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40">
        <div class="bg-white rounded-lg max-w-lg w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium">Add Purchase Order</h3>
                <button onclick="closeLocalModal('modalAddPO')" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form method="POST" action="#" onsubmit="submitAddPO(event)">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">PO Number</label>
                        <input type="text" name="poNumber" placeholder="PO-001"
                            class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Supplier ID</label>
                        <input type="text" name="supplierId" placeholder="SUP-001"
                            class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Order Date</label>
                        <input type="date" name="orderDate"
                            class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Expected Delivery Date</label>
                        <input type="date" name="expectedDeliveryDate"
                            class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                            <option>Pending</option>
                            <option>Approved</option>
                            <option>Received</option>
                            <option>Partially Received</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Total Order Value</label>
                        <input type="number" step="0.01" name="amount" placeholder="0.00"
                            class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Created By</label>
                        <input type="text" name="createdBy" placeholder="EMP-001"
                            class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" onclick="closeLocalModal('modalAddPO')"
                        class="px-4 py-2 border rounded-md">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-brand-600 text-white rounded-md">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Minimal JS to handle tab and modal visibility. Tables and modal content are static HTML.
        function setActiveTab(activeId) {
            const tabs = ['tabProducts', 'tabSuppliers', 'tabPO'];
            tabs.forEach(id => {
                const tabBtn = document.getElementById(id);
                const section = document.getElementById(id.replace('tab', '').toLowerCase() + 'Section');
                const action = document.getElementById('action' + id.replace('tab', ''));
                if (!tabBtn || !section) return;
                if (id === activeId) {
                    tabBtn.classList.remove('text-slate-600');
                    tabBtn.classList.add('text-black', 'border-b-2', 'border-brand-600', 'font-bold');
                    section.classList.remove('hidden');
                    if (action) action.classList.remove('hidden');
                } else {
                    tabBtn.classList.remove('border-b-2', 'border-brand-600', 'font-bold');
                    tabBtn.classList.add('text-slate-600');
                    section.classList.add('hidden');
                    if (action) action.classList.add('hidden');
                }
            });
        }

        document.getElementById('tabProducts').addEventListener('click', () => setActiveTab('tabProducts'));
        document.getElementById('tabSuppliers').addEventListener('click', () => setActiveTab('tabSuppliers'));
        document.getElementById('tabPO').addEventListener('click', () => setActiveTab('tabPO'));

        // Local modal helpers (open/close) - avoid colliding with global openModal used by shared component
        function openLocalModal(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.classList.remove('hidden');
        }

        function closeLocalModal(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.classList.add('hidden');
        }

        function toggleProductDetails(productId) {
            const row = document.getElementById('details-' + productId);
            if (!row) return;
            row.classList.toggle('hidden');
        }

        function toggleSupplierDetails(supplierId) {
            const row = document.getElementById('details-' + supplierId);
            if (!row) return;
            row.classList.toggle('hidden');
        }

        function togglePODetails(poId) {
            const row = document.getElementById('details-' + poId);
            if (!row) return;
            row.classList.toggle('hidden');
        }

        // Example submit handlers that simply close the modal for this demo page
        function submitAddProduct(e) {
            e?.preventDefault();
            closeLocalModal('modalAddProduct');
            alert('Product added (demo)');
        }

        function submitAddSupplier(e) {
            e?.preventDefault();
            closeLocalModal('modalAddSupplier');
            alert('Supplier added (demo)');
        }

        function submitAddPO(e) {
            e?.preventDefault();
            closeLocalModal('modalAddPO');
            alert('Purchase order added (demo)');
        }

        // initialize default tab
        setActiveTab('tabProducts');
    </script>

    @include('components.modal')
    @include('components.alert')
    @include('components.confirm')
    
</body>

</html>
