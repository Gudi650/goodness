<div id="ph-tab-flocks" class="ph-tab-panel">
    <div class="bg-white border rounded shadow-sm">
        <h2 class="text-lg font-semibold px-4 py-3 border-b">Breeder Flock Lifecycle</h2>
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Flock Code</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Breed</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">House / Location</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Bird Count</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Age (weeks)</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Placement Date</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($flocks ?? [] as $flock)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 mono text-xs text-slate-500">{{ $flock['code'] }}</td>
                        <td class="px-4 py-3 font-medium">{{ $flock['breed'] }}</td>
                        <td class="px-4 py-3">{{ $flock['house'] }}</td>
                        <td class="px-4 py-3 text-right mono">{{ number_format($flock['bird_count']) }}</td>
                        <td class="px-4 py-3">{{ $flock['age_weeks'] }}</td>
                        <td class="px-4 py-3">{{ $flock['placement_date'] }}</td>
                        <td class="px-4 py-3">
                            @if ($flock['status'] === 'Laying')
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">Laying</span>
                            @elseif($flock['status'] === 'Rearing')
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700 border border-yellow-200">Rearing</span>
                            @else
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700 border border-red-200">{{ $flock['status'] }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-6">
                                <button type="button" onclick="togglePhDetails('flock-{{ $flock['id'] }}')"
                                    class="text-slate-600 hover:text-slate-800 transition-colors"
                                    title="View flock details" aria-label="View flock details">
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
                    <tr id="ph-details-flock-{{ $flock['id'] }}" class="hidden bg-slate-50/70">
                        <td colspan="8" class="px-4 py-4">
                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Supplier /
                                        Origin</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $flock['supplier'] ?? '-' }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Mortality to
                                        Date</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $flock['mortality'] ?? '-' }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Avg Daily
                                        Egg Rate</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $flock['egg_rate'] ?? '-' }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Supervisor
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $flock['supervisor'] ?? '-' }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Created At
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $flock['created_at'] ?? '-' }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Updated At
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $flock['updated_at'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-3 text-center text-slate-500">No breeder flocks registered
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
