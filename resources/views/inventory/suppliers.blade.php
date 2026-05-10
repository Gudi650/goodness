<div id="suppliersPane" class="tab-content">
    <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-lg font-semibold font-display">Suppliers</h2>
        <button onclick="resetSupplierForm(); openLocalModal('modalAddSupplier')"
            class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium transition-colors">
            Add Supplier
        </button>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
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
                    @forelse ($suppliers as $supplier)
                        @php($detailsId = 'supplier-' . $supplier->id)
                        <tr>
                            <td class="px-4 py-3 text-sm">{{ $supplier->supplier_id }}</td>
                            <td class="px-4 py-3 text-sm">{{ $supplier->supplier_name }}</td>
                            <td class="px-4 py-3 text-sm">{{ $supplier->phone_number ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $supplier->email ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-right">{{ $supplier->rating ? $supplier->rating . ' / 5' : '-' }}</td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex justify-end items-center gap-2 whitespace-nowrap">

                                    <button type="button" title="View" onclick="toggleSupplierDetails('{{ $detailsId }}')" class="p-1.5 rounded-md text-slate-600 hover:bg-slate-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </button>

                                    <button type="button" title="Edit" onclick="editSupplier({{ json_encode($supplier->toArray()) }})" class="p-1.5 rounded-md text-blue-600 hover:bg-blue-50">
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
                        <tr id="details-{{ $detailsId }}" class="hidden bg-slate-50/40">
                            <td colspan="6" class="px-4 py-3">
                                <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                                    <div class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Supplier Details</div>
                                    <div class="grid grid-cols-1 gap-3 text-sm md:grid-cols-2 lg:grid-cols-3">
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Supplier ID:</span> <span class="font-medium text-slate-800">{{ $supplier->supplier_id ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Supplier Name:</span> <span class="font-medium text-slate-800">{{ $supplier->supplier_name ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Supplier Type:</span> <span class="font-medium text-slate-800">{{ $supplier->supplier_type ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Company:</span> <span class="font-medium text-slate-800">{{ optional($supplier->company)->name ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Registration Number / TIN:</span> <span class="font-medium text-slate-800">{{ $supplier->registration_number ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Status:</span> <span class="font-medium text-slate-800">{{ $supplier->status ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Contact Person:</span> <span class="font-medium text-slate-800">{{ $supplier->contact_person_name ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Phone:</span> <span class="font-medium text-slate-800">{{ $supplier->phone_number ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Alternative Phone:</span> <span class="font-medium text-slate-800">{{ $supplier->alternative_phone_number ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Email:</span> <span class="font-medium text-slate-800">{{ $supplier->email ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Website:</span> <span class="font-medium text-slate-800">{{ $supplier->website ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Country:</span> <span class="font-medium text-slate-800">{{ $supplier->country ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Address:</span> <span class="font-medium text-slate-800">{{ $supplier->street_address ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Region / District:</span> <span class="font-medium text-slate-800">{{ $supplier->region ?? '-' }}{{ $supplier->district ? ' / ' . $supplier->district : '' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">P.O Box:</span> <span class="font-medium text-slate-800">{{ $supplier->po_box ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Category of Supply:</span> <span class="font-medium text-slate-800">{{ $supplier->categories_supplied ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Products Supplied:</span> <span class="font-medium text-slate-800">{{ $supplier->products_supplied ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Lead Time:</span> <span class="font-medium text-slate-800">{{ $supplier->lead_time ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Minimum Order Value:</span> <span class="font-medium text-slate-800">{{ $supplier->minimum_order_value !== null ? number_format((float) $supplier->minimum_order_value, 2) . ' TZS' : '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Payment Terms:</span> <span class="font-medium text-slate-800">{{ $supplier->payment_terms ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Bank Name:</span> <span class="font-medium text-slate-800">{{ $supplier->bank_name ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Account Name:</span> <span class="font-medium text-slate-800">{{ $supplier->account_name ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Account Number:</span> <span class="font-medium text-slate-800">{{ $supplier->account_number ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Branch Name:</span> <span class="font-medium text-slate-800">{{ $supplier->branch_name ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Mobile Money Number:</span> <span class="font-medium text-slate-800">{{ $supplier->mobile_money_number ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Preferred Payment Method:</span> <span class="font-medium text-slate-800">{{ $supplier->preferred_payment_method ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Rating:</span> <span class="font-medium text-slate-800">{{ $supplier->rating ? $supplier->rating . ' / 5' : '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Contract Start Date:</span> <span class="font-medium text-slate-800">{{ optional($supplier->contract_start_date)->format('Y-m-d') ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2"><span class="text-slate-500">Contract End Date:</span> <span class="font-medium text-slate-800">{{ optional($supplier->contract_end_date)->format('Y-m-d') ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2 md:col-span-2 lg:col-span-3"><span class="text-slate-500">Notes / Additional Info:</span> <span class="font-medium text-slate-800">{{ $supplier->notes ?? '-' }}</span></div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2 md:col-span-2 lg:col-span-3"><span class="text-slate-500">Business Registration Certificate:</span>
                                            @if ($supplier->business_registration_certificate_path)
                                                <div class="flex gap-2 mt-1">
                                                    <a class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-200 text-slate-700 rounded hover:bg-slate-300 text-xs font-medium" href="{{ asset('storage/' . $supplier->business_registration_certificate_path) }}" target="_blank" rel="noopener">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                        </svg>
                                                        View
                                                    </a>
                                                    <a class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-700 rounded hover:bg-blue-100 text-xs font-medium" href="{{ route('suppliers.downloadAttachment', ['supplier' => $supplier->id, 'type' => 'business_registration_certificate']) }}" download>
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                                                        </svg>
                                                        Download
                                                    </a>
                                                </div>
                                            @else
                                                <span class="font-medium text-slate-800">-</span>
                                            @endif
                                        </div>
                                        <div class="rounded-md bg-slate-50 px-3 py-2 md:col-span-2 lg:col-span-3"><span class="text-slate-500">TIN Certificate:</span>
                                            @if ($supplier->tin_certificate_path)
                                                <div class="flex gap-2 mt-1">
                                                    <a class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-200 text-slate-700 rounded hover:bg-slate-300 text-xs font-medium" href="{{ asset('storage/' . $supplier->tin_certificate_path) }}" target="_blank" rel="noopener">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                        </svg>
                                                        View
                                                    </a>
                                                    <a class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-700 rounded hover:bg-blue-100 text-xs font-medium" href="{{ route('suppliers.downloadAttachment', ['supplier' => $supplier->id, 'type' => 'tin_certificate']) }}" download>
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                                                        </svg>
                                                        Download
                                                    </a>
                                                </div>
                                            @else
                                                <span class="font-medium text-slate-800">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500">
                                No suppliers found. Click "Add Supplier" to create your first supplier.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
