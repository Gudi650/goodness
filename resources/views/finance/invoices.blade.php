{{-- Loading components for invoice actions --}}
<x-loading id="invoiceDeleteLoader" message="Deleting invoice..." :show="false" full-page="true" />
<x-loading id="invoiceEditLoader" message="Updating invoice..." :show="false" full-page="true" />

<div id="invoicesPane">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Invoice</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Company</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Invoice Type</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Amount</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-center">Status</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($invoices as $invoice)
                    <tr class="align-top">
                        <td class="px-4 py-3 text-sm font-medium">{{ $invoice['invoice_number'] }}</td>
                        <td class="px-4 py-3 text-sm">{{ $invoice['company_name'] ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm">{{ ucfirst($invoice['invoice_type'] ?? 'N/A') }}</td>
                        <td class="px-4 py-3 text-sm text-right mono">TZS {{ number_format($invoice['total_amount']) }}
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <span
                                class="inline-block px-2 py-1 {{ $invoice['status'] === 'paid' ? 'bg-brand-50 text-brand-700' : 'bg-slate-50 text-slate-700' }} rounded-md text-xs font-medium">{{ ucfirst($invoice['status']) }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <div class="flex items-center justify-center gap-6">
                                
                                <a href="{{ route('invoices.download', $invoice['id']) }}"
                                    class="text-emerald-600 hover:text-emerald-800 transition-colors"
                                    title="Download invoice PDF" aria-label="Download invoice PDF">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 3v12m0 0-4-4m4 4 4-4M4.5 20.25h15" />
                                    </svg>
                                </a>
                                <button type="button" onclick="toggleInvoiceDetails('{{ $invoice['id'] }}')"
                                    class="text-slate-600 hover:text-slate-800 transition-colors"
                                    title="View invoice details" aria-label="View invoice details">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                                <button type="button" class="text-blue-600 hover:text-blue-800 transition-colors"
                                    title="Edit invoice" aria-label="Edit invoice"
                                    onclick="openEditInvoiceModal({{ $invoice['id'] }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a2.25 2.25 0 1 1 3.182 3.182L10.582 17.13a4.5 4.5 0 0 1-1.897 1.13L6 19l.74-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487ZM16.862 4.487 19.5 7.125" />
                                    </svg>
                                </button>
                                {{--  
                                <form action="{{ route('invoices.destroy', $invoice['id']) }}" method="POST"
                                    onsubmit="return confirm('Delete invoice {{ $invoice['invoice_number'] }}?');"
                                    class="inline-flex">
                                    @csrf
                                    @method('DELETE') --}}
                                    <button type="button" onclick="deleteInvoice({{ $invoice['id'] }})" title="Delete"  class="text-red-600 hover:text-red-700 transition-colors"
                                        title="Delete invoice" aria-label="Delete invoice">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.245-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0V4.875c0-1.035-.84-1.875-1.875-1.875h-3.75C9.09 3 8.25 3.84 8.25 4.875v.518" />
                                        </svg>
                                    </button>
                                {{--</form> --}}
                            </div>
                        </td>
                    </tr>
                    <tr id="invoice-details-{{ $invoice['id'] }}" class="hidden bg-slate-50/70">
                        <td colspan="5" class="px-4 py-4">
                            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                                <div class="flex items-start justify-between gap-4 flex-wrap">
                                    <div>
                                        <h4 class="text-sm font-semibold text-slate-900">Invoice
                                            {{ $invoice['invoice_number'] }}</h4>
                                        <p class="text-xs text-slate-500 mt-1">Created
                                            {{ $invoice['created_at'] }}</p>
                                    </div>
                                    <div>
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full bg-brand-50 text-brand-700 font-medium text-xs">{{ ucfirst($invoice['status']) }}</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4 text-sm">
                                    <div class="rounded-lg bg-slate-50 p-3">
                                        <p class="text-xs uppercase tracking-wide text-slate-500">Client</p>
                                        <p class="mt-1 font-medium text-slate-900">Name: {{ $invoice['client_name'] }}
                                        </p>
                                        <p class="text-slate-600">Email:
                                            {{ $invoice['client_email'] ?: 'No email provided' }}</p>
                                        <p class="text-slate-600">Phone:
                                            {{ $invoice['client_phone'] ?: 'No phone provided' }}</p>
                                        <p class="text-slate-600">Invoice Type:
                                            {{ ucfirst($invoice['invoice_type'] ?? 'N/A') }}</p>
                                    </div>
                                    <div class="rounded-lg bg-slate-50 p-3">
                                        <p class="text-xs uppercase tracking-wide text-slate-500">Dates</p>
                                        <p class="mt-1 text-slate-700">Recorded: {{ $invoice['invoice_date'] }}</p>
                                        <p class="text-slate-700">Due: {{ $invoice['due_date'] ?: 'N/A' }}</p>
                                        <p class="text-slate-700">Payment:
                                            {{ ucfirst($invoice['payment_method'] ?: 'N/A') }}</p>
                                    </div>
                                    <div class="rounded-lg bg-slate-50 p-3">
                                        <p class="text-xs uppercase tracking-wide text-slate-500">Totals</p>
                                        <p class="mt-1 text-slate-700">Subtotal: TZS
                                            {{ number_format($invoice['subtotal']) }}</p>
                                        <p class="text-slate-700">Tax: TZS {{ number_format($invoice['tax_amount']) }}
                                        </p>
                                        <p class="text-slate-900 font-semibold">Total: TZS
                                            {{ number_format($invoice['total_amount']) }}</p>
                                    </div>
                                    
                                </div>

                                <div class="mt-4">
                                    <p class="text-xs uppercase tracking-wide text-slate-500 mb-2">Items</p>
                                    <div class="overflow-x-auto border border-slate-200 rounded-lg">
                                        <table class="min-w-full text-sm">
                                            <thead class="bg-slate-50">
                                                <tr>
                                                    <th class="px-3 py-2 text-left font-medium text-slate-600">#</th>
                                                    <th class="px-3 py-2 text-left font-medium text-slate-600">
                                                        Description</th>
                                                    <th class="px-3 py-2 text-right font-medium text-slate-600">Qty</th>
                                                    <th class="px-3 py-2 text-right font-medium text-slate-600">Unit
                                                        Price</th>
                                                    <th class="px-3 py-2 text-right font-medium text-slate-600">Total
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100 bg-white">
                                                @forelse ($invoice['items'] as $item)
                                                    <tr>
                                                        <td class="px-3 py-2 text-slate-500">{{ $item['item_number'] }}
                                                        </td>
                                                        <td class="px-3 py-2 text-slate-700">{{ $item['description'] }}
                                                        </td>
                                                        <td class="px-3 py-2 text-right text-slate-700">
                                                            {{ $item['quantity'] }}</td>
                                                        <td class="px-3 py-2 text-right text-slate-700">TZS
                                                            {{ number_format($item['unit_price']) }}</td>
                                                        <td class="px-3 py-2 text-right text-slate-900 font-medium">TZS
                                                            {{ number_format($item['total_price']) }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5"
                                                            class="px-3 py-4 text-center text-slate-500">No items
                                                            found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                @if ($invoice['notes'])
                                    <div class="mt-4 rounded-lg bg-slate-50 p-3">
                                        <p class="text-xs uppercase tracking-wide text-slate-500">Notes</p>
                                        <p class="mt-1 text-sm text-slate-700 whitespace-pre-line">
                                            {{ $invoice['notes'] }}</p>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-sm text-slate-500 text-center">No invoices yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Edit uses the same modal UI as Create — we will populate the existing invoice modal when editing. --}}

<script>
    function showInvoiceLoader() {
        const loader = document.getElementById('invoiceDeleteLoader');
        if (loader) {
            loader.classList.remove('hidden');
        }
    }

    function hideInvoiceLoader() {
        const loader = document.getElementById('invoiceDeleteLoader');
        if (loader) {
            loader.classList.add('hidden');
        }
    }

    // Open edit invoice modal by reusing the create-invoice modal UI.
    // Populates the existing create modal fields and marks the form for PUT submission.
    function openEditInvoiceModal(invoiceId) {
        // Fetch invoice data
        fetch(`/invoices/${invoiceId}`)
            .then(response => response.json())
            .then(invoice => {
                // mark global editing id used by send/save functions
                window.editingInvoiceId = invoiceId;

                // Populate create-modal fields (ids from add-invoice.blade.php)
                document.getElementById('invoiceNumber').value = invoice.invoice_number || '';
                document.getElementById('invoiceCompany').value = invoice.company_id || '';
                document.getElementById('invoiceClientName').value = invoice.client_name || '';
                document.getElementById('invoiceClientEmail').value = invoice.client_email || '';
                document.getElementById('invoiceClientPhone').value = invoice.client_phone || '';
                document.getElementById('invoiceClientAddress').value = invoice.client_address || '';
                document.getElementById('invoiceDate').value = invoice.invoice_date ? invoice.invoice_date.split('T')[0] : '';
                document.getElementById('invoiceDueDate').value = invoice.due_date ? invoice.due_date.split('T')[0] : '';
                document.getElementById('invoiceStatus').value = invoice.status || 'draft';
                document.getElementById('invoicePaymentMethod').value = invoice.payment_method || 'cash';
                document.getElementById('invoiceNotes').value = invoice.notes || '';

                // Remove existing dynamic rows then populate items
                const container = document.getElementById('invoiceItemsContainer');
                container.innerHTML = '';
                if (invoice.items && invoice.items.length) {
                    invoice.items.forEach(item => {
                        // reuse global addInvoiceItem to append rows then fill values
                        window.addInvoiceItem();
                    });
                    // Fill values into the created rows
                    const rows = container.querySelectorAll('.invoice-item-row');
                    invoice.items.forEach((item, idx) => {
                        const row = rows[idx];
                        if (!row) return;
                        row.querySelector('.invoice-item-desc').value = item.description || '';
                        row.querySelector('.invoice-item-qty').value = item.quantity || 1;
                        row.querySelector('.invoice-item-price').value = (item.unit_price ?? 0).toFixed(2);
                    });
                } else {
                    // ensure at least one row
                    window.addInvoiceItem();
                }

                // sync totals to reflect populated items
                if (typeof syncTotals === 'function') syncTotals();

                // Open the create modal UI
                if (typeof window.openInvoiceModal === 'function') {
                    document.getElementById('invoiceModalBackdrop').classList.remove('hidden');
                }
            })
            .catch(err => {
                console.error('Failed to load invoice for editing', err);
                window.showAlert && window.showAlert('error', 'Failed to load invoice for editing');
            });
    }

    // (editing flow now uses the create invoice modal UI; submission handled in the create modal script)

    function toggleInvoiceDetails(invoiceId) {
        const targetRow = document.getElementById(`invoice-details-${invoiceId}`);
        if (!targetRow) {
            return;
        }

        const shouldOpen = targetRow.classList.contains('hidden');

        document.querySelectorAll('[id^="invoice-details-"]').forEach(row => {
            row.classList.add('hidden');
        });

        if (shouldOpen) {
            targetRow.classList.remove('hidden');
        }
    }
</script>
