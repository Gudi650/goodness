<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRM - Goodness ERP</title>
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
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
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
            </div>
        </div>

        <div id="tab-employees" class="tab-content">
            <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-lg font-semibold font-display">Employees</h2>
                <button onclick="openAddEmployeeModal()"
                    class="w-full sm:w-auto flex-shrink-0 whitespace-nowrap px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium transition-colors">Add
                    Employee</button>
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
                        <tbody id="employeesTable" class="divide-y divide-slate-100"></tbody>
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
                        <tbody id="attendanceTable" class="divide-y divide-slate-100"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="tab-leave" class="tab-content hidden">
            <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-lg font-semibold font-display">Leave Requests</h2>
                <button onclick="openAddLeaveModal()"
                    class="w-full sm:w-auto flex-shrink-0 whitespace-nowrap px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium transition-colors">Add
                    Leave</button>
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
                        <tbody id="leaveTable" class="divide-y divide-slate-100"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="tab-payroll" class="tab-content hidden">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold font-display">Payroll</h2>
                <div class="flex gap-3">
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
                            </tr>
                        </thead>
                        <tbody id="payrollTable" class="divide-y divide-slate-100"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <div id="addEmployeeModal"
        class="hidden fixed inset-0 bg-slate-900 bg-opacity-40 z-50 flex items-start justify-center pt-20 overflow-y-auto">
        <div class="bg-white rounded-lg shadow-xl border border-slate-200 w-full max-w-lg mx-4 p-6">
            <h2 class="text-lg font-semibold font-display mb-4">Add Employee</h2>
            <div class="space-y-4">
                <input type="text" placeholder="Full Name"
                    class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm" id="empName" />
                <input type="text" placeholder="Department"
                    class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm" id="empDept" />
                <input type="text" placeholder="Position"
                    class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm" id="empPos" />
                <select class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm" id="empCompany">
                    <option>Goodness Tanzania Ltd</option>
                    <option>Goodness Kenya Ltd</option>
                    <option>Goodness Uganda Ltd</option>
                </select>
                <input type="date" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm"
                    id="empJoinDate" />
            </div>
            <div class="flex gap-3 justify-end mt-6">
                <button onclick="closeAddEmployeeModal()"
                    class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm">Cancel</button>
                <button onclick="saveEmployee()"
                    class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm">Save</button>
            </div>
        </div>
    </div>

    <script>
        const employees = [{
                id: 1,
                name: 'Amina Hassan',
                dept: 'HR',
                position: 'HR Manager',
                company: 'Goodness Tanzania Ltd',
                joinDate: '2021-03-15',
                status: 'Active'
            },
            {
                id: 2,
                name: 'Joseph Kimani',
                dept: 'Finance',
                position: 'Accountant',
                company: 'Goodness Kenya Ltd',
                joinDate: '2022-07-20',
                status: 'Active'
            },
            {
                id: 3,
                name: 'Lucy Mwangi',
                dept: 'Operations',
                position: 'Operations Lead',
                company: 'Goodness Uganda Ltd',
                joinDate: '2020-01-10',
                status: 'Active'
            },
            {
                id: 4,
                name: 'David Okoro',
                dept: 'IT',
                position: 'Systems Admin',
                company: 'Goodness Tanzania Ltd',
                joinDate: '2023-05-12',
                status: 'Active'
            },
            {
                id: 5,
                name: 'Sarah Kamau',
                dept: 'Sales',
                position: 'Sales Manager',
                company: 'Goodness Kenya Ltd',
                joinDate: '2021-11-08',
                status: 'Active'
            },
        ];

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
            renderTable(tab);
        }

        function renderTable(tab) {
            if (tab === 'employees') renderEmployees();
            if (tab === 'attendance') renderAttendance();
            if (tab === 'leave') renderLeaves();
            if (tab === 'payroll') renderPayroll();
        }

        function renderEmployees() {
            const tbody = document.getElementById('employeesTable');
            tbody.innerHTML = employees.map(e => `
        <tr class="hover:bg-slate-50">
          <td class="px-4 py-3 text-sm">${e.name}</td>
          <td class="px-4 py-3 text-sm">${e.dept}</td>
          <td class="px-4 py-3 text-sm">${e.position}</td>
          <td class="px-4 py-3 text-sm">${e.company}</td>
          <td class="px-4 py-3 text-sm">${e.joinDate}</td>
          <td class="px-4 py-3 text-sm"><span class="inline-block px-2 py-0.5 rounded bg-green-50 text-green-700 text-xs font-medium">${e.status}</span></td>
        </tr>
      `).join('');
        }

        function renderAttendance() {
            const tbody = document.getElementById('attendanceTable');
            tbody.innerHTML = attendance.map(a => `
        <tr class="hover:bg-slate-50">
          <td class="px-4 py-3 text-sm">${a.employee}</td>
          <td class="px-4 py-3 text-sm">${a.date}</td>
          <td class="px-4 py-3 text-sm">${a.checkin || '-'}</td>
          <td class="px-4 py-3 text-sm">${a.checkout || '-'}</td>
          <td class="px-4 py-3 text-sm">${a.hours}</td>
          <td class="px-4 py-3 text-sm"><span class="inline-block px-2 py-0.5 rounded ${a.status === 'Present' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'} text-xs font-medium">${a.status}</span></td>
        </tr>
      `).join('');
        }

        function renderLeaves() {
            const tbody = document.getElementById('leaveTable');
            tbody.innerHTML = leaves.map(l => `
        <tr class="hover:bg-slate-50">
          <td class="px-4 py-3 text-sm">${l.employee}</td>
          <td class="px-4 py-3 text-sm">${l.type}</td>
          <td class="px-4 py-3 text-sm">${l.from}</td>
          <td class="px-4 py-3 text-sm">${l.to}</td>
          <td class="px-4 py-3 text-sm">${l.days}</td>
          <td class="px-4 py-3 text-sm"><span class="inline-block px-2 py-0.5 rounded ${l.status === 'Approved' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700'} text-xs font-medium">${l.status}</span></td>
          <td class="px-4 py-3 text-sm space-x-2">
            ${l.status === 'Pending' ? `<button onclick="approveLeave(${l.id})" class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded hover:bg-green-100">Approve</button><button onclick="rejectLeave(${l.id})" class="px-2 py-1 bg-red-50 text-red-700 text-xs rounded hover:bg-red-100">Reject</button>` : '-'}
          </td>
        </tr>
      `).join('');
        }

        function renderPayroll() {
            const tbody = document.getElementById('payrollTable');
            tbody.innerHTML = payroll.map(p => `
        <tr class="hover:bg-slate-50">
          <td class="px-4 py-3 text-sm">${p.employee}</td>
          <td class="px-4 py-3 text-sm">TZS ${p.basic.toLocaleString()}</td>
          <td class="px-4 py-3 text-sm">TZS ${p.deductions.toLocaleString()}</td>
          <td class="px-4 py-3 text-sm">TZS ${p.netPay.toLocaleString()}</td>
          <td class="px-4 py-3 text-sm"><span class="inline-block px-2 py-0.5 rounded ${p.status === 'Paid' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700'} text-xs font-medium">${p.status}</span></td>
        </tr>
      `).join('');
        }

        function openAddEmployeeModal() {
            document.getElementById('addEmployeeModal').classList.remove('hidden');
        }

        function closeAddEmployeeModal() {
            document.getElementById('addEmployeeModal').classList.add('hidden');
        }

        function saveEmployee() {
            const name = document.getElementById('empName').value;
            if (name) {
                employees.push({
                    id: employees.length + 1,
                    name: name,
                    dept: document.getElementById('empDept').value,
                    position: document.getElementById('empPos').value,
                    company: document.getElementById('empCompany').value,
                    joinDate: document.getElementById('empJoinDate').value,
                    status: 'Active'
                });
                renderEmployees();
                closeAddEmployeeModal();
                document.getElementById('empName').value = '';
                document.getElementById('empDept').value = '';
                document.getElementById('empPos').value = '';
                document.getElementById('empJoinDate').value = '';
            }
        }

        function openAddLeaveModal() {
            const empOptions = employees.map(e => `<option value="${e.name}">${e.name}</option>`).join('');
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
                renderLeaves();
                window.closeModal();
                window.showAlert('success', 'Leave request added');
                return true;
            });
        }

        function approveLeave(id) {
            const leave = leaves.find(l => l.id === id);
            if (leave) leave.status = 'Approved';
            renderLeaves();
        }

        function rejectLeave(id) {
            const leave = leaves.find(l => l.id === id);
            if (leave) leave.status = 'Rejected';
            renderLeaves();
        }

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
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
    @include('components.alert')
    @include('components.confirm')
</body>

</html>
