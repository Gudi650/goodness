<div id="paymentsPane" class="hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Reference</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Date</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Company</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Direction</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Amount</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Method</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Status</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($payments as $payment)
                    <tr class="hover:bg-slate-50/60">
                        <td class="px-4 py-3 text-sm font-semibold text-slate-800">{{ $payment['payment_reference'] }}</td>
                        <td class="px-4 py-3 text-sm text-slate-600">{{ $payment['payment_date'] }}</td>
                        <td class="px-4 py-3 text-sm text-slate-700">{{ $payment['company'] }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $payment['payment_direction'] === 'Incoming' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                {{ $payment['payment_direction'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-right font-mono text-slate-800">
                            {{ $payment['currency'] }} {{ number_format($payment['amount'], 2) }}
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-600">{{ $payment['payment_method'] }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $payment['payment_status'] === 'Completed' ? 'bg-emerald-50 text-emerald-700' : ($payment['payment_status'] === 'Pending' ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-700') }}">
                                {{ $payment['payment_status'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex justify-end items-center gap-2">
                                <button type="button"
                                        onclick="togglePaymentDetails('payment-detail-{{ $payment['id'] }}', this)"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:border-brand-300 hover:text-brand-700 hover:bg-brand-50 transition-colors"
                                        aria-label="View payment details">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7Z" />
                                    </svg>
                                </button>
                                <button type="button"
                                        onclick='openEditPaymentModal(@js($payment))'
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:border-brand-300 hover:text-brand-700 hover:bg-brand-50 transition-colors"
                                        aria-label="Edit payment">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16.862 4.487a2.1 2.1 0 0 1 2.97 2.97L7.5 19.786 3 21l1.214-4.5L16.862 4.487Z" />
                                    </svg>
                                </button>
                                <button type="button"
                                        onclick="confirmPaymentDelete({{ $payment['id'] }}, @js($payment['payment_reference']))"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-red-200 text-red-500 hover:bg-red-50 hover:border-red-300 hover:text-red-700 transition-colors"
                                        aria-label="Delete payment">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 7h12M10 11v6m4-6v6M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m-7 0h8m-9 0 1 12a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1l1-12" />
                                    </svg>
                                </button>
                            </div>
                            <form id="delete-payment-form-{{ $payment['id'] }}" action="{{ $payment['delete_url'] }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    <tr id="payment-detail-{{ $payment['id'] }}" class="hidden bg-slate-50/60">
                        <td colspan="8" class="px-4 py-4">
                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Reference</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $payment['payment_reference'] }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Paid By / Received From</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $payment['party_name'] }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Category</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $payment['payment_category'] }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Linked To</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $payment['linked_to'] }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Amount</p>
                                    <p class="mt-1 text-sm text-slate-700 font-mono">{{ $payment['currency'] }} {{ number_format($payment['amount'], 2) }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Exchange Rate</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ number_format($payment['exchange_rate'], 4) }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">TZS Equivalent</p>
                                    <p class="mt-1 text-sm text-slate-700 font-mono">TZS {{ number_format($payment['tzs_equivalent'], 2) }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Reference Number</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $payment['reference_number'] }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Direction</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $payment['payment_direction'] }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $payment['payment_status'] }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3 md:col-span-2 lg:col-span-2">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Notes</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $payment['notes'] }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3 md:col-span-2 lg:col-span-4">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Attachment</p>
                                    @if ($payment['attachment_url'])
                                        <div class="mt-2">
                                            @if ($payment['attachment_is_image'])
                                                <a href="{{ $payment['attachment_url'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-3 rounded-lg border border-slate-200 p-2 hover:border-brand-400">
                                                    <img src="{{ $payment['attachment_url'] }}" alt="Payment attachment" class="h-20 w-20 rounded-md object-cover">
                                                    <span class="text-sm font-medium text-slate-700">View attachment</span>
                                                </a>
                                            @else
                                                <a href="{{ $payment['attachment_url'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-md border border-slate-200 px-3 py-2 text-sm font-medium text-brand-700 hover:border-brand-400 hover:text-brand-800">
                                                    View 
                                                </a>

                                                <a href="{{ $payment['download_url'] }}" class="ml-3 inline-flex items-center gap-2 rounded-md border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 hover:border-brand-400 hover:text-brand-800">
                                                    Download Attachment
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        <p class="mt-1 text-sm text-slate-500">No attachment uploaded.</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-sm text-slate-500 text-center">No payments yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<x-loading id="paymentActionLoader" fullPage="true" class="hidden" />

<script>
    function togglePaymentDetails(detailRowId, buttonEl) {
        const detailRow = document.getElementById(detailRowId);
        if (!detailRow) return;

        detailRow.classList.toggle('hidden');

        if (buttonEl) {
            buttonEl.classList.toggle('bg-slate-100');
            buttonEl.classList.toggle('text-brand-700');
            buttonEl.classList.toggle('border-brand-300');
        }
    }

    function confirmPaymentDelete(paymentId, paymentReference) {
        if (typeof window.openConfirm !== 'function') {
            return;
        }

        window.openConfirm({
            title: 'Delete payment',
            message: `Are you sure you want to delete payment ${paymentReference}? This cannot be undone.`,
            confirmText: 'Delete',
            cancelText: 'Cancel',
            variant: 'warning',
            onConfirm: () => {
                const loader = document.getElementById('paymentActionLoader');
                if (loader) loader.classList.remove('hidden');

                window.setTimeout(() => {
                    const form = document.getElementById(`delete-payment-form-${paymentId}`);
                    if (form) form.submit();
                }, 75);
            },
        });
    }
</script>
