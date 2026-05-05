<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HRM - Goodness ERP</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        nav,
        button {
            font-family: 'Outfit', sans-serif;
        }

        .mono {
            font-family: ui-monospace, monospace;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fff8e5',
                            100: '#fde6a1',
                            500: '#f0b73a',
                            600: '#eaa106',
                            700: '#c88600',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-slate-50 text-slate-800">
    @include('components.topbar')
    @include('components.sidebar')

    <main class="ml-0 lg:ml-64 pt-20 p-6">
        <div class="bg-white border-b border-slate-200 -mx-6 px-6 mb-6">
            <div class="flex gap-8">
                <button onclick="switchTab('employees', this)"
                    class="tab-btn active py-4 text-sm font-medium text-slate-700 border-b-2 border-brand-600 cursor-pointer">Employees</button>
                <button onclick="switchTab('attendance', this)"
                    class="tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer">Attendance</button>
                <button onclick="switchTab('leave', this)"
                    class="tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer">Leave</button>
                <button onclick="switchTab('payroll', this)"
                    class="tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer">Payroll</button>
                <button onclick="switchTab('departments', this)"
                    class="tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer">Departments</button>
            </div>
        </div>

            <div id="recordSalaryModal"
                class="hidden fixed inset-0 bg-slate-900 bg-opacity-40 z-50 flex items-start justify-center pt-20 overflow-y-auto">
                <div class="bg-white rounded-lg shadow-xl border border-slate-200 w-full max-w-lg mx-4 p-6">
                    <h2 class="text-lg font-semibold font-display mb-4" id="modalTitle">Record Salary</h2>
                    <form id="salaryForm" method="POST" action="{{ route('payroll.store') }}" class="space-y-4" onsubmit="return showSalaryLoader(event)">
                        <input type="hidden" id="editingId" name="editing_id" value="">
                        @csrf
                        <div class="space-y-3">
                            <label class="block text-sm text-slate-600">Employee
                                @php $recorded = collect($salaries ?? [])->pluck('user_id')->all(); @endphp
                                <select name="user_id" required class="mt-1 block w-full border border-slate-300 rounded p-2 text-sm">
                                    <option value="">Select employee</option>
                                    @foreach ($employees as $emp)
                                        @if(!in_array($emp['id'], $recorded))
                                            <option value="{{ $emp['id'] }}">{{ $emp['name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </label>

                            <label class="block text-sm text-slate-600">Basic Salary
                                <input id="basicSalary" name="amount" type="number" step="0.01" min="0" required class="mt-1 block w-full border border-slate-300 rounded p-2 text-sm" />
                            </label>

                            <label class="block text-sm text-slate-600">Deductions
                                <input id="deductions" name="deductions" type="number" step="0.01" min="0" class="mt-1 block w-full border border-slate-300 rounded p-2 text-sm" value="0" />
                            </label>

                            <label class="block text-sm text-slate-600">Net Salary
                                <input id="netSalary" name="net_amount" type="number" step="0.01" min="0" required class="mt-1 block w-full border border-slate-300 rounded p-2 text-sm bg-slate-50" readonly />
                            </label>

                            <div class="grid grid-cols-2 gap-3">
                                <label class="block text-sm text-slate-600">Currency
                                    <input name="currency" type="text" class="mt-1 block w-full border border-slate-300 rounded p-2 text-sm" value="USD" />
                                </label>
                                <label class="block text-sm text-slate-600">Effective Date
                                    <input name="effective_date" type="date" class="mt-1 block w-full border border-slate-300 rounded p-2 text-sm" />
                                </label>
                            </div>

                            <label class="block text-sm text-slate-600">Notes (optional)
                                <textarea name="notes" rows="3" class="mt-1 block w-full border border-slate-300 rounded p-2 text-sm"></textarea>
                            </label>
                        </div>

                        <div class="flex gap-3 justify-end pt-2">
                        <button type="button" onclick="closeRecordSalaryModal()" class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm">Cancel</button>
                        <button type="submit" id="salarySubmitBtn" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm">Save</button>
                    </div>
                    </form>
                <x-loading id="salaryLoader" size="lg" color="amber" full-page="true" message="Saving salary..." :show="false" />
                </div>
            </div>

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
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Name</th>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Department</th>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Position</th>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Company</th>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Join Date</th>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Status</th>
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-sm text-slate-500 text-center">No employees found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="tab-attendance" class="tab-content hidden">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold font-display">Attendance Records</h2>
                <input type="date" id="attendanceFilter"
                    class="border border-slate-300 rounded-md px-3 py-2 text-sm" />
            </div>
            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Employee</th>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Date</th>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Check-in</th>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Check-out</th>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Hours</th>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Status</th>
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

        <div id="tab-leave" class="tab-content hidden">
            <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-lg font-semibold font-display">Leave Requests</h2>
                <button onclick="openAddLeaveModal()"
                    class="w-full sm:w-auto flex-shrink-0 whitespace-nowrap px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium transition-colors">Add
                    Leave
                </button>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Employee</th>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Type</th>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    From</th>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    To</th>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Days</th>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Status</th>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Actions</th>
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

        <div id="tab-payroll" class="tab-content hidden">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-3">
                <h2 class="text-lg font-semibold font-display">Payroll</h2>
                <div class="flex items-center gap-3">
                    <button onclick="openRecordSalaryModal()"
                        class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium">Record Salary</button>
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
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Employee</th>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Basic Salary</th>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Deductions</th>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Net Pay</th>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Status</th>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Action</th>
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

        <div id="tab-departments" class="tab-content hidden">
            <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-lg font-semibold font-display">Departments</h2>
                <button onclick="openAddDepartmentModal()"
                    class="w-full sm:w-auto flex-shrink-0 whitespace-nowrap px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium transition-colors">Add
                    Department</button>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Name</th>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Description</th>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Company</th>
                                <th
                                    class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">
                                    Created</th>
                            </tr>
                        </thead>
                        <tbody id="departmentsTable" class="divide-y divide-slate-100">
                            @forelse($departments as $department)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 text-sm font-medium">{{ $department['name'] }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $department['description'] }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $department['company_name'] }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $department['created_at'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-sm text-slate-500 text-center">No departments found. Create your first department.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <div id="addDepartmentModal"
        class="hidden fixed inset-0 bg-slate-900 bg-opacity-40 z-50 flex items-start justify-center pt-20 overflow-y-auto">
        <div class="bg-white rounded-lg shadow-xl border border-slate-200 w-full max-w-lg mx-4 p-6">
            <h2 class="text-lg font-semibold font-display mb-4">Add Department</h2>
            <form id="departmentForm" method="POST" action="{{ route('departments.store') }}" class="space-y-6">
                @csrf
                <div class="space-y-4">
                    @if ($isAdmin)
                        <select name="company_id"
                            class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600"
                            required>
                            <option value="">Select Company</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}" @selected((string) $activeCompanyId === (string) $company->id)>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <input type="hidden" name="company_id" value="{{ auth()->user()?->company_id }}">
                    @endif
                    <input type="text" name="name" placeholder="Department Name"
                        class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600" id="deptName" required />
                    <textarea name="description" placeholder="Description (optional)"
                        class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600" id="deptDesc" rows="3"></textarea>
                </div>

                <div class="flex gap-3 justify-end pt-2">
                    <button type="button" onclick="closeAddDepartmentModal()"
                        class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm">Save</button>
                </div>
            </form>
        </div>
    </div>

    <div id="addEmployeeModal"
        class="hidden fixed inset-0 bg-slate-900 bg-opacity-40 z-50 flex items-start justify-center pt-20 overflow-y-auto">
        <div class="bg-white rounded-lg shadow-xl border border-slate-200 w-full max-w-lg mx-4 p-6">
            <h2 class="text-lg font-semibold font-display mb-4">Add Employee</h2>
            <form id="employeeForm" method="POST" action="{{ route('employees.store') }}" class="space-y-6" onsubmit="return showEmployeeLoader(event)">
                @csrf
                <div class="space-y-4">
                    <input type="text" name="name" placeholder="Full Name"
                        class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600" id="empName" required />
                    
                    <input type="email" name="email" placeholder="Email Address"
                        class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600" id="empEmail" required />
                    
                    <input type="tel" name="phone_number" placeholder="Phone Number "
                        class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600" id="empPhone" />
                    
                    @if ($isAdmin)
                    
                        <select name="company_id" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600" id="empCompany" required>
                            <option value="">Select Company</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}" @selected((string) $activeCompanyId === (string) $company->id)>{{ $company->name }}</option>
                            @endforeach
                        </select>

                    @else

                        <input type="hidden" name="company_id" value="{{ auth()->user()?->company_id }}">
                        <div class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm bg-slate-50 text-slate-600">
                            {{ auth()->user()?->company?->name ?? 'No company assigned' }}
                        </div>

                    @endif

                    <select name="department_id" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600" id="empDept">
                        <option value="">Select Department</option>
                    </select>
                    <input type="date" name="join_date" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600"
                        id="empJoinDate" />
                </div>
                <div class="flex gap-3 justify-end pt-2">
                    <button type="button" onclick="closeAddEmployeeModal()"
                        class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm">Cancel</button>
                    <button type="submit" id="employeeSubmitBtn"
                        class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm">Save</button>
                </div>
            </form>
            
            <x-loading id="employeeLoader" size="lg" color="amber" full-page="true" message="Saving employee details..." :show="false" />
        </div>
    </div>

    <div id="bulkImportModal"
        class="hidden fixed inset-0 bg-slate-900 bg-opacity-40 z-50 flex items-start justify-center pt-20 overflow-y-auto">
        <div class="bg-white rounded-lg shadow-xl border border-slate-200 w-full max-w-2xl mx-4 p-6">
            <h2 class="text-lg font-semibold font-display mb-2">Bulk Import Employees</h2>
            <p class="text-sm text-slate-600 mb-4">Upload a CSV file with columns: Name, Email, Phone Number, Department, Join Date (YYYY-MM-DD)</p>
            
            <div id="importStep1" class="space-y-4">
                <div class="bg-slate-50 border-2 border-dashed border-slate-300 rounded-lg p-6 text-center cursor-pointer" id="dragDropZone">
                    <input type="file" id="csvFileInput" accept=".csv,.txt" class="hidden" onchange="handleFileSelect(event)">
                    <p id="dragDropPrompt" class="text-sm text-slate-600">Drag CSV file here or <span class="text-brand-600 font-medium">click to browse</span></p>
                    <p id="selectedCsvFile" class="hidden text-sm text-brand-600 font-medium"></p>
                    <p class="text-xs text-slate-500 mt-2">CSV format: Name, Email, Phone, Department, Join Date</p>
                </div>

                <div id="importStatus" class="hidden rounded-md border px-3 py-2 text-sm"></div>

                @if ($isAdmin)
                    <select id="bulkImportCompany" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600" required>
                        <option value="">Select Company</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}" @selected((string) $activeCompanyId === (string) $company->id)>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                @else
                    <input type="hidden" id="bulkImportCompany" value="{{ auth()->user()?->company_id }}">
                    <div class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm bg-slate-50 text-slate-600">
                        {{ auth()->user()?->company?->name ?? 'No company assigned' }}
                    </div>
                @endif

                <button type="button" id="previewBtn" onclick="previewImport()" disabled
                    class="w-full px-4 py-2 bg-brand-600 hover:bg-brand-700 disabled:bg-slate-300 text-white rounded-md text-sm font-medium transition-colors">Preview & Validate</button>
            </div>

            <div id="importStep2" class="hidden space-y-4">
                <div id="previewResults" class="space-y-3">
                    <div id="validRowsPreview" class="hidden">
                        <h3 class="text-sm font-medium text-slate-700 mb-2"> Valid Rows (<span id="validCount">0</span>)</h3>
                        <div id="validRowsList" class="bg-slate-50 rounded p-3 max-h-40 overflow-y-auto text-xs text-slate-600 space-y-1"></div>
                    </div>

                    <div id="errorRowsPreview" class="hidden">
                        <h3 class="text-sm font-medium text-slate-700 mb-2"> Error Rows (<span id="errorCount">0</span>)</h3>
                        <div id="errorRowsList" class="bg-red-50 border border-red-200 rounded p-3 max-h-40 overflow-y-auto text-xs text-red-600 space-y-1"></div>
                    </div>

                    <div id="previewMessage" class="text-sm text-slate-600 bg-blue-50 border border-blue-200 p-3 rounded"></div>
                </div>

                <div class="flex gap-3 justify-end pt-2">
                    <button type="button" onclick="backToUpload()"
                        class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm">Back</button>
                    <button type="button" id="confirmBtn" onclick="confirmImport()" disabled
                        class="px-4 py-2 bg-brand-600 hover:bg-brand-700 disabled:bg-slate-300 text-white rounded-md text-sm font-medium">Confirm Import</button>
                </div>
            </div>

            <div id="importStep3" class="hidden text-center space-y-4">
                <div id="importResult" class="bg-slate-50 rounded p-4 text-sm"></div>
                <button type="button" onclick="closeBulkImportModal()"
                    class="w-full px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm">Close</button>
            </div>

            <div class="flex gap-3 justify-end pt-4 border-t border-slate-200 mt-6">
                <button type="button" onclick="closeBulkImportModal()"
                    class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm">Cancel</button>
            </div>

            <x-loading id="importLoader" size="lg" color="amber" full-page="true" message="Processing..." :show="false" />

        </div>
    </div>

    <script>
        const employeeNames = @json($employeeNames);
        const departments = @json($departments);
        const departmentOptions = @json($departmentOptions);
        let previewedData = null;

        function getDragDropZone() {
            return document.getElementById('dragDropZone');
        }

        function setImportStatus(message, type = 'info') {
            const status = document.getElementById('importStatus');
            if (!status) return;

            const styles = {
                info: 'border-blue-200 bg-blue-50 text-blue-700',
                success: 'border-emerald-200 bg-emerald-50 text-emerald-700',
                error: 'border-red-200 bg-red-50 text-red-700',
            };

            status.className = `rounded-md border px-3 py-2 text-sm ${styles[type] || styles.info}`;
            status.textContent = message;
            status.classList.remove('hidden');
        }

        function clearImportStatus() {
            const status = document.getElementById('importStatus');
            if (!status) return;
            status.textContent = '';
            status.classList.add('hidden');
        }

        function setImportLoading(isLoading, message = '') {
            const loader = document.getElementById('importLoader');
            const loaderText = document.getElementById('importLoaderText');
            const previewBtn = document.getElementById('previewBtn');
            const confirmBtn = document.getElementById('confirmBtn');

            if (loader) {
                loader.classList.toggle('hidden', !isLoading);
                loader.classList.toggle('flex', isLoading);
            }

            if (loaderText && message) {
                loaderText.textContent = message;
            }

            if (previewBtn) {
                previewBtn.disabled = isLoading;
            }

            if (confirmBtn) {
                confirmBtn.disabled = isLoading;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const dragDropZone = getDragDropZone();

            if (!dragDropZone) return;

            dragDropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dragDropZone.classList.add('bg-brand-50', 'border-brand-400');
            });

            dragDropZone.addEventListener('dragleave', () => {
                dragDropZone.classList.remove('bg-brand-50', 'border-brand-400');
            });

            dragDropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dragDropZone.classList.remove('bg-brand-50', 'border-brand-400');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    document.getElementById('csvFileInput').files = files;
                    handleFileSelect({ target: { files } });
                }
            });

            dragDropZone.addEventListener('click', () => {
                document.getElementById('csvFileInput').click();
            });
        });

        function handleFileSelect(event) {
            const files = event.target.files;
            if (files.length > 0) {
                document.getElementById('previewBtn').disabled = false;
                clearImportStatus();

                const selectedCsvFile = document.getElementById('selectedCsvFile');
                const dragDropPrompt = document.getElementById('dragDropPrompt');

                if (selectedCsvFile) {
                    selectedCsvFile.textContent = `✓ ${files[0].name}`;
                    selectedCsvFile.classList.remove('hidden');
                }

                if (dragDropPrompt) {
                    dragDropPrompt.classList.add('hidden');
                }
            }
        }

        function openBulkImportModal() {
            document.getElementById('bulkImportModal').classList.remove('hidden');
            const csvFileInput = document.getElementById('csvFileInput');
            if (csvFileInput) {
                csvFileInput.value = '';
            }
            document.getElementById('previewBtn').disabled = true;
            setImportLoading(false);
            clearImportStatus();

            const selectedCsvFile = document.getElementById('selectedCsvFile');
            const dragDropPrompt = document.getElementById('dragDropPrompt');

            if (selectedCsvFile) {
                selectedCsvFile.textContent = '';
                selectedCsvFile.classList.add('hidden');
            }

            if (dragDropPrompt) {
                dragDropPrompt.classList.remove('hidden');
            }
            document.getElementById('csvFileInput').value = '';
            document.getElementById('previewBtn').disabled = true;
            setImportLoading(false);
            clearImportStatus();
            const dragDropZone = getDragDropZone();
            if (dragDropZone) {
                dragDropZone.innerHTML = `<p class="text-sm text-slate-600">Drag CSV file here or <span class="text-brand-600 font-medium">click to browse</span></p>
                    <p class="text-xs text-slate-500 mt-2">CSV format: Name, Email, Phone, Department, Join Date</p>`;
            }
            previewedData = null;
        }

        function previewImport() {
            const fileInput = document.getElementById('csvFileInput');
            if (!fileInput) {
                setImportStatus('CSV file input was not found. Reopen the import modal and try again.', 'error');
                if (window.showAlert) window.showAlert('error', 'CSV file input was not found.');
                return;
            }

            const file = fileInput.files && fileInput.files[0] ? fileInput.files[0] : null;
            const companyId = document.getElementById('bulkImportCompany').value;

            if (!file) {
                setImportStatus('Please select a CSV file first.', 'error');
                if (window.showAlert) window.showAlert('error', 'Please select a CSV file');
                return;
            }

            if (!companyId) {
                setImportStatus('Please select a company first.', 'error');
                if (window.showAlert) window.showAlert('error', 'Please select a company');
                return;
            }

            const formData = new FormData();
            formData.append('csv_file', file);
            formData.append('company_id', companyId);

            setImportLoading(true, 'Validating CSV...');
            setImportStatus('Validating file and checking rows...', 'info');

            fetch('{{ route("bulk-import.preview") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData,
            })
            .then(async response => {
                const text = await response.text();
                let payload = {};

                try {
                    payload = text ? JSON.parse(text) : {};
                } catch (error) {
                    payload = { message: text || 'Invalid server response.' };
                }

                if (!response.ok) {
                    throw new Error(payload.message || 'Preview failed');
                }

                return payload;
            })
            .then(data => {
                setImportLoading(false);

                if (data.success) {
                    previewedData = data.valid_rows;
                    displayPreview(data);
                    document.getElementById('importStep1').classList.add('hidden');
                    document.getElementById('importStep2').classList.remove('hidden');
                    setImportStatus(`Preview completed: ${data.valid_count} valid, ${data.error_count} errors.`, 'success');
                    if (data.valid_count > 0) {
                        document.getElementById('confirmBtn').disabled = false;
                    }
                } else {
                    setImportStatus(data.message || 'Preview failed', 'error');
                    if (window.showAlert) window.showAlert('error', data.message || 'Preview failed');
                }
            })
            .catch(error => {
                setImportLoading(false);
                setImportStatus(error.message || 'Preview request failed.', 'error');
                if (window.showAlert) window.showAlert('error', 'Error: ' + error.message);
            });
        }

        function displayPreview(data) {
            document.getElementById('previewMessage').innerHTML = `<strong>Summary:</strong> ${data.message}`;

            if (data.valid_count > 0) {
                document.getElementById('validCount').textContent = data.valid_count;
                document.getElementById('validRowsList').innerHTML = data.valid_rows
                    .map((row, idx) => `
                        <div>${idx + 1}. <strong>${row.name}</strong> (${row.email}) - Dept: ${row.department_name || 'None'}</div>
                    `)
                    .join('');
                document.getElementById('validRowsPreview').classList.remove('hidden');
            }

            if (data.error_count > 0) {
                document.getElementById('errorCount').textContent = data.error_count;
                document.getElementById('errorRowsList').innerHTML = data.error_rows
                    .map(row => `
                        <div><strong>Line ${row.line}:</strong> ${row.data.name || row.data.email} - ${row.errors.join('; ')}</div>
                    `)
                    .join('');
                document.getElementById('errorRowsPreview').classList.remove('hidden');
            }
        }

        function confirmImport() {
            if (!previewedData || previewedData.length === 0) {
                setImportStatus('No valid rows to import.', 'error');
                if (window.showAlert) window.showAlert('error', 'No valid rows to import');
                return;
            }

            setImportLoading(true, 'Importing employees...');
            setImportStatus('Importing the valid rows now...', 'info');

            fetch('{{ route("bulk-import.confirm") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ valid_rows: previewedData }),
            })
            .then(async response => {
                const text = await response.text();
                let payload = {};

                try {
                    payload = text ? JSON.parse(text) : {};
                } catch (error) {
                    payload = { message: text || 'Invalid server response.' };
                }

                if (!response.ok) {
                    throw new Error(payload.message || 'Import failed');
                }

                return payload;
            })
            .then(data => {
                setImportLoading(false);
                document.getElementById('importStep2').classList.add('hidden');
                document.getElementById('importStep3').classList.remove('hidden');

                const resultHtml = `
                    <div class="text-left">
                        <h3 class="font-semibold text-lg mb-2">${data.success ? ' Import Complete!' : '❌ Import Failed'}</h3>
                        <p class="mb-3"><strong>${data.message}</strong></p>
                        <ul class="text-sm space-y-1">
                            <li>📥 Imported: <strong>${data.imported}</strong></li>
                            <li>⏭️ Skipped: <strong>${data.skipped}</strong></li>
                            ${data.errors && data.errors.length > 0 ? '<li> Errors: ' + data.errors.join('; ') + '</li>' : ''}
                        </ul>
                    </div>
                `;
                document.getElementById('importResult').innerHTML = resultHtml;

                if (window.showAlert) {
                    if (data.success && data.imported > 0 && data.skipped > 0) {
                        window.showAlert('warning', `${data.imported} employees imported and ${data.skipped} existing record(s) skipped.`);
                    } else if (data.success && data.imported > 0) {
                        window.showAlert('success', `Successfully imported ${data.imported} employees!`);
                    } else if (data.success && data.skipped > 0) {
                        window.showAlert('warning', `${data.skipped} employee record(s) were skipped because they already existed.`);
                    } else if (data.success) {
                        window.showAlert('info', data.message || 'Import completed.');
                    }
                }

                if (data.success && data.imported > 0) {
                    // Reload page to show new employees
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }
            })
            .catch(error => {
                setImportLoading(false);
                setImportStatus(error.message || 'Import request failed.', 'error');
                if (window.showAlert) window.showAlert('error', 'Error: ' + error.message);
            });
        }

        function backToUpload() {
            document.getElementById('importStep2').classList.add('hidden');
            document.getElementById('importStep1').classList.remove('hidden');
            previewedData = null;
        }

        // ===== ORIGINAL SCRIPT CONTENT =====
        const attendance = [{
                id: 1,
                employee: 'Amina Hassan',
                date: '2026-04-30',
                checkin: '08:15',
                checkout: '17:45',
                hours: '9.5',
                status: 'Present'
            },
            {
                id: 2,
                employee: 'Joseph Kimani',
                date: '2026-04-30',
                checkin: '08:00',
                checkout: '17:00',
                hours: '9',
                status: 'Present'
            },
            {
                id: 3,
                employee: 'Lucy Mwangi',
                date: '2026-04-30',
                checkin: '',
                checkout: '',
                hours: '-',
                status: 'Absent'
            },
        ];

        const leaves = [{
                id: 1,
                employee: 'Amina Hassan',
                type: 'Annual Leave',
                from: '2026-05-01',
                to: '2026-05-05',
                days: 5,
                status: 'Pending'
            },
            {
                id: 2,
                employee: 'Joseph Kimani',
                type: 'Sick Leave',
                from: '2026-04-28',
                to: '2026-04-29',
                days: 2,
                status: 'Approved'
            },
        ];

        const payroll = [{
                id: 1,
                employee: 'Amina Hassan',
                basic: 2500000,
                deductions: 375000,
                netPay: 2125000,
                status: 'Paid'
            },
            {
                id: 2,
                employee: 'Joseph Kimani',
                basic: 1800000,
                deductions: 270000,
                netPay: 1530000,
                status: 'Pending'
            },
            {
                id: 3,
                employee: 'Lucy Mwangi',
                basic: 2200000,
                deductions: 330000,
                netPay: 1870000,
                status: 'Paid'
            },
        ];

        function switchTab(tab, btnEl) {
            document.querySelectorAll('.tab-content').forEach(t => t.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('border-brand-600', 'text-slate-700', 'border-b-2');
                b.classList.add('text-slate-500');
            });
            const content = document.getElementById('tab-' + tab);
            if (content) content.classList.remove('hidden');
            if (!btnEl) btnEl = document.querySelector('.tab-btn');
            if (btnEl) {
                btnEl.classList.add('border-b-2', 'border-brand-600', 'text-slate-700');
                btnEl.classList.remove('text-slate-500');
            }
        }

        function openAddEmployeeModal() {
            populateDepartmentDropdown();
            const companySelect = document.getElementById('empCompany');
            if (companySelect) {
                companySelect.onchange = populateDepartmentDropdown;
            }
            document.getElementById('addEmployeeModal').classList.remove('hidden');
        }

        function closeAddEmployeeModal() {
            document.getElementById('addEmployeeModal').classList.add('hidden');
        }

        function showEmployeeLoader(event) {
            if (event) {
                event.preventDefault();
            }

            const loader = document.getElementById('employeeLoader');
            const submitBtn = document.getElementById('employeeSubmitBtn');
            const form = document.getElementById('employeeForm');

            if (loader) {
                loader.classList.remove('hidden');
                loader.classList.add('flex');
            }

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Saving...';
            }

            if (form) {
                window.setTimeout(() => {
                    form.submit();
                }, 75);
            }

            return true;
        }

        function saveEmployee() {
            const name = document.getElementById('empName').value;
            if (name) {
                window.showAlert('info', 'Employee save is now backend-driven. Connect this form to a POST endpoint next.');
                closeAddEmployeeModal();
                document.getElementById('empName').value = '';
                document.getElementById('empDept').value = '';
                document.getElementById('empJoinDate').value = '';
            } else {
                window.showAlert('error', 'Full name is required.');
            }
        }

        function populateDepartmentDropdown() {
            const deptSelect = document.getElementById('empDept');
            const companySelect = document.getElementById('empCompany');
            if (!deptSelect) {
                return;
            }

            let selectedCompanyId = '';
            
            if (companySelect) {
                // For admins: use the selected dropdown value
                selectedCompanyId = companySelect.value;
            } else {
                // For non-admins: get the company_id from the hidden input
                const hiddenCompanyInput = document.querySelector('input[name="company_id"]');
                if (hiddenCompanyInput) {
                    selectedCompanyId = hiddenCompanyInput.value;
                }
            }
            
            deptSelect.innerHTML = '<option value="">Select Department</option>';

            departmentOptions
                .filter(dept => !selectedCompanyId || String(dept.company_id) === String(selectedCompanyId))
                .forEach(dept => {
                    const option = document.createElement('option');
                    option.value = dept.id;
                    option.textContent = dept.name;
                    deptSelect.appendChild(option);
                });
        }

        function openAddLeaveModal() {
            const empOptions = employeeNames.map(name => `<option value="${name}">${name}</option>`).join('');
            const body = `
                <div class="space-y-4">
                    <label class="block text-sm text-slate-600">Employee
                        <select id="leave_employee" class="mt-1 block w-full border border-slate-200 rounded p-2">${empOptions}</select>
                    </label>
                    <label class="block text-sm text-slate-600">Type
                        <select id="leave_type" class="mt-1 block w-full border border-slate-200 rounded p-2"><option>Annual Leave</option><option>Sick Leave</option><option>Other</option></select>
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="block text-sm text-slate-600">From<input id="leave_from" type="date" class="mt-1 block w-full border border-slate-200 rounded p-2" /></label>
                        <label class="block text-sm text-slate-600">To<input id="leave_to" type="date" class="mt-1 block w-full border border-slate-200 rounded p-2" /></label>
                    </div>
                    <label class="block text-sm text-slate-600">Days<input id="leave_days" type="number" min="0" class="mt-1 block w-full border border-slate-200 rounded p-2" /></label>
                </div>
            `;
            window.openModal('Add Leave', body, () => {
                const employee = document.getElementById('leave_employee').value;
                const type = document.getElementById('leave_type').value;
                const from = document.getElementById('leave_from').value;
                const to = document.getElementById('leave_to').value;
                let days = Number(document.getElementById('leave_days').value) || 0;
                if (!days && from && to) {
                    const diff = (new Date(to) - new Date(from)) / (1000 * 60 * 60 * 24);
                    days = Math.max(1, Math.round(diff) + 1);
                }
                if (!employee) { window.showAlert('error', 'Employee is required'); return false; }
                leaves.push({ id: Date.now(), employee, type, from, to, days, status: 'Pending' });
                window.closeModal();
                window.showAlert('success', 'Leave request added');
                return true;
            });
        }

        function approveLeave(id) {
            const leave = leaves.find(l => l.id === id);
            if (leave) leave.status = 'Approved';
            window.showAlert('info', 'Leave status update will reflect after backend integration for leave records.');
        }

        function rejectLeave(id) {
            const leave = leaves.find(l => l.id === id);
            if (leave) leave.status = 'Rejected';
            window.showAlert('info', 'Leave status update will reflect after backend integration for leave records.');
        }

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
        }

        function openAddDepartmentModal() {
            document.getElementById('addDepartmentModal').classList.remove('hidden');
        }

        function closeAddDepartmentModal() {
            document.getElementById('addDepartmentModal').classList.add('hidden');
        }

        function openRecordSalaryModal() {
            document.getElementById('recordSalaryModal').classList.remove('hidden');
        }

        function closeRecordSalaryModal() {
            const form = document.getElementById('salaryForm');
            if (form) form.reset();
            
            // Reset form to create mode
            document.getElementById('editingId').value = '';
            document.getElementById('modalTitle').textContent = 'Record Salary';
            form.action = '{{ route('payroll.store') }}';
            form.method = 'POST';
            
            // Remove PUT method override
            const methodInput = document.querySelector('input[name="_method"]');
            if (methodInput) {
                methodInput.remove();
            }
            
            // Re-enable employee select
            const empSelect = document.querySelector('select[name="user_id"]');
            if (empSelect) {
                empSelect.disabled = false;
            }
            
            document.getElementById('recordSalaryModal').classList.add('hidden');
        }

        // Auto-calc net salary = basic - deductions
        function calcNetSalary() {
            const basicEl = document.getElementById('basicSalary');
            const dedEl = document.getElementById('deductions');
            const netEl = document.getElementById('netSalary');
            if (!basicEl || !dedEl || !netEl) return;
            const basic = parseFloat(basicEl.value) || 0;
            const ded = parseFloat(dedEl.value) || 0;
            const net = Math.max(0, basic - ded);
            netEl.value = net.toFixed(2);
        }

        document.addEventListener('input', (e) => {
            if (e.target && (e.target.id === 'basicSalary' || e.target.id === 'deductions')) {
                calcNetSalary();
            }
        });

        function showSalaryLoader(event) {
            if (event) event.preventDefault();
            const loader = document.getElementById('salaryLoader');
            const submitBtn = document.getElementById('salarySubmitBtn');
            const form = document.getElementById('salaryForm');

            if (loader) {
                loader.classList.remove('hidden');
                loader.classList.add('flex');
            }

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Saving...';
            }

            if (form) {
                // small timeout to allow loader to render
                window.setTimeout(() => form.submit(), 75);
            }

            return true;
        }


        function editSalary(id, salaryData) {
            // Populate the modal with salary data
            document.getElementById('editingId').value = id;
            document.getElementById('modalTitle').textContent = 'Edit Salary';
            
            // Pre-fill form fields
            document.getElementById('basicSalary').value = salaryData.basic;
            document.getElementById('deductions').value = salaryData.deductions;
            document.getElementById('netSalary').value = salaryData.net;
            
            // Find and select the employee
            const empSelect = document.querySelector('select[name="user_id"]');
            if (empSelect) {
                // Check if the option already exists
                let option = empSelect.querySelector(`option[value="${salaryData.user_id}"]`);
                if (!option) {
                    // Create the option if it doesn't exist (employee was filtered out)
                    option = document.createElement('option');
                    option.value = salaryData.user_id;
                    option.textContent = salaryData.employee; // Use the employee name from salaryData
                    empSelect.appendChild(option);
                }
                empSelect.value = salaryData.user_id;
                empSelect.disabled = true; // Prevent changing employee
            }
            
            // Set effective date
            const dateInput = document.querySelector('input[name="effective_date"]');
            if (dateInput && salaryData.effective_date !== '-') {
                dateInput.value = salaryData.effective_date;
            }
            
            // Update form action to use update route
            const form = document.getElementById('salaryForm');
            form.action = '/payroll/' + id;
            form.method = 'POST';
            
            // Add PUT method override
            let methodInput = document.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = 'PUT';
            
            // Open the modal
            openRecordSalaryModal();
        }

        function deleteSalary(id) {
            if (typeof window.openConfirm !== 'function') {
                return;
            }

            window.openConfirm({
                title: 'Delete salary record',
                message: 'This action cannot be undone. Do you want to continue?',
                confirmText: 'Delete',
                cancelText: 'Cancel',
                variant: 'danger',
                onConfirm: () => {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/payroll/' + id;

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';

                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    tokenInput.value = csrfToken;

                    form.appendChild(methodInput);
                    form.appendChild(tokenInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '/login';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            switchTab('employees', document.querySelector('.tab-btn'));
        });
    </script>
    @include('components.modal')
    <x-alert />
    @include('components.confirm')
</body>

</html>
