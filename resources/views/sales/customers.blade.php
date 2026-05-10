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
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody id="customersTable" class="divide-y divide-slate-100">
                    @forelse ($customers as $customer)
                        @php
                            $status = $customer->status ?? 'Prospect';
                            $statusClasses = match ($status) {
                                'Active' => 'bg-green-50 text-green-700',
                                'Inactive' => 'bg-slate-100 text-slate-700',
                                'Blacklisted' => 'bg-red-50 text-red-700',
                                default => 'bg-yellow-50 text-yellow-700',
                            };
                        @endphp
                        <tr>
                            <td class="px-4 py-3">{{ $customer->customer_name }}</td>
                            <td class="px-4 py-3">{{ $customer->company?->name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $customer->phone_number }}</td>
                            <td class="px-4 py-3">{{ $customer->email ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $customer->assignedSalesRep?->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs {{ $statusClasses }}">{{ $status }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <button
                                        type="button"
                                        title="View customer"
                                        onclick="toggleCustomerDetails('{{ $customer->id }}', this)"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded border border-slate-200 text-slate-600 hover:bg-slate-50"
                                        aria-label="View customer details"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button
                                        type="button"
                                        title="Edit customer"
                                        onclick="openEditCustomerModal('{{ $customer->id }}')"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded border border-slate-200 text-blue-600 hover:bg-blue-50"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button
                                        type="button"
                                        title="Delete customer"
                                        onclick="confirmDeleteCustomer('{{ $customer->id }}', '{{ $customer->customer_name }}')"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded border border-slate-200 text-red-600 hover:bg-red-50"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-6 0h6" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr id="customer-details-{{ $customer->id }}" class="hidden bg-slate-50/60">
                            <td colspan="7" class="px-4 py-4">
                                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                                    <div class="rounded-lg border border-slate-200 bg-white p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Customer Code</p>
                                        <p class="mt-1 text-sm text-slate-700">{{ $customer->customer_code ?? '-' }}</p>
                                    </div>
                                    <div class="rounded-lg border border-slate-200 bg-white p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Customer Type</p>
                                        <p class="mt-1 text-sm text-slate-700">{{ $customer->customer_type ?? '-' }}</p>
                                    </div>
                                    <div class="rounded-lg border border-slate-200 bg-white p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">WhatsApp</p>
                                        <p class="mt-1 text-sm text-slate-700">{{ $customer->whatsapp_number ?? '-' }}</p>
                                    </div>
                                    <div class="rounded-lg border border-slate-200 bg-white p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Source</p>
                                        <p class="mt-1 text-sm text-slate-700">{{ $customer->customer_source ?? '-' }}</p>
                                    </div>
                                    <div class="rounded-lg border border-slate-200 bg-white p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Price Category</p>
                                        <p class="mt-1 text-sm text-slate-700">{{ $customer->price_category ?? '-' }}</p>
                                    </div>
                                    <div class="rounded-lg border border-slate-200 bg-white p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Payment Terms</p>
                                        <p class="mt-1 text-sm text-slate-700">{{ $customer->payment_terms ?? '-' }}</p>
                                    </div>
                                    <div class="rounded-lg border border-slate-200 bg-white p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Preferred Payment</p>
                                        <p class="mt-1 text-sm text-slate-700">{{ $customer->preferred_payment_method ?? '-' }}</p>
                                    </div>
                                    <div class="rounded-lg border border-slate-200 bg-white p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Credit Limit</p>
                                        <p class="mt-1 text-sm text-slate-700">{{ $customer->credit_limit !== null ? 'TZS ' . number_format((float) $customer->credit_limit, 2) : '-' }}</p>
                                    </div>
                                    <div class="rounded-lg border border-slate-200 bg-white p-3 md:col-span-2">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Location</p>
                                        <p class="mt-1 text-sm text-slate-700">{{ $customer->region ?? '-' }}{{ $customer->district ? ', ' . $customer->district : '' }}</p>
                                        <p class="text-sm text-slate-600">{{ $customer->street_address ?? '-' }}</p>
                                    </div>
                                    <div class="rounded-lg border border-slate-200 bg-white p-3 md:col-span-2">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Notes</p>
                                        <p class="mt-1 text-sm text-slate-700 whitespace-pre-line">{{ $customer->notes ?? 'No notes available.' }}</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-500">No customers found yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
