<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: Inter, sans-serif; }
        h1, h2, nav, button { font-family: Outfit, sans-serif; }
        .mono { font-family: ui-monospace, monospace; }
    </style>
</head>

<body class="bg-slate-50 text-slate-800">
    @include('components.topbar')
    @include('components.sidebar')

    <main class="ml-0 lg:ml-64 pt-20 p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Finance</h1>
            <p class="text-sm text-slate-500">Invoices, expenses and payments</p>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white border-b border-slate-200 -mx-6 px-6 mb-6">
            <div class="flex gap-8">
                <button onclick="switchTab('invoices', this)" class="tab-btn py-4 text-sm font-medium text-slate-700 border-brand-600 cursor-pointer">Invoices</button>
                <button onclick="switchTab('expenses', this)" class="tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer">Expenses</button>
                <button onclick="switchTab('payments', this)" class="tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer">Payments</button>
                <button onclick="switchTab('accounts', this)" class="tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer">Accounts</button>
            </div>
        </div>

        <!-- Action Button Row -->
        <div class="flex flex-col gap-3 mb-4 lg:flex-row lg:items-center lg:justify-between">
            <h2 id="sectionTitle" class="text-lg font-semibold">Invoices</h2>
            <div id="actionButton" class="w-full lg:w-auto"></div>
        </div>

        <!-- Content Container -->
        <div class="bg-white border border-slate-200 rounded-lg p-4">
            <div id="tabContent" class="space-y-6">
                @include('finance.invoices', ['invoices' => $invoices])
                @include('finance.expenses', ['expenses' => $expenses])
                @include('finance.payments', ['payments' => $payments])
                @include('finance.accounts',['virtualAccounts' => $virtualAccounts])
            </div>
        </div>
    </main>

    <!-- Modals -->
    @include('finance.modals.add-invoice')
    @include('finance.modals.add-expense')
    @include('finance.modals.add-payment')
    @include('finance.modals.add-accounts')

    <!-- Shared Components -->
    @include('components.modal')
    @include('components.alert')
    @include('components.confirm')

    <!-- Scripts -->
    @include('finance.scripts')
</body>

</html>
