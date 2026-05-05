<div id="expensesPane" class="hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">ID</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Category</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Amount</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Description</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($expenses as $expense)
                    <tr>
                        <td class="px-4 py-3 text-sm">{{ $expense['id'] }}</td>
                        <td class="px-4 py-3 text-sm">{{ $expense['category'] }}</td>
                        <td class="px-4 py-3 text-sm text-right mono">TZS {{ number_format($expense['amount']) }}</td>
                        <td class="px-4 py-3 text-sm">{{ $expense['description'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-sm text-slate-500 text-center">No expenses yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
