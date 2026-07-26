<div id="ph-tab-allocation" class="ph-tab-panel hidden">
    <div class="bg-white border rounded shadow-sm">
        <h2 class="text-lg font-semibold px-4 py-3 border-b">Chick Allocation</h2>
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Batch Code</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Allocated To</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Party Type</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Quantity</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Allocation Date</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($chickAllocations ?? [] as $alloc)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 mono text-xs text-slate-500">{{ $alloc['batch_code'] }}</td>
                        <td class="px-4 py-3 font-medium">{{ $alloc['allocated_to'] }}</td>
                        <td class="px-4 py-3">{{ $alloc['party_type'] }}</td>
                        <td class="px-4 py-3 text-right mono">{{ number_format($alloc['quantity']) }}</td>
                        <td class="px-4 py-3">{{ $alloc['allocation_date'] }}</td>
                        <td class="px-4 py-3">
                            @if ($alloc['status'] === 'Delivered')
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">Delivered</span>
                            @else
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700 border border-yellow-200">{{ $alloc['status'] }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-slate-500">No chick allocations recorded
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
