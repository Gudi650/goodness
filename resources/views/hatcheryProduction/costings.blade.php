<div id="ph-tab-costing" class="ph-tab-panel hidden">
    <div class="bg-white border rounded shadow-sm">
        <h2 class="text-lg font-semibold px-4 py-3 border-b">Batch-Level Costing & Party Profitability</h2>
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Batch Code</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Party</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Total Cost</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Revenue</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Margin</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Cost / Chick</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($batchCosting ?? [] as $cost)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 mono text-xs text-slate-500">{{ $cost['batch_code'] }}</td>
                        <td class="px-4 py-3 font-medium">{{ $cost['party'] }}</td>
                        <td class="px-4 py-3 text-right mono">TZS {{ number_format($cost['total_cost']) }}</td>
                        <td class="px-4 py-3 text-right mono">TZS {{ number_format($cost['revenue']) }}</td>
                        <td class="px-4 py-3 text-right mono">TZS {{ number_format($cost['margin']) }}</td>
                        <td class="px-4 py-3 text-right mono">TZS {{ number_format($cost['cost_per_chick']) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-slate-500">No costing data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
