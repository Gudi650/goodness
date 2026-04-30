<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HRM - Goodness ERP</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
    h1, h2, h3, nav, button { font-family: 'Outfit', sans-serif; }
    .mono { font-family: ui-monospace, monospace; }
  </style>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              50:  '#f0fdf4',
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
  <div class="fixed top-0 left-0 right-0 h-16 bg-white border-b border-slate-200 z-50 flex items-center px-6 gap-4">
    <button onclick="toggleSidebar()" class="lg:hidden p-2 hover:bg-slate-100 rounded-md text-slate-600">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>
    <h1 class="text-base font-semibold font-display">Human Resources</h1>
    <div class="ml-auto flex items-center gap-4">
      <select class="border border-slate-300 rounded-md text-sm px-3 py-1.5 text-slate-700 bg-white focus:ring-2 focus:ring-green-500 focus:outline-none">
        <option>Goodness Tanzania Ltd</option>
        <option>Goodness Kenya Ltd</option>
        <option>Goodness Uganda Ltd</option>
      </select>
      <button onclick="logout()" class="text-sm text-slate-500 hover:text-red-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
      </button>
    </div>
  </div>
  @include('components.topbar')

  <div id="sidebar" class="fixed left-0 top-16 w-64 h-screen bg-white border-r border-slate-200 flex flex-col transition-transform duration-300 z-40 lg:translate-x-0 -translate-x-full lg:top-0 overflow-y-auto">
    <div class="p-4 border-b border-slate-200">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-brand-600 rounded-md flex items-center justify-center text-white font-bold text-sm font-display">GG</div>
        <div>
          <p class="font-semibold text-slate-800 font-display text-sm">Goodness Group</p>
          <p class="text-xs text-slate-500">Enterprise</p>
        </div>
      </div>
    </div>
    <nav class="flex-1 px-2 py-4 space-y-1">
      <a href="/dashboard" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 rounded-md">Dashboard</a>
      <a href="/companies" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 rounded-md">Companies</a>
      <a href="/users" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 rounded-md">Users</a>
      <a href="/finance" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 rounded-md">Finance</a>
      <a href="/hrm" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium bg-green-50 text-green-700 border-l-4 border-green-600 rounded-r-md">HRM</a>
      <a href="/sales" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 rounded-md">Sales</a>
      <a href="/inventory" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 rounded-md">Inventory</a>
      <a href="/reports" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 rounded-md">Reports</a>
    </nav>
    <div class="p-4 border-t border-slate-200">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-8 h-8 bg-brand-600 rounded-full flex items-center justify-center text-white font-bold text-xs font-display">JD</div>
        <div>
          <p class="text-sm font-medium text-slate-800">John Doe</p>
          <p class="text-xs text-slate-500">Administrator</p>
        </div>
      </div>
    </div>
  </div>
  @include('components.sidebar')

  <main class="ml-0 lg:ml-64 pt-16 p-6">
    <div class="bg-white border-b border-slate-200 -mx-6 -mt-6 px-6 mb-6">
      <div class="flex gap-8">
        <button onclick="switchTab('employees')" class="tab-btn active py-4 text-sm font-medium text-slate-700 border-b-2 border-brand-600 cursor-pointer">Employees</button>
        <button onclick="switchTab('attendance')" class="tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer">Attendance</button>
        <button onclick="switchTab('leave')" class="tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer">Leave</button>
        <button onclick="switchTab('payroll')" class="tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer">Payroll</button>
      </div>
    </div>

    <div id="tab-employees" class="tab-content">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold font-display">Employees</h2>
        <button onclick="openAddEmployeeModal()" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium">Add Employee</button>
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
            <tbody id="attendanceTable" class="divide-y divide-slate-100"></tbody>
          </table>
        </div>
      </div>
    </div>

    <div id="tab-leave" class="tab-content hidden">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold font-display">Leave Requests</h2>
        <button onclick="openAddLeaveModal()" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium">Add Leave</button>
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
            <option value="01">January</option><option value="02">February</option><option value="03">March</option><option value="04" selected>April</option><option value="05">May</option><option value="06">June</option><option value="07">July</option><option value="08">August</option><option value="09">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option>
          </select>
          <select id="payrollYear" class="border border-slate-300 rounded-md px-3 py-2 text-sm">
            <option value="2024">2024</option><option value="2025">2025</option><option value="2026" selected>2026</option>
          </select>
        </div>
      </div>
      <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-slate-50">
              <tr>
                <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Employee</th>
                <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Basic Salary</th>
                <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Deductions</th>
                <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Net Pay</th>
                <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Status</th>
              </tr>
            </thead>
            <tbody id="payrollTable" class="divide-y divide-slate-100"></tbody>
          </table>
        </div>
      </div>
    </div>
  </main>

  <div id="addEmployeeModal" class="hidden fixed inset-0 bg-slate-900 bg-opacity-40 z-50 flex items-start justify-center pt-20 overflow-y-auto">
    <div class="bg-white rounded-lg shadow-xl border border-slate-200 w-full max-w-lg mx-4 p-6">
      <h2 class="text-lg font-semibold font-display mb-4">Add Employee</h2>
      <div class="space-y-4">
        <input type="text" placeholder="Full Name" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm" id="empName" />
        <input type="text" placeholder="Department" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm" id="empDept" />
        <input type="text" placeholder="Position" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm" id="empPos" />
        <select class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm" id="empCompany">
          <option>Goodness Tanzania Ltd</option>
          <option>Goodness Kenya Ltd</option>
          <option>Goodness Uganda Ltd</option>
        </select>
        <input type="date" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm" id="empJoinDate" />
      </div>
      <div class="flex gap-3 justify-end mt-6">
        <button onclick="closeAddEmployeeModal()" class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm">Cancel</button>
        <button onclick="saveEmployee()" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm">Save</button>
      </div>
    </div>
  </div>

  <script>
    const employees = [
      { id: 1, name: 'Amina Hassan', dept: 'HR', position: 'HR Manager', company: 'Goodness Tanzania Ltd', joinDate: '2021-03-15', status: 'Active' },
      { id: 2, name: 'Joseph Kimani', dept: 'Finance', position: 'Accountant', company: 'Goodness Kenya Ltd', joinDate: '2022-07-20', status: 'Active' },
      { id: 3, name: 'Lucy Mwangi', dept: 'Operations', position: 'Operations Lead', company: 'Goodness Uganda Ltd', joinDate: '2020-01-10', status: 'Active' },
      { id: 4, name: 'David Okoro', dept: 'IT', position: 'Systems Admin', company: 'Goodness Tanzania Ltd', joinDate: '2023-05-12', status: 'Active' },
      { id: 5, name: 'Sarah Kamau', dept: 'Sales', position: 'Sales Manager', company: 'Goodness Kenya Ltd', joinDate: '2021-11-08', status: 'Active' },
    ];

    const attendance = [
      { id: 1, employee: 'Amina Hassan', date: '2026-04-30', checkin: '08:15', checkout: '17:45', hours: '9.5', status: 'Present' },
      { id: 2, employee: 'Joseph Kimani', date: '2026-04-30', checkin: '08:00', checkout: '17:00', hours: '9', status: 'Present' },
      { id: 3, employee: 'Lucy Mwangi', date: '2026-04-30', checkin: '', checkout: '', hours: '-', status: 'Absent' },
    ];

    const leaves = [
      { id: 1, employee: 'Amina Hassan', type: 'Annual Leave', from: '2026-05-01', to: '2026-05-05', days: 5, status: 'Pending' },
      { id: 2, employee: 'Joseph Kimani', type: 'Sick Leave', from: '2026-04-28', to: '2026-04-29', days: 2, status: 'Approved' },
    ];

    const payroll = [
      { id: 1, employee: 'Amina Hassan', basic: 2500000, deductions: 375000, netPay: 2125000, status: 'Paid' },
      { id: 2, employee: 'Joseph Kimani', basic: 1800000, deductions: 270000, netPay: 1530000, status: 'Pending' },
      { id: 3, employee: 'Lucy Mwangi', basic: 2200000, deductions: 330000, netPay: 1870000, status: 'Paid' },
    ];

    function switchTab(tab) {
      document.querySelectorAll('.tab-content').forEach(t => t.classList.add('hidden'));
      document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('border-brand-600', 'text-slate-700');
        b.classList.add('text-slate-500');
      });
      document.getElementById('tab-' + tab).classList.remove('hidden');
      event.target.classList.add('border-brand-600', 'text-slate-700');
      event.target.classList.remove('text-slate-500');
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
      alert('Add Leave modal opened');
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
      renderEmployees();
    });
  </script>
