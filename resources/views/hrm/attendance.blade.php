<div id="tab-attendance" class="tab-content hidden">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold font-display">Attendance Records</h2>
        <input type="date" id="attendanceFilter" class="border border-slate-300 rounded-md px-3 py-2 text-sm" />
    </div>
    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Employee</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Date</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Check-in</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Check-out</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Hours</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Status</th>
                    </tr>
                </thead>
                <tbody id="attendanceTable" class="divide-y divide-slate-100">
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm">Amina Hassan</td>
                        <td class="px-4 py-3 text-sm">2026-04-30</td>
                        <td class="px-4 py-3 text-sm">08:15</td>
                        <td class="px-4 py-3 text-sm">17:45</td>
                        <td class="px-4 py-3 text-sm">9.5</td>
                        <td class="px-4 py-3 text-sm"><span class="inline-block px-2 py-0.5 rounded bg-brand-50 text-brand-700 text-xs font-medium">Present</span></td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm">Joseph Kimani</td>
                        <td class="px-4 py-3 text-sm">2026-04-30</td>
                        <td class="px-4 py-3 text-sm">08:00</td>
                        <td class="px-4 py-3 text-sm">17:00</td>
                        <td class="px-4 py-3 text-sm">9</td>
                        <td class="px-4 py-3 text-sm"><span class="inline-block px-2 py-0.5 rounded bg-brand-50 text-brand-700 text-xs font-medium">Present</span></td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm">Lucy Mwangi</td>
                        <td class="px-4 py-3 text-sm">2026-04-30</td>
                        <td class="px-4 py-3 text-sm">-</td>
                        <td class="px-4 py-3 text-sm">-</td>
                        <td class="px-4 py-3 text-sm">-</td>
                        <td class="px-4 py-3 text-sm"><span class="inline-block px-2 py-0.5 rounded bg-red-50 text-red-700 text-xs font-medium">Absent</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
