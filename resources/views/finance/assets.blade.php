<div id="assetsPane" class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden hidden">

    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
        <span class="text-base font-semibold text-slate-800">Recent Assets</span>
    </div>

    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Code</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Name</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Term</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Value</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($assetsDetails as $asset)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-mono text-xs text-slate-500">{{ $asset['code'] }}</td>
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $asset['name'] }}</td>
                    <td class="px-4 py-3">
                        @if ($asset['term'] === 'Long-term')
                            <span
                                class="badge bg-purple-100 text-purple-700 border border-purple-200">{{ $asset['term'] }}</span>
                        @else
                            <span
                                class="badge bg-blue-100 text-blue-700 border border-blue-200">{{ $asset['term'] }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right font-mono">TZS {{ number_format($asset['current_value'], 0) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-3 text-center text-slate-500">No assets found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
