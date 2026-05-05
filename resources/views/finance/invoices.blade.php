<div id="invoicesPane">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Invoice</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Company</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Amount</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($invoices as $invoice)
                    <tr>
                        <td class="px-4 py-3 text-sm">{{ $invoice['id'] }}</td>
                        <td class="px-4 py-3 text-sm">{{ $invoice['company'] }}</td>
                        <td class="px-4 py-3 text-sm text-right mono">TZS {{ number_format($invoice['amount']) }}</td>
                        <td class="px-4 py-3 text-sm text-center">
                            <span class="inline-block px-2 py-1 {{ $invoice['status'] === 'Paid' ? 'bg-brand-50 text-brand-700' : 'bg-slate-50 text-slate-700' }} rounded-md text-xs">{{ $invoice['status'] }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-sm text-slate-500 text-center">No invoices yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
