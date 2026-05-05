<div id="tab-employees" class="tab-content">
    <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-lg font-semibold font-display">Employees</h2>
        <div class="flex flex-col gap-2 w-full sm:w-auto sm:flex-row">
            <button onclick="openAddEmployeeModal()"
                class="flex-1 sm:flex-none px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium transition-colors">Add
                Employee</button>
            <button onclick="openBulkImportModal()"
                class="flex-1 sm:flex-none px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-md text-sm font-medium transition-colors">Import CSV</button>
        </div>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Name</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Department</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Position</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Company</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Join Date</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Action</th>
                    </tr>
                </thead>
                <tbody id="employeesTable" class="divide-y divide-slate-100">
                    @forelse($employees as $employee)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 text-sm">{{ $employee['name'] }}</td>
                            <td class="px-4 py-3 text-sm">{{ $employee['department'] }}</td>
                            <td class="px-4 py-3 text-sm">{{ $employee['position'] }}</td>
                            <td class="px-4 py-3 text-sm">{{ $employee['company_name'] }}</td>
                            <td class="px-4 py-3 text-sm">{{ $employee['join_date'] }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-block px-2 py-0.5 rounded bg-brand-50 text-brand-700 text-xs font-medium">{{ $employee['status'] }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <button type="button" onclick="deleteEmployee({{ $employee['id'] }})" title="Delete" class="p-2 text-red-600 hover:bg-red-50 rounded transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-sm text-slate-500 text-center">No employees found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
