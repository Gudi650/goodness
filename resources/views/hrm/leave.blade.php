<div id="tab-leave" class="tab-content hidden">
    <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-lg font-semibold font-display">Leave Requests</h2>
        <button onclick="openAddLeaveModal()"
            class="w-full sm:w-auto flex-shrink-0 whitespace-nowrap px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium transition-colors">Add Leave</button>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Employee</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Type</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">From</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">To</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Days</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody id="leaveTable" class="divide-y divide-slate-100">
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm">Amina Hassan</td>
                        <td class="px-4 py-3 text-sm">Annual Leave</td>
                        <td class="px-4 py-3 text-sm">2026-05-01</td>
                        <td class="px-4 py-3 text-sm">2026-05-05</td>
                        <td class="px-4 py-3 text-sm">5</td>
                        <td class="px-4 py-3 text-sm"><span class="inline-block px-2 py-0.5 rounded bg-amber-50 text-amber-700 text-xs font-medium">Pending</span></td>
                        <td class="px-4 py-3 text-sm space-x-2"><button onclick="approveLeave(1)" class="px-2 py-1 bg-brand-50 text-brand-700 text-xs rounded hover:bg-brand-100">Approve</button><button onclick="rejectLeave(1)" class="px-2 py-1 bg-red-50 text-red-700 text-xs rounded hover:bg-red-100">Reject</button></td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm">Joseph Kimani</td>
                        <td class="px-4 py-3 text-sm">Sick Leave</td>
                        <td class="px-4 py-3 text-sm">2026-04-28</td>
                        <td class="px-4 py-3 text-sm">2026-04-29</td>
                        <td class="px-4 py-3 text-sm">2</td>
                        <td class="px-4 py-3 text-sm"><span class="inline-block px-2 py-0.5 rounded bg-brand-50 text-brand-700 text-xs font-medium">Approved</span></td>
                        <td class="px-4 py-3 text-sm">-</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
