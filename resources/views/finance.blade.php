<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Finance - Goodness Group</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                            700: '#15803d'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: Inter, sans-serif
        }

        h1,
        h2,
        nav,
        button {
            font-family: Outfit, sans-serif
        }

        .mono {
            font-family: ui-monospace, monospace
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800">
    @include('components.topbar')
    @include('components.sidebar')

    <main class="ml-0 lg:ml-64 pt-16 lg:pt-20 px-4 lg:p-6 min-h-screen">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Finance</h1>
            <p class="text-sm text-slate-500">Invoices, expenses and payments</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-lg p-4">
            <!-- Tabs + Action Button Row -->
            <div class="flex flex-col gap-3 mb-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex flex-wrap gap-2">
                    <button id="tabInvoices" onclick="setActiveTab('invoices', this)"
                        class="px-3 py-2 bg-green-50 text-green-700 border-b-2 border-brand-600">Invoices</button>
                    <button id="tabExpenses" onclick="setActiveTab('expenses', this)"
                        class="px-3 py-2 text-slate-600">Expenses</button>
                    <button id="tabPayments" onclick="setActiveTab('payments', this)"
                        class="px-3 py-2 text-slate-600">Payments</button>
                </div>
                <!-- Dynamic Add Button -->
                <div id="actionButton" class="w-full lg:w-auto"></div>
            </div>
            <div id="tabContent"></div>
        </div>
    </main>

    <script>
        const invoices = [{
                id: 'INV-001',
                company: 'Goodness Tanzania Ltd',
                amount: 1250000,
                status: 'Paid'
            },
            {
                id: 'INV-002',
                company: 'Goodness Kenya Ltd',
                amount: 850000,
                status: 'Unpaid'
            }
        ];

        const expenses = [];
        const payments = [];

        function formatTZS(n) {
            return 'TZS ' + n.toLocaleString();
        }

        function renderInvoices() {
            return `<div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Invoice</th>
                                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Company</th>
                                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Amount</th>
                                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                ${invoices.map(i => `
                                        <tr>
                                            <td class="px-4 py-3 text-sm">${i.id}</td>
                                            <td class="px-4 py-3 text-sm">${i.company}</td>
                                            <td class="px-4 py-3 text-sm text-right mono">${formatTZS(i.amount)}</td>
                                            <td class="px-4 py-3 text-sm text-center">
                                                <span class="inline-block px-2 py-1 ${i.status === 'Paid' ? 'bg-green-50 text-green-700' : 'bg-slate-50 text-slate-700'} rounded-md text-xs">${i.status}</span>
                                            </td>
                                        </tr>`).join('')}
                            </tbody>
                        </table>
                    </div>`;
        }

        function renderButton(label, onclick) {
            return `<button onclick="${onclick}" class="w-full lg:w-auto flex-shrink-0 whitespace-nowrap px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium transition-colors">
                        ${label}
                    </button>`;
        }

        function openAddInvoiceModal() {
            const body = `
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Invoice ID</label>
                        <input type="text" id="invoiceId" placeholder="INV-003" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Company</label>
                        <input type="text" id="invoiceCompany" placeholder="Company name" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Amount (TZS)</label>
                        <input type="number" id="invoiceAmount" placeholder="0" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                        <select id="invoiceStatus" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                            <option value="Paid">Paid</option>
                            <option value="Unpaid">Unpaid</option>
                        </select>
                    </div>
                </div>
            `;
            window.openModal('Add Invoice', body, () => {
                const id = document.getElementById('invoiceId').value.trim();
                const company = document.getElementById('invoiceCompany').value.trim();
                const amount = parseFloat(document.getElementById('invoiceAmount').value);
                const status = document.getElementById('invoiceStatus').value;

                if (!id) { window.showAlert('error', 'Invoice ID is required'); return false; }
                if (!company) { window.showAlert('error', 'Company is required'); return false; }
                if (!amount || amount <= 0) { window.showAlert('error', 'Amount must be greater than 0'); return false; }

                invoices.push({ id, company, amount, status });
                setActiveTab('invoices', document.getElementById('tabInvoices'));
                window.closeModal();
                window.showAlert('success', 'Invoice added successfully');
                return true;
            });
        }

        function openAddExpenseModal() {
            const body = `
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Expense ID</label>
                        <input type="text" id="expenseId" placeholder="EXP-001" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Category</label>
                        <input type="text" id="expenseCategory" placeholder="Travel, Office, etc" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Amount (TZS)</label>
                        <input type="number" id="expenseAmount" placeholder="0" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                        <input type="text" id="expenseDesc" placeholder="Description" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                </div>
            `;
            window.openModal('Add Expense', body, () => {
                const id = document.getElementById('expenseId').value.trim();
                const category = document.getElementById('expenseCategory').value.trim();
                const amount = parseFloat(document.getElementById('expenseAmount').value);
                const description = document.getElementById('expenseDesc').value.trim();

                if (!id) { window.showAlert('error', 'Expense ID is required'); return false; }
                if (!category) { window.showAlert('error', 'Category is required'); return false; }
                if (!amount || amount <= 0) { window.showAlert('error', 'Amount must be greater than 0'); return false; }

                expenses.push({ id, category, amount, description });
                setActiveTab('expenses', document.getElementById('tabExpenses'));
                window.closeModal();
                window.showAlert('success', 'Expense added successfully');
                return true;
            });
        }

        function openAddPaymentModal() {
            const body = `
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Payment ID</label>
                        <input type="text" id="paymentId" placeholder="PAY-001" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Company</label>
                        <input type="text" id="paymentCompany" placeholder="Company name" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Amount (TZS)</label>
                        <input type="number" id="paymentAmount" placeholder="0" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Payment Method</label>
                        <select id="paymentMethod" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Cash">Cash</option>
                            <option value="Check">Check</option>
                        </select>
                    </div>
                </div>
            `;
            window.openModal('Add Payment', body, () => {
                const id = document.getElementById('paymentId').value.trim();
                const company = document.getElementById('paymentCompany').value.trim();
                const amount = parseFloat(document.getElementById('paymentAmount').value);
                const method = document.getElementById('paymentMethod').value;

                if (!id) { window.showAlert('error', 'Payment ID is required'); return false; }
                if (!company) { window.showAlert('error', 'Company is required'); return false; }
                if (!amount || amount <= 0) { window.showAlert('error', 'Amount must be greater than 0'); return false; }

                payments.push({ id, company, amount, method });
                setActiveTab('payments', document.getElementById('tabPayments'));
                window.closeModal();
                window.showAlert('success', 'Payment added successfully');
                return true;
            });
        }

        function renderExpenses() {
            if (expenses.length === 0) return '<p class="text-sm text-slate-500">No expenses yet.</p>';
            return `<div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">ID</th>
                                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Category</th>
                                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Amount</th>
                                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Description</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                ${expenses.map(e => `
                                        <tr>
                                            <td class="px-4 py-3 text-sm">${e.id}</td>
                                            <td class="px-4 py-3 text-sm">${e.category}</td>
                                            <td class="px-4 py-3 text-sm text-right mono">${formatTZS(e.amount)}</td>
                                            <td class="px-4 py-3 text-sm">${e.description}</td>
                                        </tr>`).join('')}
                            </tbody>
                        </table>
                    </div>`;
        }

        function renderPayments() {
            if (payments.length === 0) return '<p class="text-sm text-slate-500">No payments yet.</p>';
            return `<div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">ID</th>
                                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Company</th>
                                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Amount</th>
                                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Method</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                ${payments.map(p => `
                                        <tr>
                                            <td class="px-4 py-3 text-sm">${p.id}</td>
                                            <td class="px-4 py-3 text-sm">${p.company}</td>
                                            <td class="px-4 py-3 text-sm text-right mono">${formatTZS(p.amount)}</td>
                                            <td class="px-4 py-3 text-sm">${p.method}</td>
                                        </tr>`).join('')}
                            </tbody>
                        </table>
                    </div>`;
        }

        function setActiveTab(tab, btnEl) {
            const tabs = ['tabInvoices', 'tabExpenses', 'tabPayments'];
            tabs.forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                el.classList.remove('bg-green-50', 'text-green-700', 'border-brand-600', 'border-b-2');
                el.classList.add('text-slate-600');
            });

            if (btnEl) {
                btnEl.classList.add('bg-green-50', 'text-green-700', 'border-brand-600', 'border-b-2');
                btnEl.classList.remove('text-slate-600');
            }

            const content = document.getElementById('tabContent');
            const actionButton = document.getElementById('actionButton');

            if (tab === 'invoices') {
                content.innerHTML = renderInvoices();
                actionButton.innerHTML = renderButton('Add Invoice', 'openAddInvoiceModal()');
            }
            if (tab === 'expenses') {
                content.innerHTML = renderExpenses();
                actionButton.innerHTML = renderButton('Add Expense', 'openAddExpenseModal()');
            }
            if (tab === 'payments') {
                content.innerHTML = renderPayments();
                actionButton.innerHTML = renderButton('Add Payment', 'openAddPaymentModal()');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            setActiveTab('invoices', document.getElementById('tabInvoices'));
        });
    </script>

    @include('components.modal')
    @include('components.alert')
    @include('components.confirm')
</body>

</html>
