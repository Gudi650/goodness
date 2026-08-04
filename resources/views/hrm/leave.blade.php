<div id="tab-leave" class="tab-content hidden">
    <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-lg font-semibold font-display">Leave Requests</h2>
        <button onclick="openAddLeaveModal()"
            class="w-full sm:w-auto flex-shrink-0 whitespace-nowrap px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium transition-colors">Request
            Leave</button>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                            Employee</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Type
                        </th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">From
                        </th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">To
                        </th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Days
                        </th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                            Status</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                            Actions</th>
                    </tr>
                </thead>
                <tbody id="leaveTable" class="divide-y divide-slate-100">
                    @forelse($leaves ?? [] as $leave)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 text-sm">{{ $leave->user?->name ?? 'Employee' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $leave->leave_type }}</td>
                            <td class="px-4 py-3 text-sm">{{ $leave->from_date?->format('Y-m-d') ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $leave->to_date?->format('Y-m-d') ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $leave->days }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if ($leave->status === 'Approved')
                                    <span
                                        class="inline-block px-2 py-0.5 rounded bg-brand-50 text-brand-700 text-xs font-medium">Approved</span>
                                @elseif($leave->status === 'Rejected')
                                    <span
                                        class="inline-block px-2 py-0.5 rounded bg-red-50 text-red-700 text-xs font-medium">Rejected</span>
                                @else
                                    <span
                                        class="inline-block px-2 py-0.5 rounded bg-amber-50 text-amber-700 text-xs font-medium">Pending</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm space-x-2">
                                @if ($leave->status === 'Pending')
                                    <form method="POST" action="{{ route('leaves.update', $leave->id) }}"
                                        class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="Approved">
                                        <div class="flex items-center gap-1">
                                            <button type="submit"
                                                class="px-2 py-1 bg-brand-50 text-green-700 text-xs rounded hover:bg-green-100">

                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"
                                                    class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg>

                                            </button>
                                    </form>

                                    <form method="POST" action="{{ route('leaves.update', $leave->id) }}"
                                        class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="Rejected">
                                        <button type="submit"
                                            class="px-2 py-1 bg-red-50 text-red-700 text-xs rounded hover:bg-red-100">

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>

                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-400 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-3 text-center text-slate-500 text-sm">No leave requests
                                yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
