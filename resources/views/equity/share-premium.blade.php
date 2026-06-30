{{-- Loading components for share premium actions --}}

<div id="sharePremiumPane">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Record ID</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Company</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Premium Amount</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Shares Issued</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Premium/Share</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-center">Status</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">

                @forelse($sharePremiumsData as $premium)
                    <tr>
                        <td class="px-4 py-3 text-sm text-slate-700">SP-{{ $premium->id }}</td>
                        <td class="px-4 py-3 text-sm text-slate-700">{{ $premium->company->name }}</td>
                        <td class="px-4 py-3 text-sm text-right text-slate-700">
                            TZS {{ number_format($premium->total_premium, 2) }}
                        </td>
                        <td class="px-4 py-3 text-sm text-right text-slate-700">{{ $premium->shares_issued }}</td>
                        <td class="px-4 py-3 text-sm text-right text-slate-700">{{ number_format($premium->premium_per_share, 2) }}</td>
                        <td class="px-4 py-3 text-sm text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-50 text-green-700 font-medium text-xs">
                                Approved
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <button onclick="toggleDropdown('share-premium-details-{{ $premium->id }}')" class="text-slate-600 hover:text-slate-800 transition-colors">
                                <!-- eye icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <!-- Dropdown row -->
                    <tr id="share-premium-details-{{ $premium->id }}" class="hidden bg-slate-50/70">
                        <td colspan="7" class="px-4 py-4">
                            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                                <div class="flex items-start justify-between gap-4 flex-wrap">
                                    <div>
                                        <h4 class="text-sm font-semibold text-slate-900">Share Premium Record: SP-{{ $premium->id }}</h4>
                                        <p class="text-xs text-slate-500 mt-1">Company: {{ $premium->company->name }}</p>
                                    </div>
                                    <div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-50 text-green-700 font-medium text-xs">
                                            Approved
                                        </span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4 text-sm">
                                    <div class="rounded-lg bg-slate-50 p-3">
                                        <p class="text-xs uppercase tracking-wide text-slate-500">Premium Info</p>
                                        <p class="mt-1 font-medium text-slate-900">Premium Amount: TZS {{ number_format($premium->total_premium, 2) }}</p>
                                        <p class="text-slate-600">Shares Issued: {{ $premium->shares_issued }}</p>
                                        <p class="text-slate-600">Premium/Share: {{ number_format($premium->premium_per_share, 2) }}</p>
                                    </div>
                                    <div class="rounded-lg bg-slate-50 p-3">
                                        <p class="text-xs uppercase tracking-wide text-slate-500">Dates</p>
                                        <p class="mt-1 text-slate-700">Recorded: {{ $premium->created_at->format('Y-m-d') }}</p>
                                        <p class="text-slate-700">Updated: {{ $premium->updated_at->format('Y-m-d') }}</p>
                                    </div>
                                    <div class="rounded-lg bg-slate-50 p-3">
                                        <p class="text-xs uppercase tracking-wide text-slate-500">Notes</p>
                                        <p class="mt-1 text-slate-700">{{ $premium->notes ?? 'No notes' }}</p>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-3 text-sm text-center text-slate-500">
                            No share premium records yet.
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>
</div>

<script>

    function toggleDropdown(id) {
        const row = document.getElementById(id);
        row.classList.toggle('hidden');
    }
    
</script>
