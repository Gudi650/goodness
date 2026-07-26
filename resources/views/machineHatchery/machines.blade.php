<div id="mm-tab-machines" class="mm-tab-panel">
    <div class="bg-white border rounded shadow-sm">
        <h2 class="text-lg font-semibold px-4 py-3 border-b">Machine Registry</h2>
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Code</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Machine Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Location</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Capacity (eggs)</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Installed Date</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($machines ?? [] as $machine)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 mono text-xs text-slate-500">{{ $machine['code'] }}</td>
                        <td class="px-4 py-3 font-medium">{{ $machine['name'] }}</td>
                        <td class="px-4 py-3">{{ $machine['type'] }}</td>
                        <td class="px-4 py-3">{{ $machine['location'] }}</td>
                        <td class="px-4 py-3 text-right mono">{{ number_format($machine['capacity']) }}</td>
                        <td class="px-4 py-3">{{ $machine['installed_date'] }}</td>
                        <td class="px-4 py-3">
                            @if ($machine['status'] === 'Active')
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">{{ $machine['status'] }}</span>
                            @elseif($machine['status'] === 'Under Maintenance')
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700 border border-yellow-200">{{ $machine['status'] }}</span>
                            @else
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700 border border-red-200">{{ $machine['status'] }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-6">
                                <button type="button" onclick="toggleMachineDetails('{{ $machine['id'] }}')"
                                    class="text-slate-600 hover:text-slate-800 transition-colors"
                                    title="View machine details" aria-label="View machine details">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                                {{--
                                                <button type="button" class="text-blue-600 hover:text-blue-800 transition-colors"
                                                    title="Edit machine" aria-label="Edit machine"
                                                    onclick="openEditMachineModal({{ $machine['id'] }})">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m16.862 4.487 1.687-1.688a2.25 2.25 0 1 1 3.182 3.182L10.582 17.13a4.5 4.5 0 0 1-1.897 1.13L6 19l.74-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487ZM16.862 4.487 19.5 7.125" />
                                                    </svg>
                                                </button>
                                                --}}
                            </div>
                        </td>
                    </tr>
                    <tr id="machine-details-{{ $machine['id'] }}" class="hidden bg-slate-50/70">
                        <td colspan="8" class="px-4 py-4">
                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Serial
                                        Number</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $machine['serial_number'] ?? '-' }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Manufacturer
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $machine['manufacturer'] ?? '-' }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Assigned
                                        Technician</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $machine['technician'] ?? '-' }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">IoT Enabled
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ $machine['iot_enabled'] ?? false ? 'Yes' : 'No' }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Last
                                        Maintenance</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $machine['last_maintenance'] ?? '-' }}
                                    </p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Next
                                        Calibration Due</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $machine['next_calibration'] ?? '-' }}
                                    </p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Created At
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $machine['created_at'] ?? '-' }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Updated At
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $machine['updated_at'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-3 text-center text-slate-500">No machines registered</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
