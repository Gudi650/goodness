<div id="tab-payroll" class="tab-content hidden">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-3">
        <h2 class="text-lg font-semibold font-display">Payroll</h2>
        <div class="flex items-center gap-3">
            <button onclick="openRecordSalaryModal()" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium">Record Salary</button>
            <select id="payrollMonth" class="border border-slate-300 rounded-md px-3 py-2 text-sm">
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04" selected>April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
            <select id="payrollYear" class="border border-slate-300 rounded-md px-3 py-2 text-sm">
                <option value="2024">2024</option>
                <option value="2025">2025</option>
                <option value="2026" selected>2026</option>
            </select>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden mt-3 w-full">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Employee</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Basic Salary</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Deductions</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Net Pay</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Action</th>
                    </tr>
                </thead>
                <tbody id="payrollTable" class="divide-y divide-slate-100">
                    @forelse($salaries ?? [] as $s)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 text-sm">{{ $s['employee'] }}</td>
                            <td class="px-4 py-3 text-sm">{{ number_format($s['basic'], 2) }}</td>
                            <td class="px-4 py-3 text-sm">{{ number_format($s['deductions'], 2) }}</td>
                            <td class="px-4 py-3 text-sm">{{ number_format($s['net'], 2) }}</td>
                            <td class="px-4 py-3 text-sm"><span class="inline-block px-2 py-0.5 rounded bg-brand-50 text-brand-700 text-xs font-medium">{{ $s['status'] }}</span></td>
                            <td class="px-4 py-3 text-sm flex gap-2">
                                <button onclick="editSalary({{ $s['id'] }}, {{ json_encode($s) }})" title="Edit" class="p-2 text-blue-600 hover:bg-blue-50 rounded transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a2.25 2.25 0 1 1 3.182 3.182L10.582 17.13a4.5 4.5 0 0 1-1.897 1.13L6 19l.74-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487ZM16.862 4.487 19.5 7.125" />
                                    </svg>
                                </button>
                                <button onclick="deleteSalary({{ $s['id'] }})" title="Delete" class="p-2 text-red-600 hover:bg-red-50 rounded transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-sm text-slate-500 text-center">No salary records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
