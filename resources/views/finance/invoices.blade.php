<div id="invoicesPane">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Invoice</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Company</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Amount</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-center">Status</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($invoices as $invoice)
                    <tr class="align-top">
                        <td class="px-4 py-3 text-sm font-medium">{{ $invoice->invoice_number }}</td>
                        <td class="px-4 py-3 text-sm">{{ $invoice->company->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm text-right mono">TZS {{ number_format($invoice->total_amount) }}</td>
                        <td class="px-4 py-3 text-sm text-center">
                            <span class="inline-block px-2 py-1 {{ $invoice->status === 'paid' ? 'bg-brand-50 text-brand-700' : 'bg-slate-50 text-slate-700' }} rounded-md text-xs font-medium">{{ ucfirst($invoice->status) }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <div class="flex items-center justify-center gap-6">
                                <button type="button" onclick="toggleInvoiceDetails('{{ $invoice->id }}')" class="text-slate-600 hover:text-slate-800 transition-colors" title="View invoice details" aria-label="View invoice details">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                                <button type="button" class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit invoice" aria-label="Edit invoice" onclick="window.showAlert ? window.showAlert('info', 'Edit invoice is not wired yet.') : null">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h-2a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.5 3.5a2.121 2.121 0 113 3L9 18l-4 1 1-4 11.5-11.5z"></path>
                                    </svg>
                                </button>
                                <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('Delete invoice {{ $invoice->invoice_number }}?');" class="inline-flex">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 transition-colors" title="Delete invoice" aria-label="Delete invoice">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <tr id="invoice-details-{{ $invoice->id }}" class="hidden bg-slate-50/70">
                        <td colspan="5" class="px-4 py-4">
                            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                                <div class="flex items-start justify-between gap-4 flex-wrap">
                                    <div>
                                        <h4 class="text-sm font-semibold text-slate-900">Invoice {{ $invoice->invoice_number }}</h4>
                                        <p class="text-xs text-slate-500 mt-1">Created {{ $invoice->created_at?->format('M d, Y h:i A') }}</p>
                                    </div>
                                    <div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-brand-50 text-brand-700 font-medium text-xs">{{ ucfirst($invoice->status) }}</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4 text-sm">
                                    <div class="rounded-lg bg-slate-50 p-3">
                                        <p class="text-xs uppercase tracking-wide text-slate-500">Client</p>
                                        <p class="mt-1 font-medium text-slate-900">Name: {{ $invoice->client_name }}</p>
                                        <p class="text-slate-600">Email: {{ $invoice->client_email ?: 'No email provided' }}</p>
                                        <p class="text-slate-600">Phone: {{ $invoice->client_phone ?: 'No phone provided' }}</p>
                                    </div>
                                    <div class="rounded-lg bg-slate-50 p-3">
                                        <p class="text-xs uppercase tracking-wide text-slate-500">Dates</p>
                                        <p class="mt-1 text-slate-700">Recorded: {{ $invoice->invoice_date }}</p>
                                        <p class="text-slate-700">Due: {{ $invoice->due_date ?: 'N/A' }}</p>
                                        <p class="text-slate-700">Payment: {{ ucfirst($invoice->payment_method ?: 'N/A') }}</p>
                                    </div>
                                    <div class="rounded-lg bg-slate-50 p-3">
                                        <p class="text-xs uppercase tracking-wide text-slate-500">Totals</p>
                                        <p class="mt-1 text-slate-700">Subtotal: TZS {{ number_format($invoice->subtotal) }}</p>
                                        <p class="text-slate-700">Tax: TZS {{ number_format($invoice->tax_amount) }}</p>
                                        <p class="text-slate-900 font-semibold">Total: TZS {{ number_format($invoice->total_amount) }}</p>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <p class="text-xs uppercase tracking-wide text-slate-500 mb-2">Items</p>
                                    <div class="overflow-x-auto border border-slate-200 rounded-lg">
                                        <table class="min-w-full text-sm">
                                            <thead class="bg-slate-50">
                                                <tr>
                                                    <th class="px-3 py-2 text-left font-medium text-slate-600">#</th>
                                                    <th class="px-3 py-2 text-left font-medium text-slate-600">Description</th>
                                                    <th class="px-3 py-2 text-right font-medium text-slate-600">Qty</th>
                                                    <th class="px-3 py-2 text-right font-medium text-slate-600">Unit Price</th>
                                                    <th class="px-3 py-2 text-right font-medium text-slate-600">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100 bg-white">
                                                @forelse ($invoice->items as $item)
                                                    <tr>
                                                        <td class="px-3 py-2 text-slate-500">{{ $item->item_number }}</td>
                                                        <td class="px-3 py-2 text-slate-700">{{ $item->description }}</td>
                                                        <td class="px-3 py-2 text-right text-slate-700">{{ $item->quantity }}</td>
                                                        <td class="px-3 py-2 text-right text-slate-700">TZS {{ number_format($item->unit_price) }}</td>
                                                        <td class="px-3 py-2 text-right text-slate-900 font-medium">TZS {{ number_format($item->total_price) }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="px-3 py-4 text-center text-slate-500">No items found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                @if ($invoice->notes)
                                    <div class="mt-4 rounded-lg bg-slate-50 p-3">
                                        <p class="text-xs uppercase tracking-wide text-slate-500">Notes</p>
                                        <p class="mt-1 text-sm text-slate-700 whitespace-pre-line">{{ $invoice->notes }}</p>
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

<script>
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