</body>
</html>
        <!-- Sidebar -->
        <div class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col">
            <div class="p-6 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-brand rounded-md flex items-center justify-center">
                        <span class="font-bold text-base text-sm">GG</span>
                    </div>
                    <div>
                        <p class="font-semibold">Goodness</p>
                        <p class="text-xs text-slate-400">Enterprise</p>
                    </div>
                </div>
            </div>

            <nav id="sidebarNav" class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <a href="/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-md text-slate-300 hover:bg-slate-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4-4m-4 4l-4-4m4 4v-4m0 0l-4 4m4-4l4-4" />
                    </svg>
                    Dashboard
                </a>
                <a href="/companies" class="flex items-center gap-3 px-4 py-3 rounded-md text-slate-300 hover:bg-slate-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
                    </svg>
                    Companies
                </a>
                <a href="/users" class="flex items-center gap-3 px-4 py-3 rounded-md text-slate-300 hover:bg-slate-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a9 9 0 0118 0v2h1v-2a10 10 0 00-19 0v2h1" />
                    </svg>
                    Users
                </a>
                <a href="/finance" class="flex items-center gap-3 px-4 py-3 rounded-md text-slate-300 hover:bg-slate-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Finance
                </a>
                <a href="/hrm" class="flex items-center gap-3 px-4 py-3 rounded-md bg-slate-800 border-l-2 border-brand text-white font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    HRM
                </a>
                <a href="/sales" class="flex items-center gap-3 px-4 py-3 rounded-md text-slate-300 hover:bg-slate-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Sales
                </a>
                <a href="/inventory" class="flex items-center gap-3 px-4 py-3 rounded-md text-slate-300 hover:bg-slate-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4m0-10l8 4m-8-4v10m8-10l-8-4" />
                    </svg>
                    Inventory
                </a>
                <a href="/reports" class="flex items-center gap-3 px-4 py-3 rounded-md text-slate-300 hover:bg-slate-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Reports
                </a>
            </nav>

            <div id="sidebarToggle" class="p-4 border-t border-slate-800 cursor-pointer text-slate-400 hover:text-slate-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <svg class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <div class="bg-surface border-b border-slate-700 px-8 py-4 flex items-center justify-between">
                <div>
                    <select class="bg-slate-700 border border-slate-600 rounded-md px-4 py-2 text-white focus:outline-none focus:border-brand">
                        <option>Goodness Group HQ</option>
                        <option>Goodness Tanzania</option>
                        <option>Goodness Kenya</option>
                    </select>
                </div>
                <div class="flex items-center gap-6">
                    <div class="relative">
                        <svg class="w-6 h-6 text-slate-400 cursor-pointer hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute top-0 right-0 w-2 h-2 bg-brand rounded-full"></span>
                    </div>
                    <div class="flex items-center gap-3 pl-6 border-l border-slate-600">
                        <div class="text-right">
                            <p class="font-medium text-sm">John Doe</p>
                            <p class="text-xs text-slate-400">Administrator</p>
                        </div>
                        <div class="w-8 h-8 bg-brand rounded-md flex items-center justify-center text-sm font-bold">JD</div>
                    </div>
                    <button onclick="logout()" class="text-slate-400 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto px-8 py-6">
                <!-- Page Header -->
                <div class="mb-6">
                    <h1 class="text-3xl font-bold">Human Resources</h1>
                    <p class="text-slate-400 mt-1">Manage employees, attendance, leave, and payroll</p>
                </div>

                <!-- Tab Navigation -->
                <div class="bg-surface border border-slate-700 rounded-md mb-6">
                    <div class="flex border-b border-slate-700">
                        <button onclick="switchTab('employees')" class="tab-btn flex-1 px-4 py-3 text-center font-medium border-b-2 border-brand active">Employees</button>
                        <button onclick="switchTab('attendance')" class="tab-btn flex-1 px-4 py-3 text-center font-medium text-slate-400 hover:text-white">Attendance</button>
                        <button onclick="switchTab('leave')" class="tab-btn flex-1 px-4 py-3 text-center font-medium text-slate-400 hover:text-white">Leave</button>
                        <button onclick="switchTab('payroll')" class="tab-btn flex-1 px-4 py-3 text-center font-medium text-slate-400 hover:text-white">Payroll</button>
                    </div>

                    <!-- Tab Content -->
                    <div class="p-6">
                        <!-- Employees Tab -->
                        <div id="employees" class="tab-content">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-semibold">Employee Directory</h3>
                                <button onclick="openAddModal('employee')" class="text-brand hover:text-sky-400 text-sm font-medium">+ Add Employee</button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm" id="employeesTable">
                                    <thead class="bg-slate-800 border-b border-slate-700">
                                        <tr class="text-slate-400">
                                            <th class="text-left py-3 px-4 font-medium">Name</th>
                                            <th class="text-left py-3 px-4 font-medium">Department</th>
                                            <th class="text-left py-3 px-4 font-medium">Position</th>
                                            <th class="text-left py-3 px-4 font-medium">Company</th>
                                            <th class="text-left py-3 px-4 font-medium">Join Date</th>
                                            <th class="text-center py-3 px-4 font-medium">Status</th>
                                        <!DOCTYPE html>
                                        <html lang="en">
                                        <head>
                                          <meta charset="utf-8">
                                          <meta name="viewport" content="width=device-width,initial-scale=1">
                                          <title>HRM — Goodness Group</title>
                                          <script src="https://cdn.tailwindcss.com"></script>
                                          <script>
                                            tailwind.config = { theme: { extend: { colors: { brand: {50:'#f0fdf4',100:'#dcfce7',500:'#22c55e',600:'#16a34a',700:'#15803d'} }, fontFamily:{ sans:['Inter','sans-serif'], display:['Outfit','sans-serif'] } } } }
                                          </script>
                                          <link rel="preconnect" href="https://fonts.googleapis.com">
                                          <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
                                          <style>body{font-family:Inter, sans-serif}h1,h2,nav,button{font-family:Outfit, sans-serif}</style>
                                        </head>
                                        <body class="bg-slate-50 text-slate-800">
                                          <div class="min-h-screen md:flex">
                                            <aside class="hidden md:block w-64 bg-white border-r border-slate-200 p-6">
                                              <div class="text-sm font-semibold">Goodness Group</div>
                                              <nav class="mt-6 space-y-1"><a href="/dashboard" class="block px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 rounded-md">Dashboard</a><a href="/hrm" class="block px-3 py-2 text-sm bg-green-50 text-green-700 rounded-l-md">HRM</a></nav>
                                            </aside>

                                            <main class="flex-1 p-6">
                                              <div class="mb-6"><h1 class="text-2xl font-semibold">Human Resources</h1><p class="text-sm text-slate-500">Employees, attendance and payroll</p></div>

                                              <div class="bg-white border border-slate-200 rounded-lg p-4">
                                                <div class="flex gap-2 mb-4">
                                                  <button id="tabEmployees" class="px-3 py-2 bg-green-50 text-green-700 rounded-md">Employees</button>
                                                  <button id="tabAttendance" class="px-3 py-2 text-slate-600 rounded-md">Attendance</button>
                                                  <button id="tabPayroll" class="px-3 py-2 text-slate-600 rounded-md">Payroll</button>
                                                </div>
                                                <div id="hrmContent"></div>
                                              </div>
                                            </main>
                                          </div>

                                          <script>
                                            const employees = [ { id:1, name:'John Doe', position:'CEO' }, { id:2, name:'Jane Smith', position:'CFO' } ];
                                            function renderEmployees(){ return `
                                              <div class="overflow-x-auto">
                                                <table class="min-w-full">
                                                  <thead class="bg-slate-50">
                                                    <tr>
                                                      <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-left">Name</th>
                                                      <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-left">Position</th>
                                                      <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-center">Actions</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody class="bg-white divide-y divide-slate-100">
                                                    ${employees.map(e=>`<tr class="hover:bg-slate-50"><td class="px-4 py-3 text-sm">${e.name}</td><td class="px-4 py-3 text-sm">${e.position}</td><td class="px-4 py-3 text-sm text-center"><button class="px-2 py-1 border border-slate-200 rounded-md">View</button></td></tr>`).join('')}
                                                  </tbody>
                                                </table>
                                              </div>
                                            ` }

                                            document.getElementById('tabEmployees').addEventListener('click', ()=> document.getElementById('hrmContent').innerHTML = renderEmployees());
                                            document.getElementById('tabAttendance').addEventListener('click', ()=> document.getElementById('hrmContent').innerHTML = '<p class="text-sm text-slate-500">No attendance records.</p>');
                                            document.getElementById('tabPayroll').addEventListener('click', ()=> document.getElementById('hrmContent').innerHTML = '<p class="text-sm text-slate-500">Payroll will be here.</p>');
                                            document.getElementById('tabEmployees').click();
                                          </script>
                                        </body>
                                        </html>

        const payrollData = [
            { employee: 'Sarah Johnson', basic: 2500000, deductions: 500000, net: 2000000, status: 'Paid' },
            { employee: 'David Mkambaji', basic: 1800000, deductions: 360000, net: 1440000, status: 'Paid' },
            { employee: 'Amina Hassan', basic: 2200000, deductions: 440000, net: 1760000, status: 'Pending' },
            { employee: 'James Kipchoge', basic: 2000000, deductions: 400000, net: 1600000, status: 'Paid' },
            { employee: 'Lucy Mwangi', basic: 1500000, deductions: 300000, net: 1200000, status: 'Paid' },
        ];

        function formatCurrency(amount) {
            return 'TZS ' + amount.toLocaleString();
        }

        function renderEmployees() {
            const tbody = document.getElementById('employeesBody');
            tbody.innerHTML = employeesData.map(emp => `
                <tr class="border-b border-slate-700 hover:bg-slate-800">
                    <td class="py-3 px-4">${emp.name}</td>
                    <td class="py-3 px-4">${emp.department}</td>
                    <td class="py-3 px-4">${emp.position}</td>
                    <td class="py-3 px-4">${emp.company}</td>
                    <td class="py-3 px-4">${emp.joinDate}</td>
                    <td class="text-center py-3 px-4">
                        <span class="px-2 py-1 ${emp.status === 'Active' ? 'bg-green-900 text-green-100' : 'bg-slate-700 text-slate-300'} rounded text-xs font-medium">${emp.status}</span>
                    </td>
                </tr>
            `).join('');
        }

        function renderAttendance() {
            const tbody = document.getElementById('attendanceBody');
            tbody.innerHTML = attendanceData.map(att => `
                <tr class="border-b border-slate-700 hover:bg-slate-800">
                    <td class="py-3 px-4">${att.employee}</td>
                    <td class="py-3 px-4">${att.date}</td>
                    <td class="py-3 px-4">${att.checkin}</td>
                    <td class="py-3 px-4">${att.checkout}</td>
                    <td class="text-center py-3 px-4">
                        <span class="px-2 py-1 ${att.status === 'Present' ? 'bg-green-900 text-green-100' : 'bg-red-900 text-red-100'} rounded text-xs font-medium">${att.status}</span>
                    </td>
                </tr>
            `).join('');
        }

        function renderLeave() {
            const tbody = document.getElementById('leaveBody');
            tbody.innerHTML = leaveData.map(lv => `
                <tr class="border-b border-slate-700 hover:bg-slate-800">
                    <td class="py-3 px-4">${lv.employee}</td>
                    <td class="py-3 px-4">${lv.type}</td>
                    <td class="py-3 px-4">${lv.start}</td>
                    <td class="py-3 px-4">${lv.end}</td>
                    <td class="text-center py-3 px-4">${lv.days}</td>
                    <td class="text-center py-3 px-4">
                        <span class="px-2 py-1 ${lv.status === 'Approved' ? 'bg-green-900 text-green-100' : 'bg-yellow-900 text-yellow-100'} rounded text-xs font-medium">${lv.status}</span>
                    </td>
                </tr>
            `).join('');
        }

        function renderPayroll() {
            const tbody = document.getElementById('payrollBody');
            tbody.innerHTML = payrollData.map(pay => `
                <tr class="border-b border-slate-700 hover:bg-slate-800">
                    <td class="py-3 px-4">${pay.employee}</td>
                    <td class="text-right py-3 px-4 mono">${formatCurrency(pay.basic)}</td>
                    <td class="text-right py-3 px-4 mono">${formatCurrency(pay.deductions)}</td>
                    <td class="text-right py-3 px-4 mono">${formatCurrency(pay.net)}</td>
                    <td class="text-center py-3 px-4">
                        <span class="px-2 py-1 ${pay.status === 'Paid' ? 'bg-green-900 text-green-100' : 'bg-yellow-900 text-yellow-100'} rounded text-xs font-medium">${pay.status}</span>
                    </td>
                </tr>
            `).join('');
        }

        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
            document.getElementById(tabName).classList.remove('hidden');
            
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('border-b-2', 'border-brand');
                btn.classList.add('text-slate-400');
            });
            event.target.classList.add('border-b-2', 'border-brand');
            event.target.classList.remove('text-slate-400');
        }

        function filterAttendance() {
            // Placeholder for attendance filter
        }

        function filterPayroll() {
            // Placeholder for payroll filter
        }

        function openAddModal(type) {
            alert('Add ' + type + ' functionality');
        }

        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '/login';
            }
        }

        // Sidebar toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.body.classList.toggle('sidebar-collapsed');
        });

        // Initialize
        renderEmployees();
        renderAttendance();
        renderLeave();
        renderPayroll();
    </script>
@include('components.modal')
@include('components.alert')
@include('components.confirm')
</body>
</html>
