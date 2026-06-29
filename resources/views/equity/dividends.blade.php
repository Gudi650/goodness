<div id="dividendsPane">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Dividend ID</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Shareholder</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Company</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Amount</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Ownership %</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-center">Status</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">

                @forelse($dividendsData as $dividend)
                    @foreach($dividend->distributions as $distribution)
                        <tr>
                            <td class="px-4 py-3 text-sm text-slate-700">DIV-{{ $dividend->id }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ $distribution->shareholder_name }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ $dividend->company->name }}</td>
                            <td class="px-4 py-3 text-sm text-right text-slate-700">
                                TZS {{ number_format($distribution->amount, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right text-slate-700">
                                {{ $distribution->ownership_percentage }}%
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-50 text-green-700 font-medium text-xs">
                                    Paid
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                <button onclick="toggleDropdown('dividend-details-{{ $distribution->id }}')" class="text-slate-600 hover:text-slate-800 transition-colors">
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
                        <tr id="dividend-details-{{ $distribution->id }}" class="hidden bg-slate-50/70">
                            <td colspan="7" class="px-4 py-4">
                                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                                    <div class="flex items-start justify-between gap-4 flex-wrap">
                                        <div>
                                            <h4 class="text-sm font-semibold text-slate-900">Dividend: DIV-{{ $dividend->id }}</h4>
                                            <p class="text-xs text-slate-500 mt-1">Shareholder: {{ $distribution->shareholder_name }}</p>
                                            <p class="text-xs text-slate-500">Company: {{ $dividend->company->name }}</p>
                                        </div>
                                        <div>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-50 text-green-700 font-medium text-xs">
                                                Paid
                                            </span>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4 text-sm">
                                        <div class="rounded-lg bg-slate-50 p-3">
                                            <p class="text-xs uppercase tracking-wide text-slate-500">Dividend Info</p>
                                            <p class="mt-1 font-medium text-slate-900">Amount: TZS {{ number_format($distribution->amount, 2) }}</p>
                                            <p class="text-slate-600">Ownership: {{ $distribution->ownership_percentage }}%</p>
                                            <p class="text-slate-600">Shares: {{ $distribution->shares }}</p>
                                        </div>
                                        <div class="rounded-lg bg-slate-50 p-3">
                                            <p class="text-xs uppercase tracking-wide text-slate-500">Dates</p>
                                            <p class="mt-1 text-slate-700">Declared: {{ $dividend->declared_at }}</p>
                                            {{-- If you have a paid_at column, show it --}}
                                            {{-- <p class="text-slate-700">Paid: {{ $dividend->paid_at }}</p> --}}
                                        </div>
                                        <div class="rounded-lg bg-slate-50 p-3">
                                            <p class="text-xs uppercase tracking-wide text-slate-500">Notes</p>
                                            <p class="mt-1 text-slate-700">{{ $distribution->notes ?? 'No notes' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-3 text-sm text-center text-slate-500">
                            No dividends declared yet.
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
