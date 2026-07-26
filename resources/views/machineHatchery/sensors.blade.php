<div id="mm-tab-sensors" class="mm-tab-panel hidden">
    <div class="bg-white border rounded shadow-sm">
        <h2 class="text-lg font-semibold px-4 py-3 border-b">IoT Sensors (Optional Integration)</h2>
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Sensor ID</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Machine</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Type</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Last Reading</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Last Sync</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($iotSensors ?? [] as $sensor)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 mono text-xs text-slate-500">{{ $sensor['sensor_id'] }}</td>
                        <td class="px-4 py-3 font-medium">{{ $sensor['machine'] }}</td>
                        <td class="px-4 py-3">{{ $sensor['type'] }}</td>
                        <td class="px-4 py-3 text-right mono">{{ $sensor['last_reading'] }}</td>
                        <td class="px-4 py-3">{{ $sensor['last_sync'] }}</td>
                        <td class="px-4 py-3">
                            @if ($sensor['status'] === 'Online')
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">Online</span>
                            @else
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700 border border-red-200">Offline</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-slate-500">No IoT sensors connected</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
