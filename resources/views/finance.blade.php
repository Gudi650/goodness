<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance - Goodness ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .mono { font-family: 'IBM Plex Mono', monospace; }
        .sidebar-collapsed #sidebarNav { @apply hidden; }
        .sidebar-collapsed #sidebarToggle svg:first-child { @apply hidden; }
        .sidebar-collapsed #sidebarToggle svg:last-child { @apply block; }
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Finance — Goodness Group</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = { theme: { extend: { colors: { brand: {50:'#f0fdf4',100:'#dcfce7',500:'#22c55e',600:'#16a34a',700:'#15803d'} }, fontFamily:{ sans:['Inter','sans-serif'], display:['Outfit','sans-serif'] } } } }
        </script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
        <style>body{font-family:Inter, sans-serif}h1,h2,nav,button{font-family:Outfit, sans-serif}.mono{font-family:ui-monospace,monospace}</style>
    </head>
    <body class="bg-slate-50 text-slate-800">
        <div class="min-h-screen md:flex">
            <aside class="hidden md:block w-64 bg-white border-r border-slate-200 p-6">
                <div class="text-sm font-semibold">Goodness Group</div>
      @include('components.topbar')
      @include('components.sidebar')
      <main class="flex-1 p-6">
                    <p class="text-sm text-slate-500">Invoices, expenses and payments</p>
                </div>

                <div class="flex items-center gap-3 mb-4">
                    <input id="searchFinance" type="text" placeholder="Search invoices..." class="flex-1 px-4 py-2 border border-slate-200 rounded-md bg-white" />
                    <button id="newInvoiceBtn" class="px-4 py-2 bg-brand-600 text-white rounded-md">New Invoice</button>
                </div>

                <div class="bg-white border border-slate-200 rounded-lg p-4">
                    <div class="flex gap-2 mb-4">
                        <button id="tabInvoices" class="px-3 py-2 bg-green-50 text-green-700 rounded-md">Invoices</button>
                        <button id="tabExpenses" class="px-3 py-2 text-slate-600 rounded-md">Expenses</button>
                        <button id="tabPayments" class="px-3 py-2 text-slate-600 rounded-md">Payments</button>
                    </div>

                    <div id="tabContent"></div>
                </div>
            </main>
        </div>

        <script>
            const invoices = [ { id:'INV-001', company:'Goodness Tanzania Ltd', amount:1250000, status:'Paid' }, { id:'INV-002', company:'Goodness Kenya Ltd', amount:850000, status:'Unpaid' } ];
            function formatTZS(n){ return 'TZS ' + n.toLocaleString(); }
            function renderInvoices(){ return `
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-left">Invoice</th>
                                <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-left">Company</th>
                                <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-right">Amount</th>
                                <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            ${invoices.map(i=>`<tr class="hover:bg-slate-50"><td class="px-4 py-3 text-sm">${i.id}</td><td class="px-4 py-3 text-sm">${i.company}</td><td class="px-4 py-3 text-sm text-right mono">${formatTZS(i.amount)}</td><td class="px-4 py-3 text-sm text-center"><span class="inline-block px-2 py-1 ${i.status==='Paid'?'bg-green-50 text-green-700':'bg-slate-50 text-slate-700'} rounded-md text-xs">${i.status}</span></td></tr>`).join('')}
                        </tbody>
                    </table>
                </div>
            ` }

            document.getElementById('tabInvoices').addEventListener('click', ()=> document.getElementById('tabContent').innerHTML = renderInvoices());
            document.getElementById('tabExpenses').addEventListener('click', ()=> document.getElementById('tabContent').innerHTML = '<p class="text-sm text-slate-500">No expenses yet.</p>');
            document.getElementById('tabPayments').addEventListener('click', ()=> document.getElementById('tabContent').innerHTML = '<p class="text-sm text-slate-500">No payments yet.</p>');
            // init
            document.getElementById('tabInvoices').click();
        </script>
  @include('components.modal')
  @include('components.alert')
  @include('components.confirm')
    </body>
    </html>
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
                    <h1 class="text-3xl font-bold">Finance</h1>
                    <p class="text-slate-400 mt-1">Manage invoices, expenses, and payments</p>
                </div>

                <!-- Summary Bar -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-surface border border-slate-700 rounded-md p-4">
                        <p class="text-slate-400 text-sm mb-1">Total Invoiced</p>
                        <p class="text-2xl font-bold mono">TZS 5,850,000</p>
                    </div>
                    <div class="bg-surface border border-slate-700 rounded-md p-4">
                        <p class="text-slate-400 text-sm mb-1">Total Expenses</p>
                        <p class="text-2xl font-bold mono">TZS 1,250,000</p>
                    </div>
                    <div class="bg-surface border border-slate-700 rounded-md p-4">
                        <p class="text-slate-400 text-sm mb-1">Net Balance</p>
                        <p class="text-2xl font-bold mono text-green-400">TZS 4,600,000</p>
                    </div>
                </div>

                <!-- Tab Navigation -->
                <div class="bg-surface border border-slate-700 rounded-md mb-6">
                    <div class="flex border-b border-slate-700">
                        <button onclick="switchTab('invoices')" class="tab-btn flex-1 px-4 py-3 text-center font-medium border-b-2 border-brand active">Invoices</button>
                        <button onclick="switchTab('expenses')" class="tab-btn flex-1 px-4 py-3 text-center font-medium text-slate-400 hover:text-white">Expenses</button>
                        <button onclick="switchTab('payments')" class="tab-btn flex-1 px-4 py-3 text-center font-medium text-slate-400 hover:text-white">Payments</button>
                        <button onclick="switchTab('reports')" class="tab-btn flex-1 px-4 py-3 text-center font-medium text-slate-400 hover:text-white">Reports</button>
                    </div>

                    <!-- Tab Content -->
                    <div class="p-6">
                        <!-- Invoices Tab -->
                        <div id="invoices" class="tab-content">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-semibold">Invoice Records</h3>
                                <button onclick="openAddModal('invoice')" class="text-brand hover:text-sky-400 text-sm font-medium">+ Add Invoice</button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm" id="invoicesTable">
                                    <thead class="bg-slate-800 border-b border-slate-700">
                                        <tr class="text-slate-400">
                                            <th class="text-left py-3 px-4 font-medium">Invoice No.</th>
                                            <th class="text-left py-3 px-4 font-medium">Client</th>
                                            <th class="text-left py-3 px-4 font-medium">Company</th>
                                            <th class="text-right py-3 px-4 font-medium mono">Amount</th>
                                            <th class="text-left py-3 px-4 font-medium">Date</th>
                                            <th class="text-center py-3 px-4 font-medium">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="invoicesBody"></tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Expenses Tab -->
                        <div id="expenses" class="tab-content hidden">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-semibold">Expense Records</h3>
                                <button onclick="openAddModal('expense')" class="text-brand hover:text-sky-400 text-sm font-medium">+ Add Expense</button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm" id="expensesTable">
                                    <thead class="bg-slate-800 border-b border-slate-700">
                                        <tr class="text-slate-400">
                                            <th class="text-left py-3 px-4 font-medium">Description</th>
                                            <th class="text-left py-3 px-4 font-medium">Category</th>
                                            <th class="text-left py-3 px-4 font-medium">Company</th>
                                            <th class="text-right py-3 px-4 font-medium mono">Amount</th>
                                            <th class="text-left py-3 px-4 font-medium">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody id="expensesBody"></tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Payments Tab -->
                        <div id="payments" class="tab-content hidden">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-semibold">Payment Records</h3>
                                <button onclick="openAddModal('payment')" class="text-brand hover:text-sky-400 text-sm font-medium">+ Add Payment</button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm" id="paymentsTable">
                                    <thead class="bg-slate-800 border-b border-slate-700">
                                        <tr class="text-slate-400">
                                            <th class="text-left py-3 px-4 font-medium">Reference</th>
                                            <th class="text-left py-3 px-4 font-medium">From</th>
                                            <th class="text-left py-3 px-4 font-medium">To Company</th>
                                            <th class="text-right py-3 px-4 font-medium mono">Amount</th>
                                            <th class="text-left py-3 px-4 font-medium">Date</th>
                                            <th class="text-left py-3 px-4 font-medium">Method</th>
                                        </tr>
                                    </thead>
                                    <tbody id="paymentsBody"></tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Reports Tab -->
                        <div id="reports" class="tab-content hidden">
                            <div class="space-y-4">
                                <p class="text-slate-400">Financial reports and analysis tools are available here.</p>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-slate-800 border border-slate-700 rounded-md p-4 text-center cursor-pointer hover:border-brand">
                                        <p class="font-medium">Monthly Summary</p>
                                    </div>
                                    <div class="bg-slate-800 border border-slate-700 rounded-md p-4 text-center cursor-pointer hover:border-brand">
                                        <p class="font-medium">Quarterly Report</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-surface border border-slate-700 rounded-md p-6 max-w-sm">
            <p class="text-green-100">Record added successfully!</p>
            <button onclick="closeSuccess()" class="mt-4 w-full px-4 py-2 bg-brand rounded-md hover:bg-sky-600 font-medium text-sm">OK</button>
        </div>
    </div>

    <script>
        const invoicesData = [
            { no: 'INV-001', client: 'ABC Corporation', company: 'Goodness Tanzania Ltd', amount: 1500000, date: '2026-04-15', status: 'Paid' },
            { no: 'INV-002', client: 'XYZ Ltd', company: 'Goodness Kenya Ltd', amount: 2000000, date: '2026-04-18', status: 'Pending' },
            { no: 'INV-003', client: 'Tech Solutions', company: 'Goodness Uganda Ltd', amount: 950000, date: '2026-04-10', status: 'Overdue' },
            { no: 'INV-004', client: 'Global Services', company: 'Goodness Tanzania Ltd', amount: 1400000, date: '2026-04-22', status: 'Paid' },
        ];

        const expensesData = [
            { desc: 'Office Supplies', category: 'Supplies', company: 'Goodness Tanzania Ltd', amount: 250000, date: '2026-04-20' },
            { desc: 'Staff Training', category: 'Training', company: 'Goodness Kenya Ltd', amount: 500000, date: '2026-04-18' },
            { desc: 'Utilities', category: 'Operations', company: 'Goodness Uganda Ltd', amount: 300000, date: '2026-04-15' },
            { desc: 'Maintenance', category: 'Maintenance', company: 'Goodness Tanzania Ltd', amount: 200000, date: '2026-04-22' },
        ];

        const paymentsData = [
            { ref: 'PAY-001', from: 'ABC Corp', to: 'Goodness Tanzania Ltd', amount: 500000, date: '2026-04-20', method: 'Bank Transfer' },
            { ref: 'PAY-002', from: 'XYZ Ltd', to: 'Goodness Kenya Ltd', amount: 750000, date: '2026-04-19', method: 'Cheque' },
            { ref: 'PAY-003', from: 'Tech Solutions', to: 'Goodness Uganda Ltd', amount: 1200000, date: '2026-04-21', method: 'Bank Transfer' },
        ];

        function formatCurrency(amount) {
            return 'TZS ' + amount.toLocaleString();
        }

        function renderInvoices() {
            const tbody = document.getElementById('invoicesBody');
            tbody.innerHTML = invoicesData.map(inv => `
                <tr class="border-b border-slate-700 hover:bg-slate-800">
                    <td class="py-3 px-4 font-medium">${inv.no}</td>
                    <td class="py-3 px-4">${inv.client}</td>
                    <td class="py-3 px-4">${inv.company}</td>
                    <td class="py-3 px-4 text-right mono">${formatCurrency(inv.amount)}</td>
                    <td class="py-3 px-4">${inv.date}</td>
                    <td class="text-center py-3 px-4">
                        <span class="px-2 py-1 ${inv.status === 'Paid' ? 'bg-green-900 text-green-100' : inv.status === 'Pending' ? 'bg-yellow-900 text-yellow-100' : 'bg-red-900 text-red-100'} rounded text-xs font-medium">${inv.status}</span>
                    </td>
                </tr>
            `).join('');
        }

        function renderExpenses() {
            const tbody = document.getElementById('expensesBody');
            tbody.innerHTML = expensesData.map(exp => `
                <tr class="border-b border-slate-700 hover:bg-slate-800">
                    <td class="py-3 px-4">${exp.desc}</td>
                    <td class="py-3 px-4">${exp.category}</td>
                    <td class="py-3 px-4">${exp.company}</td>
                    <td class="py-3 px-4 text-right mono">${formatCurrency(exp.amount)}</td>
                    <td class="py-3 px-4">${exp.date}</td>
                </tr>
            `).join('');
        }

        function renderPayments() {
            const tbody = document.getElementById('paymentsBody');
            tbody.innerHTML = paymentsData.map(pay => `
                <tr class="border-b border-slate-700 hover:bg-slate-800">
                    <td class="py-3 px-4 font-medium">${pay.ref}</td>
                    <td class="py-3 px-4">${pay.from}</td>
                    <td class="py-3 px-4">${pay.to}</td>
                    <td class="py-3 px-4 text-right mono">${formatCurrency(pay.amount)}</td>
                    <td class="py-3 px-4">${pay.date}</td>
                    <td class="py-3 px-4">${pay.method}</td>
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

        function closeSuccess() {
            document.getElementById('successModal').classList.add('hidden');
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
        renderInvoices();
        renderExpenses();
        renderPayments();
    </script>
</body>
</html>
