<div id="mm-tab-calibration" class="mm-tab-panel hidden">
    <div class="bg-white border rounded shadow-sm">
        <h2 class="text-lg font-semibold px-4 py-3 border-b">Calibration Records</h2>
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Machine</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Component</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Calibration Date</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Next Due</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Performed By</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Certificate</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($calibrationRecords ?? [] as $record)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-medium">{{ $record['machine'] }}</td>
                        <td class="px-4 py-3">{{ $record['component'] }}</td>
                        <td class="px-4 py-3">{{ $record['calibration_date'] }}</td>
                        <td class="px-4 py-3">{{ $record['next_due'] }}</td>
                        <td class="px-4 py-3">{{ $record['performed_by'] }}</td>
                        <td class="px-4 py-3">
                            @if (!empty($record['certificate']))
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">On
                                    file</span>
                            @else
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-slate-100 text-slate-600 border border-slate-200">Pending</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-slate-500">No calibration records found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
