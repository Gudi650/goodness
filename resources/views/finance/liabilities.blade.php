<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden hidden" id="liabilitiesPane">

    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
        <span class="text-base font-semibold text-slate-800">Recent Liabilities</span>
    </div>

    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Code</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Name</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Term</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Outstanding</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Due Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($liabilitiesDetails as $liability)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-mono text-xs text-slate-500">{{ $liability['code'] }}</td>
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $liability['name'] }}</td>
                    <td class="px-4 py-3">
                        @if (!empty($liability['term'] === 'Long-term'))
                            <span
                                class="inline-flex px-2 py-0.5 rounded-full text-xs bg-purple-100 text-purple-700 border border-purple-200">{{ $liability['term'] }}</span>
                        @else
                            <span
                                class="inline-flex px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-700 border border-blue-200">{{ $liability['term'] }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right font-mono">TZS {{ number_format($liability['current_amount'], 0) }}
                    </td>
                    <td class="px-4 py-3">{{ $liability['due_date'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-3 text-center text-slate-500">No liabilities found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
