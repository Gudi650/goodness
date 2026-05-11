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
                        <th class="px-4 py-3 text-right text-xs uppercase tracking-wider font-medium text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody id="ordersTable" class="divide-y divide-slate-100">
                    @forelse ($orders as $order)
                        @php
                            $firstItem = $order->items->first();
                            $itemCount = $order->items->count();
                            $description = $firstItem?->description
                                ?: $firstItem?->product?->name
                                ?: $order->order_type;
                            if ($itemCount > 0) {
                                $description .= ' (' . $itemCount . ' item' . ($itemCount === 1 ? '' : 's') . ')';
                            }

                            $statusClasses = match ($order->status) {
                                'Delivered' => 'bg-green-50 text-green-700',
                                'Processing', 'Confirmed', 'Ready for Delivery' => 'bg-yellow-50 text-yellow-700',
                                'Cancelled', 'Returned' => 'bg-red-50 text-red-700',
                                default => 'bg-slate-100 text-slate-600',
                            };
                        @endphp
                        <tr>
                            <td class="px-4 py-3 font-medium text-slate-800">{{ $order->order_number }}</td>
                            <td class="px-4 py-3">{{ $order->customer?->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $description }}</td>
                            <td class="px-4 py-3">{{ '$' . number_format((float) $order->grand_total, 2) }}</td>
                            <td class="px-4 py-3">{{ optional($order->order_date)->format('Y-m-d') }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs {{ $statusClasses }}">{{ $order->status }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex justify-end items-center gap-2 whitespace-nowrap">
                                    <button type="button" title="View" aria-label="View order"
                                        onclick="toggleOrderDetails({{ $order->id }})"
                                        class="p-1.5 rounded-md text-slate-600 hover:bg-slate-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </button>

                                    <button type="button" title="Edit" aria-label="Edit order"
                                        onclick="editOrder({{ $order->id }})"
                                        class="p-1.5 rounded-md text-blue-600 hover:bg-blue-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a2.25 2.25 0 1 1 3.182 3.182L10.582 17.13a4.5 4.5 0 0 1-1.897 1.13L6 19l.74-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487ZM16.862 4.487 19.5 7.125" />
                                        </svg>
                                    </button>

                                    <button type="button" title="Delete" aria-label="Delete order"
                                        onclick="confirmDeleteOrder({{ $order->id }}, @js($order->order_number))"
                                        class="p-1.5 rounded-md text-red-600 hover:bg-red-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.245-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0V4.875c0-1.035-.84-1.875-1.875-1.875h-3.75C9.09 3 8.25 3.84 8.25 4.875v.518" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr id="order-details-{{ $order->id }}" class="hidden bg-slate-50/40">
                            <td colspan="7" class="px-4 py-3">
                                <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                                    <div class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Order Details</div>
                                    <div class="grid grid-cols-1 gap-3 text-sm md:grid-cols-2 lg:grid-cols-3">
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Order Number:</span> <span class="font-medium text-slate-800">{{ $order->order_number }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Customer:</span> <span class="font-medium text-slate-800">{{ $order->customer?->name ?? 'N/A' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Sales Rep:</span> <span class="font-medium text-slate-800">{{ $order->salesRep?->name ?? 'N/A' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Payment Status:</span> <span class="font-medium text-slate-800">{{ $order->payment_status }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Delivery Status:</span> <span class="font-medium text-slate-800">{{ $order->delivery_status }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Company:</span> <span class="font-medium text-slate-800">{{ $order->company?->name ?? 'N/A' }}</span></div>
                                    </div>

                                    @if ($order->items->isNotEmpty())
                                        <div class="mt-4 overflow-x-auto">
                                            <table class="w-full text-sm">
                                                <thead class="bg-slate-50">
                                                    <tr>
                                                        <th class="px-3 py-2 text-left font-medium text-slate-500">Item</th>
                                                        <th class="px-3 py-2 text-left font-medium text-slate-500">Product</th>
                                                        <th class="px-3 py-2 text-left font-medium text-slate-500">Qty</th>
                                                        <th class="px-3 py-2 text-left font-medium text-slate-500">UoM</th>
                                                        <th class="px-3 py-2 text-left font-medium text-slate-500">Unit Price</th>
                                                        <th class="px-3 py-2 text-left font-medium text-slate-500">Line Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100">
                                                    @foreach ($order->items as $item)
                                                        <tr>
                                                            <td class="px-3 py-2 text-slate-700">{{ $item->item_number }}</td>
                                                            <td class="px-3 py-2 text-slate-700">{{ $item->product?->name ?? $item->description ?? 'N/A' }}</td>
                                                            <td class="px-3 py-2 text-slate-700">{{ $item->quantity }}</td>
                                                            <td class="px-3 py-2 text-slate-700">{{ $item->unit_of_measure }}</td>
                                                            <td class="px-3 py-2 text-slate-700">{{ number_format((float) $item->unit_price, 2) }}</td>
                                                            <td class="px-3 py-2 text-slate-700">{{ number_format((float) $item->line_total, 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-sm text-slate-500">
                                No orders found yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
