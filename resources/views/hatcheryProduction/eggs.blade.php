<div id="ph-tab-eggs" class="ph-tab-panel hidden">
    <div class="bg-white border rounded shadow-sm">
        <h2 class="text-lg font-semibold px-4 py-3 border-b">Daily Egg Collection & Grading</h2>
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Flock</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Total Collected</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Grade A</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Grade B</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Cracked / Rejected</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Collected By</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($eggCollections ?? [] as $entry)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3">{{ $entry['date'] }}</td>
                        <td class="px-4 py-3 font-medium">{{ $entry['flock'] }}</td>
                        <td class="px-4 py-3 text-right mono">{{ number_format($entry['total']) }}</td>
                        <td class="px-4 py-3 text-right mono">{{ number_format($entry['grade_a']) }}</td>
                        <td class="px-4 py-3 text-right mono">{{ number_format($entry['grade_b']) }}</td>
                        <td class="px-4 py-3 text-right mono">{{ number_format($entry['rejected']) }}</td>
                        <td class="px-4 py-3">{{ $entry['collected_by'] }}</td>
                        <td class="px-4 py-3">
                            @if ($entry['status'] === 'Sent to Store')
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">Sent
                                    to Store</span>
                            @else
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700 border border-yellow-200">{{ $entry['status'] }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-3 text-center text-slate-500">No egg collection logs for today
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
