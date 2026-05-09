@php
    $totalProducts = $totalProducts ?? $products->total();
    $totalStockValue = $totalStockValue ?? $products->getCollection()->sum(fn ($product) => (float) $product->stock * (float) $product->selling_price);
    $lowStockCount = $lowStockCount ?? $products->getCollection()->filter(fn ($product) => (int) $product->stock <= (int) $product->reorder_level)->count();
    $expiringSoonCount = $expiringSoonCount ?? $products->getCollection()->filter(function ($product) {
        if (empty($product->expiry_date)) {
            return false;
        }

        $expiryDate = \Carbon\Carbon::parse($product->expiry_date);

        return $expiryDate->between(now(), now()->addDays(30));
    })->count();
@endphp

<div id="productsPane" class="tab-content">
    <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-lg font-semibold font-display">Products</h2>
        <button onclick="openLocalModal('modalAddProduct')"
            class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium transition-colors">
            Add Product
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg border border-slate-200 border-l-4 border-l-blue-500 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Total Products</p>
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($totalProducts) }}</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-blue-100">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5L9 3.5M21 7.5l9.5-3.5M21 7.5v11.25m0 0l-9.5 5.25M21 18.75l9.5 5.25M9 3.5L0 7.5m0 0v11.25M0 7.5l9 5.25m9-5.25v11.25m0 0l9.5 5.25M9 18.75l-9-5.25" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 border-l-4 border-l-green-500 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Total Stock Value</p>
                    <p class="text-2xl font-bold text-slate-800">TZS {{ number_format($totalStockValue, 2) }}</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-green-100">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0Z" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 border-l-4 border-l-amber-500 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Low Stock Items</p>
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($lowStockCount) }}</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-amber-100">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c.866-1.5 2.945-2.625 5.303-2.625s4.437 1.125 5.303 2.625M3.75 4.5h16.5M3.75 12h16.5m-16.5 7.5h16.5" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 border-l-4 border-l-red-500 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Expiring Soon</p>
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($expiringSoonCount) }}</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-red-100">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5-15a9 9 0 11-18 0 9 9 0 0118 0Z" />
                </svg>
            </div>
        </div>
    </div>

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
                    @forelse ($products as $product)
                        @php
                            $inventoryId = 'INV-' . str_pad((string) $product->id, 4, '0', STR_PAD_LEFT);
                            $stockValue = (float) $product->stock * (float) $product->selling_price;
                            $isLowStock = (int) $product->stock <= (int) $product->reorder_level;
                            $expiryDate = $product->expiry_date ? \Carbon\Carbon::parse($product->expiry_date) : null;
                            $isExpiringSoon = $expiryDate ? $expiryDate->between(now(), now()->addDays(30)) : false;

                            $stockBadgeClass = $isLowStock
                                ? 'bg-amber-100 text-amber-700'
                                : 'bg-green-100 text-green-700';

                            $status = $product->status ?: 'Active';
                            $statusClass = match ($status) {
                                'Inactive' => 'bg-slate-100 text-slate-700 border border-slate-200',
                                'Discontinued' => 'bg-red-100 text-red-700 border border-red-200',
                                'Out of Stock' => 'bg-amber-100 text-amber-700 border border-amber-200',
                                default => 'bg-green-100 text-green-700 border border-green-200',
                            };
                        @endphp
                        <tr>
                            <td class="px-4 py-3 text-sm">{{ $inventoryId }}</td>
                            <td class="px-4 py-3 text-sm">{{ $product->name }}</td>
                            <td class="px-4 py-3 text-sm font-mono text-xs">{{ $product->sku }}</td>
                            <td class="px-4 py-3 text-sm">{{ $product->company?->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm text-right">
                                {{ number_format((float) $product->stock) }}
                                <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium {{ $stockBadgeClass }}">{{ $isLowStock ? 'Low Stock' : 'Sufficient' }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-right">{{ number_format($stockValue, 2) }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">{{ $status }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex justify-end items-center gap-2 whitespace-nowrap">
                                    <button type="button" title="View" onclick="toggleProductDetails('{{ $product->id }}')" class="p-1.5 rounded-md text-slate-600 hover:bg-slate-100">
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
                        <tr id="details-{{ $product->id }}" class="hidden bg-slate-50/40">
                            <td colspan="8" class="px-4 py-3">
                                <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                                    <div class="mb-4 flex items-start justify-between">
                                        <div>
                                            <div class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Product Details</div>
                                            <p class="text-sm italic text-slate-600 mb-4">{{ $product->product_description ?? 'No product description provided.' }}</p>
                                        </div>
                                        <div class="flex-shrink-0 w-16 h-16 bg-slate-200 rounded-lg border border-slate-300 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-slate-400">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.5v2.25m0-4.5v2.25m9-9L3.75 7.5" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 gap-3 text-sm md:grid-cols-3">
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Category:</span> <span class="block font-medium text-slate-800">{{ $product->category ?? 'N/A' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Brand / Manufacturer:</span> <span class="block font-medium text-slate-800">{{ $product->brand ?? 'N/A' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Unit of Measure:</span> <span class="block font-medium text-slate-800">{{ $product->unit_of_measure ?? 'N/A' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Cost per Unit:</span> <span class="block font-medium text-slate-800">{{ number_format((float) $product->cost_per_unit, 2) }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Unit Selling Price:</span> <span class="block font-medium text-slate-800">{{ number_format((float) $product->selling_price, 2) }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Profit Margin %:</span> <span class="block font-medium text-green-700">{{ $product->profit_margin !== null ? number_format((float) $product->profit_margin, 2) . '%' : 'N/A' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Barcode:</span> <span class="block font-medium text-slate-800 font-mono text-xs">{{ $product->barcode ?? 'N/A' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Expiry Date:</span>
                                            <span class="block font-medium {{ $expiryDate && $expiryDate->isPast() ? 'text-red-700' : ($isExpiringSoon ? 'text-amber-700' : 'text-slate-800') }}">
                                                {{ $expiryDate ? $expiryDate->format('d M Y') : 'No Expiry' }}
                                            </span>
                                            @if ($expiryDate && $expiryDate->isPast())
                                                <span class="text-xs text-red-600 mt-1 block">Expired</span>
                                            @elseif ($isExpiringSoon)
                                                <span class="text-xs text-amber-600 mt-1 block">⚠️ Expiring Soon</span>
                                            @endif
                                        </div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Tax / VAT:</span> <span class="block font-medium text-slate-800">{{ $product->tax_vat ?? 'N/A' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Location:</span> <span class="block font-medium text-slate-800">{{ $product->location ?? 'N/A' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Reorder Level:</span> <span class="block font-medium text-slate-800">{{ number_format((int) $product->reorder_level) }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-xs text-slate-500">Supplier:</span> <span class="block font-medium text-slate-800">{{ $product->supplier_id ?? 'N/A' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2 md:col-span-2"><span class="text-xs text-slate-500">Last Restocked Date:</span> <span class="block font-medium text-slate-800">{{ $product->last_restocked_date ? \Carbon\Carbon::parse($product->last_restocked_date)->format('d M Y') : 'N/A' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2 md:col-span-3"><span class="text-xs text-slate-500">Last Stock Movement:</span> <span class="block font-medium text-slate-800">{{ $product->last_stock_movement ?? 'N/A' }}</span></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center">
                                <div class="mx-auto max-w-sm">
                                    <div class="mx-auto mb-3 w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.5v2.25m0-4.5v2.25m9-9L3.75 7.5" />
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-semibold text-slate-800">No products yet</h3>
                                    <p class="mt-1 text-sm text-slate-500">Add your first product to start managing inventory here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
            <div class="px-4 py-4 border-t border-slate-200 bg-slate-50">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
