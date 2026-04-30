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

    <main class="ml-0 lg:ml-64 pt-20 p-6 min-h-screen">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Finance</h1>
            <p class="text-sm text-slate-500">Invoices, expenses and payments</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-lg p-4">
            <div class="flex gap-2 mb-4">
                <button id="tabInvoices" onclick="setActiveTab('invoices', this)"
                    class="px-3 py-2 bg-green-50 text-green-700  border-b-2 border-brand-600">Invoices</button>
                <button id="tabExpenses" onclick="setActiveTab('expenses', this)"
                    class="px-3 py-2 text-slate-600 ">Expenses</button>
                <button id="tabPayments" onclick="setActiveTab('payments', this)"
                    class="px-3 py-2 text-slate-600 ">Payments</button>
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
        }, {
            id: 'INV-002',
            company: 'Goodness Kenya Ltd',
            amount: 850000,
            status: 'Unpaid'
        }];

        function formatTZS(n) {
            return 'TZS ' + n.toLocaleString();
        }

        function renderInvoices() {
            return `<div class="overflow-x-auto"><table class="min-w-full"><thead class="bg-slate-50"><tr><th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Invoice</th><th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Company</th><th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Amount</th><th class="text-xs text-slate-500 uppercase px-4 py-3 text-center">Status</th></tr></thead><tbody class="divide-y divide-slate-100">${invoices.map(i=>`<tr><td class="px-4 py-3 text-sm">${i.id}</td><td class="px-4 py-3 text-sm">${i.company}</td><td class="px-4 py-3 text-sm text-right mono">${formatTZS(i.amount)}</td><td class="px-4 py-3 text-sm text-center"><span class="inline-block px-2 py-1 ${i.status==='Paid'?'bg-green-50 text-green-700':'bg-slate-50 text-slate-700'} rounded-md text-xs">${i.status}</span></td></tr>`).join('')}</tbody></table></div>`;
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
            if (tab === 'invoices') content.innerHTML = renderInvoices();
            if (tab === 'expenses') content.innerHTML = '<p class="text-sm text-slate-500">No expenses yet.</p>';
            if (tab === 'payments') content.innerHTML = '<p class="text-sm text-slate-500">No payments yet.</p>';
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
