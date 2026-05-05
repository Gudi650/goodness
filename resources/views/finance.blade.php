<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Finance - Goodness Group</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
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
                            700: '#c88600'
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
            <div class="flex flex-col gap-3 mb-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex flex-wrap gap-2">
                    <button id="tabInvoices" onclick="setActiveTab('invoices', this)"
                        class="px-3 py-2 bg-brand-50 text-brand-700 border-b-2 border-brand-600">Invoices</button>
                    <button id="tabExpenses" onclick="setActiveTab('expenses', this)"
                        class="px-3 py-2 text-slate-600">Expenses</button>
                    <button id="tabPayments" onclick="setActiveTab('payments', this)"
                        class="px-3 py-2 text-slate-600">Payments</button>
                </div>
                <div id="actionButton" class="w-full lg:w-auto"></div>
            </div>

            <div id="tabContent" class="space-y-6">
                @include('finance.invoices', ['invoices' => $invoices])
                @include('finance.expenses', ['expenses' => $expenses])
                @include('finance.payments', ['payments' => $payments])
            </div>

        </div>

    </main>

    @include('finance.modals.add-invoice')
    @include('finance.modals.add-expense')
    @include('finance.modals.add-payment')

    @include('components.modal')
    @include('components.alert')
    @include('components.confirm')

    <script>
        function renderButton(label, onclick) {
            return `<button onclick="${onclick}" class="w-full lg:w-auto flex-shrink-0 whitespace-nowrap px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium transition-colors">
                        ${label}
                    </button>`;
        }

        function togglePane(activeTab) {
            const panes = {
                invoices: document.getElementById('invoicesPane'),
                expenses: document.getElementById('expensesPane'),
                payments: document.getElementById('paymentsPane'),
            };

            Object.entries(panes).forEach(([tab, pane]) => {
                if (!pane) return;
                pane.classList.toggle('hidden', tab !== activeTab);
            });
        }

        function openAddInvoiceModal() {
            const body = document.getElementById('addInvoiceModal').innerHTML;

            window.openModal('Add Invoice', body, () => {
                const id = document.getElementById('invoiceId').value.trim();
                const company = document.getElementById('invoiceCompany').value.trim();
                const amount = parseFloat(document.getElementById('invoiceAmount').value);

                if (!id) {
                    window.showAlert('error', 'Invoice ID is required');
                    return false;
                }

                if (!company) {
                    window.showAlert('error', 'Company is required');
                    return false;
                }

                if (!amount || amount <= 0) {
                    window.showAlert('error', 'Amount must be greater than 0');
                    return false;
                }

                window.showAlert('success', 'Invoice added successfully');
                return true;
            });
        }

        function openAddExpenseModal() {
            const body = document.getElementById('addExpenseModal').innerHTML;

            window.openModal('Add Expense', body, () => {
                const id = document.getElementById('expenseId').value.trim();
                const category = document.getElementById('expenseCategory').value.trim();
                const amount = parseFloat(document.getElementById('expenseAmount').value);

                if (!id) {
                    window.showAlert('error', 'Expense ID is required');
                    return false;
                }

                if (!category) {
                    window.showAlert('error', 'Category is required');
                    return false;
                }

                if (!amount || amount <= 0) {
                    window.showAlert('error', 'Amount must be greater than 0');
                    return false;
                }

                window.showAlert('success', 'Expense added successfully');
                return true;
            });
        }

        function openAddPaymentModal() {
            const body = document.getElementById('addPaymentModal').innerHTML;

            window.openModal('Add Payment', body, () => {
                const id = document.getElementById('paymentId').value.trim();
                const company = document.getElementById('paymentCompany').value.trim();
                const amount = parseFloat(document.getElementById('paymentAmount').value);

                if (!id) {
                    window.showAlert('error', 'Payment ID is required');
                    return false;
                }

                if (!company) {
                    window.showAlert('error', 'Company is required');
                    return false;
                }

                if (!amount || amount <= 0) {
                    window.showAlert('error', 'Amount must be greater than 0');
                    return false;
                }

                window.showAlert('success', 'Payment added successfully');
                return true;
            });
        }

        function setActiveTab(tab, btnEl) {
            const tabs = ['tabInvoices', 'tabExpenses', 'tabPayments'];

            tabs.forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                el.classList.remove('bg-brand-50', 'text-brand-700', 'border-brand-600', 'border-b-2');
                el.classList.add('text-slate-600');
            });

            if (btnEl) {
                btnEl.classList.add('bg-brand-50', 'text-brand-700', 'border-brand-600', 'border-b-2');
                btnEl.classList.remove('text-slate-600');
            }

            togglePane(tab);

            const actionButton = document.getElementById('actionButton');

            if (tab === 'invoices') {
                actionButton.innerHTML = renderButton('Add Invoice', 'openAddInvoiceModal()');
                return;
            }

            if (tab === 'expenses') {
                actionButton.innerHTML = renderButton('Add Expense', 'openAddExpenseModal()');
                return;
            }

            actionButton.innerHTML = renderButton('Add Payment', 'openAddPaymentModal()');
        }

        document.addEventListener('DOMContentLoaded', () => {
            setActiveTab('invoices', document.getElementById('tabInvoices'));
        });
    </script>


</body>

</html>
