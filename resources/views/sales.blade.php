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
  @include('components.topbar')
  @include('components.sidebar')

  <main class="ml-0 lg:ml-64 pt-20 p-6">
    <div class="bg-white border-b border-slate-200 -mx-6 px-6 mb-6">
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
  @include('components.modal')
  @include('components.alert')
  @include('components.confirm')
</body>
</html>
