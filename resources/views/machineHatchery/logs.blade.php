<div id="mm-tab-logs" class="mm-tab-panel hidden">
    <div class="bg-white border rounded shadow-sm">
        <h2 class="text-lg font-semibold px-4 py-3 border-b">Daily Machine Logs</h2>
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Machine</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Shift</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Temp (°C)</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Humidity (%)</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Turning Count</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Recorded By</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Flag</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($machineLogs ?? [] as $log)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3">{{ $log['date'] }}</td>
                        <td class="px-4 py-3 font-medium">{{ $log['machine'] }}</td>
                        <td class="px-4 py-3">{{ $log['shift'] }}</td>
                        <td class="px-4 py-3 text-right mono">{{ $log['temperature'] }}</td>
                        <td class="px-4 py-3 text-right mono">{{ $log['humidity'] }}</td>
                        <td class="px-4 py-3 text-right mono">{{ $log['turning_count'] }}</td>
                        <td class="px-4 py-3">{{ $log['recorded_by'] }}</td>
                        <td class="px-4 py-3">
                            @if ($log['flag'] === 'Normal')
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">Normal</span>
                            @else
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700 border border-red-200">{{ $log['flag'] }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-3 text-center text-slate-500">No logs recorded for today</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
