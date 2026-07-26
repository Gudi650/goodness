<div id="ph-tab-hatch" class="ph-tab-panel hidden">
    <div class="bg-white border rounded shadow-sm">
        <h2 class="text-lg font-semibold px-4 py-3 border-b">Hatch Registration</h2>
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Batch Code</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Hatch Date</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Eggs Set</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Chicks Hatched</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Hatch Rate</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Culls / Rejects</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Registered By</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($hatchRegistrations ?? [] as $hatch)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 mono text-xs text-slate-500">{{ $hatch['batch_code'] }}</td>
                        <td class="px-4 py-3">{{ $hatch['hatch_date'] }}</td>
                        <td class="px-4 py-3 text-right mono">{{ number_format($hatch['eggs_set']) }}</td>
                        <td class="px-4 py-3 text-right mono">{{ number_format($hatch['chicks_hatched']) }}</td>
                        <td class="px-4 py-3 text-right mono">{{ $hatch['hatch_rate'] }}%</td>
                        <td class="px-4 py-3 text-right mono">{{ number_format($hatch['culls']) }}</td>
                        <td class="px-4 py-3">{{ $hatch['registered_by'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-3 text-center text-slate-500">No hatches registered yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
