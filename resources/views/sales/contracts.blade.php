<div id="tab-contracts" class="tab-content hidden">
    <div class="flex flex-col gap-3 mb-6 lg:flex-row lg:items-center lg:justify-between">
        <h2 class="text-lg font-semibold font-display">Contracts</h2>
        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto lg:items-center">
            <button onclick="openAddContractModal()"
                class="w-full sm:w-auto flex-shrink-0 whitespace-nowrap px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium transition-colors">Add
                Contract</button>
        </div>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Contract No.</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Client</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Value</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Start Date</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">End Date</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Status</th>
                    </tr>
                </thead>
                <tbody id="contractsTable" class="divide-y divide-slate-100">
                    @forelse(($contracts ?? []) as $contract)
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-4 py-3 text-sm text-slate-700 font-medium">{{ $contract->contract_number }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700">
                                {{ $contract->contract_counterparty_name }}
                                <div class="text-xs text-slate-500 mt-1">
                                    {{ $contract->company->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-700">
                                {{ $contract->contract_currency }} {{ number_format((float) $contract->contract_value, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ optional($contract->contract_start_date)->format('Y-m-d') }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ optional($contract->contract_end_date)->format('Y-m-d') }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $status = $contract->contract_status ?? 'Draft';
                                    $statusStyles = [
                                        'Active' => 'bg-green-50 text-green-700',
                                        'Draft' => 'bg-slate-100 text-slate-700',
                                        'Under Review' => 'bg-blue-50 text-blue-700',
                                        'Expired' => 'bg-red-50 text-red-700',
                                        'Terminated' => 'bg-red-50 text-red-700',
                                        'Suspended' => 'bg-amber-50 text-amber-700',
                                        'Renewed' => 'bg-emerald-50 text-emerald-700',
                                    ];
                                    $statusClass = $statusStyles[$status] ?? 'bg-slate-100 text-slate-700';
                                @endphp
                                <span class="px-2 py-1 rounded text-xs {{ $statusClass }}">{{ $status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500">
                                No contracts found. Create one using the Add Contract button.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
