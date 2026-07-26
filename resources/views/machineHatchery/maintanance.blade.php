<div id="mm-tab-maintenance" class="mm-tab-panel hidden">
    <div class="bg-white border rounded shadow-sm">
        <h2 class="text-lg font-semibold px-4 py-3 border-b">Scheduled Maintenance</h2>
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Machine</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Maintenance Type</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Scheduled Date</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Frequency</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Assigned Technician</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($maintenanceSchedule ?? [] as $item)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-medium">{{ $item['machine'] }}</td>
                        <td class="px-4 py-3">{{ $item['maintenance_type'] }}</td>
                        <td class="px-4 py-3">{{ $item['scheduled_date'] }}</td>
                        <td class="px-4 py-3">{{ $item['frequency'] }}</td>
                        <td class="px-4 py-3">{{ $item['technician'] }}</td>
                        <td class="px-4 py-3">
                            @if ($item['status'] === 'Completed')
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">Completed</span>
                            @elseif($item['status'] === 'Overdue')
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700 border border-red-200">Overdue</span>
                            @else
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700 border border-yellow-200">Upcoming</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-slate-500">No maintenance tasks scheduled
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
