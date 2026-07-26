<div id="ph-tab-batches" class="ph-tab-panel hidden">
    <div class="bg-white border rounded shadow-sm">
        <h2 class="text-lg font-semibold px-4 py-3 border-b">Batch Creation & Incubation Tracking</h2>
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Batch Code</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Machine (Setter)</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Eggs Set</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Set Date</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Expected Hatch Date</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Day of Incubation</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($batches ?? [] as $batch)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 mono text-xs text-slate-500">{{ $batch['code'] }}</td>
                        <td class="px-4 py-3 font-medium">{{ $batch['machine'] }}</td>
                        <td class="px-4 py-3 text-right mono">{{ number_format($batch['eggs_set']) }}</td>
                        <td class="px-4 py-3">{{ $batch['set_date'] }}</td>
                        <td class="px-4 py-3">{{ $batch['expected_hatch_date'] }}</td>
                        <td class="px-4 py-3">{{ $batch['incubation_day'] }} / 21</td>
                        <td class="px-4 py-3">
                            @if ($batch['status'] === 'Incubating')
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700 border border-yellow-200">Incubating</span>
                            @elseif($batch['status'] === 'Transferred')
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-700 border border-blue-200">Transferred</span>
                            @elseif($batch['status'] === 'Hatched')
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">Hatched</span>
                            @else
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700 border border-red-200">{{ $batch['status'] }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-6">
                                <button type="button" onclick="togglePhDetails('batch-{{ $batch['id'] }}')"
                                    class="text-slate-600 hover:text-slate-800 transition-colors"
                                    title="View batch details" aria-label="View batch details">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr id="ph-details-batch-{{ $batch['id'] }}" class="hidden bg-slate-50/70">
                        <td colspan="8" class="px-4 py-4">
                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Source
                                        Flock(s)</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $batch['source_flocks'] ?? '-' }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Transfer
                                        Date</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $batch['transfer_date'] ?? '-' }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Hatcher
                                        Machine</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $batch['hatcher_machine'] ?? '-' }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Candling
                                        Result (Fertile)</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $batch['fertile_count'] ?? '-' }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Created At
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $batch['created_at'] ?? '-' }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Updated At
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $batch['updated_at'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-3 text-center text-slate-500">No active batches</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
