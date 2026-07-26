<div id="mm-tab-alarms" class="mm-tab-panel hidden">
    <div class="bg-white border rounded shadow-sm">
        <h2 class="text-lg font-semibold px-4 py-3 border-b">Alarms & Alerts</h2>
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Date/Time</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Machine</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Alarm Type</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Severity</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Resolved By</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($alarms ?? [] as $alarm)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 mono text-xs text-slate-500">{{ $alarm['datetime'] }}</td>
                        <td class="px-4 py-3 font-medium">{{ $alarm['machine'] }}</td>
                        <td class="px-4 py-3">{{ $alarm['type'] }}</td>
                        <td class="px-4 py-3">
                            @if ($alarm['severity'] === 'Critical')
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700 border border-red-200">Critical</span>
                            @elseif($alarm['severity'] === 'Warning')
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700 border border-yellow-200">Warning</span>
                            @else
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-slate-100 text-slate-700 border border-slate-200">Info</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if ($alarm['status'] === 'Resolved')
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">Resolved</span>
                            @else
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700 border border-red-200">Open</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $alarm['resolved_by'] ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-slate-500">No alarms recorded</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
