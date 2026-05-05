<div id="paymentsPane" class="hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">ID</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Company</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Amount</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Method</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($payments as $payment)
                    <tr>
                        <td class="px-4 py-3 text-sm">{{ $payment['id'] }}</td>
                        <td class="px-4 py-3 text-sm">{{ $payment['company'] }}</td>
                        <td class="px-4 py-3 text-sm text-right mono">TZS {{ number_format($payment['amount']) }}</td>
                        <td class="px-4 py-3 text-sm">{{ $payment['method'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-sm text-slate-500 text-center">No payments yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
