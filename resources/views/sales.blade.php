<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sales - Goodness ERP</title>
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
    <h1 class="text-base font-semibold font-display">Sales & CRM</h1>
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

  @include('components.sidebar')

  <main class="ml-0 lg:ml-64 pt-16 p-6">
    <div class="bg-white border-b border-slate-200 -mx-6 -mt-6 px-6 mb-6">
      <div class="flex gap-8">
        <button onclick="switchTab('customers')" class="tab-btn active py-4 text-sm font-medium text-slate-700 border-b-2 border-brand-600 cursor-pointer">Customers</button>
        <button onclick="switchTab('orders')" class="tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer">Orders</button>
        <button onclick="switchTab('contracts')" class="tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer">Contracts</button>
      </div>
    </div>

    <div id="tab-customers" class="tab-content">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold font-display">Customers</h2>
        <button onclick="openAddCustomerModal()" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium">Add Customer</button>
      </div>
      <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-slate-50">
              <tr>
                <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Customer Name</th>
                <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Company</th>
                <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Phone</th>
                <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Email</th>
                <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Assigned To</th>
                <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Status</th>
              </tr>
            </thead>
            <tbody id="customersTable" class="divide-y divide-slate-100"></tbody>
          </table>
        </div>
      </div>
    </div>

    <div id="tab-orders" class="tab-content hidden">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold font-display">Orders</h2>
        <button onclick="openAddOrderModal()" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium">Add Order</button>
      </div>
      <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-slate-50">
              <tr>
                <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Order No.</th>
                <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Customer</th>
                <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Description</th>
                <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Total</th>
                <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Date</th>
                <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Status</th>
              </tr>
            </thead>
            <tbody id="ordersTable" class="divide-y divide-slate-100"></tbody>
          </table>
        </div>
      </div>
    </div>

    <div id="tab-contracts" class="tab-content hidden">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold font-display">Contracts</h2>
        <button onclick="openAddContractModal()" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium">Add Contract</button>
      </div>
      <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-slate-50">
              <tr>
                <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Contract No.</th>
                <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Client</th>
                <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Value</th>
                <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Start Date</th>
                <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">End Date</th>
                <th class="px-4 py-3 text-left text-xs uppercase tracking-wider font-medium text-slate-500">Status</th>
              </tr>
            </thead>
            <tbody id="contractsTable" class="divide-y divide-slate-100"></tbody>
          </table>
        </div>
      </div>
    </div>
  </main>

  <script>
    const customers = [
      { id: 1, name: 'Mohammed Salim', company: 'Tech Solutions Ltd', phone: '+255 754 123456', email: 'm.salim@tech.co.tz', assignedTo: 'Sarah Kamau', status: 'Active' },
      { id: 2, name: 'Fatima Okoro', company: 'Fashion Enterprises', phone: '+255 765 234567', email: 'f.okoro@fashion.co.tz', assignedTo: 'John Kipchoge', status: 'Active' },
      { id: 3, name: 'Charles Mwangi', company: 'Export Trading Co', phone: '+254 722 345678', email: 'c.mwangi@export.co.ke', assignedTo: 'Sarah Kamau', status: 'Inactive' },
    ];

    const orders = [
      { id: 1, orderNo: 'ORD-001', customer: 'Mohammed Salim', description: 'Software Development Services', total: 5000000, date: '2026-04-20', status: 'Completed' },
      { id: 2, orderNo: 'ORD-002', customer: 'Fatima Okoro', description: 'Fashion Collection Supply', total: 3500000, date: '2026-04-25', status: 'In Progress' },
      { id: 3, orderNo: 'ORD-003', customer: 'Charles Mwangi', description: 'Export Documentation & Shipping', total: 2200000, date: '2026-04-28', status: 'Pending' },
    ];

    const contracts = [
      { id: 1, contractNo: 'CTR-001', client: 'Tech Solutions Ltd', value: 15000000, startDate: '2026-01-01', endDate: '2026-12-31', status: 'Active' },
      { id: 2, contractNo: 'CTR-002', client: 'Fashion Enterprises', value: 8500000, startDate: '2026-02-15', endDate: '2027-02-14', status: 'Active' },
      { id: 3, contractNo: 'CTR-003', client: 'Export Trading Co', value: 12000000, startDate: '2025-06-01', endDate: '2026-05-31', status: 'Expired' },
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
      if (tab === 'customers') renderCustomers();
      if (tab === 'orders') renderOrders();
      if (tab === 'contracts') renderContracts();
    }

    function renderCustomers() {
      const tbody = document.getElementById('customersTable');
      tbody.innerHTML = customers.map(c => `
        <tr class="hover:bg-slate-50">
          <td class="px-4 py-3 text-sm">${c.name}</td>
          <td class="px-4 py-3 text-sm">${c.company}</td>
          <td class="px-4 py-3 text-sm">${c.phone}</td>
          <td class="px-4 py-3 text-sm">${c.email}</td>
          <td class="px-4 py-3 text-sm">${c.assignedTo}</td>
          <td class="px-4 py-3 text-sm"><span class="inline-block px-2 py-0.5 rounded ${c.status === 'Active' ? 'bg-green-50 text-green-700' : 'bg-slate-100 text-slate-600'} text-xs font-medium">${c.status}</span></td>
        </tr>
      `).join('');
    }

    function renderOrders() {
      const tbody = document.getElementById('ordersTable');
      tbody.innerHTML = orders.map(o => `
        <tr class="hover:bg-slate-50">
          <td class="px-4 py-3 text-sm">${o.orderNo}</td>
          <td class="px-4 py-3 text-sm">${o.customer}</td>
          <td class="px-4 py-3 text-sm">${o.description}</td>
          <td class="px-4 py-3 text-sm">TZS ${o.total.toLocaleString()}</td>
          <td class="px-4 py-3 text-sm">${o.date}</td>
          <td class="px-4 py-3 text-sm"><span class="inline-block px-2 py-0.5 rounded ${o.status === 'Completed' ? 'bg-green-50 text-green-700' : o.status === 'In Progress' ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700'} text-xs font-medium">${o.status}</span></td>
        </tr>
      `).join('');
    }

    function renderContracts() {
      const tbody = document.getElementById('contractsTable');
      tbody.innerHTML = contracts.map(c => `
        <tr class="hover:bg-slate-50">
          <td class="px-4 py-3 text-sm">${c.contractNo}</td>
          <td class="px-4 py-3 text-sm">${c.client}</td>
          <td class="px-4 py-3 text-sm">TZS ${c.value.toLocaleString()}</td>
          <td class="px-4 py-3 text-sm">${c.startDate}</td>
          <td class="px-4 py-3 text-sm">${c.endDate}</td>
          <td class="px-4 py-3 text-sm"><span class="inline-block px-2 py-0.5 rounded ${c.status === 'Active' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'} text-xs font-medium">${c.status}</span></td>
        </tr>
      `).join('');
    }

    function openAddCustomerModal() {
      alert('Add Customer modal');
    }

    function openAddOrderModal() {
      alert('Add Order modal');
    }

    function openAddContractModal() {
      alert('Add Contract modal');
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
      renderCustomers();
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
                <a href="/hrm" class="flex items-center gap-3 px-4 py-3 rounded-md text-slate-300 hover:bg-slate-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    HRM
                </a>
                <a href="/sales" class="flex items-center gap-3 px-4 py-3 rounded-md bg-slate-800 border-l-2 border-brand text-white font-medium">
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
                    <h1 class="text-3xl font-bold">Sales & CRM</h1>
                    <p class="text-slate-400 mt-1">Manage customers, orders, and contracts</p>
                </div>

                <!-- Tab Navigation -->
                <div class="bg-surface border border-slate-700 rounded-md mb-6">
                    <div class="flex border-b border-slate-700">
                        <button onclick="switchTab('customers')" class="tab-btn flex-1 px-4 py-3 text-center font-medium border-b-2 border-brand active">Customers</button>
                        <button onclick="switchTab('orders')" class="tab-btn flex-1 px-4 py-3 text-center font-medium text-slate-400 hover:text-white">Orders</button>
                        <button onclick="switchTab('contracts')" class="tab-btn flex-1 px-4 py-3 text-center font-medium text-slate-400 hover:text-white">Contracts</button>
                    </div>

                    <!-- Tab Content -->
                    <div class="p-6">
                        <!-- Customers Tab -->
                        <!DOCTYPE html>
                        <html lang="en">
                        <head>
                          <meta charset="utf-8">
                          <meta name="viewport" content="width=device-width,initial-scale=1">
                          <title>Sales — Goodness Group</title>
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
                              <nav class="mt-6 space-y-1"><a href="/dashboard" class="block px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 rounded-md">Dashboard</a><a href="/sales" class="block px-3 py-2 text-sm bg-green-50 text-green-700 rounded-l-md">Sales</a></nav>
                            </aside>

                            <main class="flex-1 p-6">
                              <div class="mb-6"><h1 class="text-2xl font-semibold">Sales & CRM</h1><p class="text-sm text-slate-500">Customers, orders, and pipelines</p></div>

                              <div class="bg-white border border-slate-200 rounded-lg p-4">
                                <div class="flex gap-2 mb-4"><button id="tabCustomers" class="px-3 py-2 bg-green-50 text-green-700 rounded-md">Customers</button><button id="tabOrders" class="px-3 py-2 text-slate-600 rounded-md">Orders</button><button id="tabContracts" class="px-3 py-2 text-slate-600 rounded-md">Contracts</button></div>
                                <div id="salesContent"></div>
                              </div>
                            </main>
                          </div>

                          <script>
                            const customers = [ { id:1, name:'Acme Corp', contact:'acme@example.com' }, { id:2, name:'Beta LLC', contact:'beta@example.com' } ];
                            function renderCustomers(){ return `
                              <div class="overflow-x-auto">
                                <table class="min-w-full">
                                  <thead class="bg-slate-50">
                                    <tr>
                                      <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-left">Customer</th>
                                      <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-left">Contact</th>
                                      <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-center">Actions</th>
                                    </tr>
                                  </thead>
                                  <tbody class="bg-white divide-y divide-slate-100">
                                    ${customers.map(c=>`<tr class="hover:bg-slate-50"><td class="px-4 py-3 text-sm">${c.name}</td><td class="px-4 py-3 text-sm">${c.contact}</td><td class="px-4 py-3 text-sm text-center"><button class="px-2 py-1 border border-slate-200 rounded-md">View</button></td></tr>`).join('')}
                                  </tbody>
                                </table>
                              </div>
                            ` }

                            document.getElementById('tabCustomers').addEventListener('click', ()=> document.getElementById('salesContent').innerHTML = renderCustomers());
                            document.getElementById('tabOrders').addEventListener('click', ()=> document.getElementById('salesContent').innerHTML = '<p class="text-sm text-slate-500">No orders yet.</p>');
                            document.getElementById('tabContracts').addEventListener('click', ()=> document.getElementById('salesContent').innerHTML = '<p class="text-sm text-slate-500">No contracts yet.</p>');
                            document.getElementById('tabCustomers').click();
                          </script>
                        </body>
                        </html>
                    <td class="py-3 px-4">${cust.assignedTo}</td>
                    <td class="text-center py-3 px-4">
                        <span class="px-2 py-1 ${cust.status === 'Active' ? 'bg-green-900 text-green-100' : 'bg-slate-700 text-slate-300'} rounded text-xs font-medium">${cust.status}</span>
                    </td>
                </tr>
            `).join('');
        }

        function renderOrders() {
            const tbody = document.getElementById('ordersBody');
            tbody.innerHTML = ordersData.map(ord => `
                <tr class="border-b border-slate-700 hover:bg-slate-800">
                    <td class="py-3 px-4 font-medium">${ord.no}</td>
                    <td class="py-3 px-4">${ord.customer}</td>
                    <td class="py-3 px-4">${ord.products}</td>
                    <td class="text-right py-3 px-4 mono">${formatCurrency(ord.total)}</td>
                    <td class="py-3 px-4">${ord.date}</td>
                    <td class="text-center py-3 px-4">
                        <span class="px-2 py-1 ${ord.status === 'Completed' ? 'bg-green-900 text-green-100' : ord.status === 'Processing' ? 'bg-blue-900 text-blue-100' : 'bg-yellow-900 text-yellow-100'} rounded text-xs font-medium">${ord.status}</span>
                    </td>
                </tr>
            `).join('');
        }

        function renderContracts() {
            const tbody = document.getElementById('contractsBody');
            tbody.innerHTML = contractsData.map(con => `
                <tr class="border-b border-slate-700 hover:bg-slate-800">
                    <td class="py-3 px-4 font-medium">${con.no}</td>
                    <td class="py-3 px-4">${con.client}</td>
                    <td class="text-right py-3 px-4 mono">${formatCurrency(con.value)}</td>
                    <td class="py-3 px-4">${con.start}</td>
                    <td class="py-3 px-4">${con.end}</td>
                    <td class="text-center py-3 px-4">
                        <span class="px-2 py-1 ${con.status === 'Active' ? 'bg-green-900 text-green-100' : 'bg-yellow-900 text-yellow-100'} rounded text-xs font-medium">${con.status}</span>
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
        renderCustomers();
        renderOrders();
        renderContracts();
    </script>
@include('components.modal')
@include('components.alert')
@include('components.confirm')
</body>
</html>
