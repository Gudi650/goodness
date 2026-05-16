<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard - Goodness Group</title>
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

    <main class="ml-0 lg:ml-64 pt-16 lg:pt-20 px-4 lg:p-6 min-h-screen bg-slate-50">
        <!-- Top KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Companies -->
            <div class="bg-white rounded-lg border border-slate-200 p-4 border-l-4 border-l-blue-500 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Total Companies</p>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalCompanies }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-blue-100">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-9l9-6 9 6m-1.5 12V7.125M21 3a.75.75 0 00-.75.75v13.5A.75.75 0 0021 17.25m-18 0A.75.75 0 003 17.25V4.875c0-.414.336-.75.75-.75S4.5 4.461 4.5 4.875v12.375A.75.75 0 003 17.25z" />
                    </svg>
                </div>
            </div>

            <!-- Employees -->
            <div class="bg-white rounded-lg border border-slate-200 p-4 border-l-4 border-l-green-500 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Total Employees</p>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalEmployees }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-green-100">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 001.591-.079A8.988 8.988 0 0121 12a8.988 8.988 0 00-5.831-8.312m0 0H15a9.382 9.382 0 00-2.625-.372m0 0a9.337 9.337 0 00-1.591.079A8.988 8.988 0 003 12c0 5.231 4.226 9.5 9.75 9.5.896 0 1.773-.055 2.632-.16m0 0h3.118a9.382 9.382 0 002.625-.372m0 0a9.337 9.337 0 001.591-.079A8.988 8.988 0 0021 12" />
                    </svg>
                </div>
            </div>

            <!-- Total Invoices -->
            <div class="bg-white rounded-lg border border-slate-200 p-4 border-l-4 border-l-amber-500 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Total Invoices</p>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalInvoices }}</p>
                        <p class="text-xs text-amber-600 mt-1">{{ $pendingInvoices }} pending</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-amber-100">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.566.034-1.08.16-1.539.342m-5.801 0C2.904 3.693 1.5 5.109 1.5 6.75v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021 18.75V6.75c0-1.641-1.404-3.075-3.227-3.192A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.48.513C3.904 3.693 2.5 5.109 2.5 6.75v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021 18.75V6.75c0-1.641-1.404-3.075-3.227-3.192" />
                    </svg>
                </div>
            </div>

            <!-- Total Expenses -->
            <div class="bg-white rounded-lg border border-slate-200 p-4 border-l-4 border-l-red-500 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Total Expenses</p>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalExpenses }}</p>
                        <p class="text-xs text-red-600 mt-1">{{ $pendingExpenses }} pending</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-red-100">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 3.071-.879 4.242 0M9.75 11.25a3 3 0 11-6 0 3 3 0 016 0zm7.5-3a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Secondary Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Invoice Amount -->
            <div class="bg-white rounded-lg border border-slate-200 p-4 border-l-4 border-l-blue-400 shadow-sm">
                <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Total Invoice Value</p>
                <p class="text-2xl font-bold text-slate-800 mono">TZS {{ number_format($totalInvoiceAmount ?? 0, 2) }}</p>
            </div>

            <!-- Expense Amount -->
            <div class="bg-white rounded-lg border border-slate-200 p-4 border-l-4 border-l-red-400 shadow-sm">
                <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Total Expenses Value</p>
                <p class="text-2xl font-bold text-slate-800 mono">TZS {{ number_format($totalExpenseAmount ?? 0, 2) }}</p>
            </div>

            <!-- Pending Leaves -->
            <div class="bg-white rounded-lg border border-slate-200 p-4 border-l-4 border-l-purple-500 shadow-sm">
                <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Pending Leave Requests</p>
                <p class="text-2xl font-bold text-slate-800">{{ $pendingLeaves }}</p>
                <p class="text-xs text-slate-600 mt-1">{{ $approvedLeaves }} approved</p>
            </div>

            <!-- Low Stock Alert -->
            <div class="bg-white rounded-lg border border-slate-200 p-4 border-l-4 {{ $lowStockItems > 0 ? 'border-l-orange-500' : 'border-l-slate-400' }} shadow-sm">
                <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Low Stock Items</p>
                <p class="text-2xl font-bold {{ $lowStockItems > 0 ? 'text-orange-600' : 'text-slate-800' }}">{{ $lowStockItems }}</p>
                @if($lowStockItems > 0)
                    <p class="text-xs text-orange-600 mt-1"> Action needed</p>
                @else
                    <p class="text-xs text-green-600 mt-1">✓ All good</p>
                @endif
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Company Performance Table -->
            <section class="lg:col-span-2 bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-4 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                    <h2 class="text-lg font-semibold text-slate-900">Company Performance</h2>
                    <p class="text-sm text-slate-500 mt-1">Overview of revenues and employee count</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left font-semibold">Company</th>
                                <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right font-semibold">Revenue</th>
                                <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right font-semibold">Employees</th>
                                <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($companies as $company)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-3 text-sm font-medium text-slate-900">{{ $company->name }}</td>
                                    <td class="px-4 py-3 text-sm text-right mono text-slate-700">TZS {{ number_format($company->revenue ?? 0, 2) }}</td>
                                    <td class="px-4 py-3 text-sm text-right font-medium text-slate-700">{{ $company->users_count ?? 0 }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-3 py-1 inline-flex items-center rounded-full text-xs font-semibold {{ $company->status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-700' }}">
                                            {{ $company->status ?? 'Active' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-slate-500">No companies registered yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Sidebar: Recent Activity & Alerts -->
            <aside class="space-y-4">
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-sm">
                    <h3 class="font-semibold text-slate-900 mb-3">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="#" class="block px-3 py-2 text-sm bg-brand-50 text-brand-700 rounded hover:bg-brand-100 transition-colors">→ Create Invoice</a>
                        <a href="#" class="block px-3 py-2 text-sm bg-slate-50 text-slate-700 rounded hover:bg-slate-100 transition-colors">→ Record Expense</a>
                        <a href="#" class="block px-3 py-2 text-sm bg-slate-50 text-slate-700 rounded hover:bg-slate-100 transition-colors">→ Review Leaves</a>
                    </div>
                </div>

                <!-- Pending Approvals -->
                @if($pendingInvoices > 0 || $pendingExpenses > 0 || $pendingLeaves > 0)
                    <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-lg border border-amber-200 p-4 shadow-sm">
                        <h3 class="font-semibold text-amber-900 mb-3"> Pending Approvals</h3>
                        <div class="space-y-2 text-sm">
                            @if($pendingInvoices > 0)
                                <div class="flex justify-between">
                                    <span class="text-amber-800">Invoice approvals</span>
                                    <span class="font-bold text-amber-900">{{ $pendingInvoices }}</span>
                                </div>
                            @endif
                            @if($pendingExpenses > 0)
                                <div class="flex justify-between">
                                    <span class="text-amber-800">Expense approvals</span>
                                    <span class="font-bold text-amber-900">{{ $pendingExpenses }}</span>
                                </div>
                            @endif
                            @if($pendingLeaves > 0)
                                <div class="flex justify-between">
                                    <span class="text-amber-800">Leave requests</span>
                                    <span class="font-bold text-amber-900">{{ $pendingLeaves }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Low Stock Alert -->
                @if($lowStockItems > 0)
                    <div class="bg-gradient-to-br from-red-50 to-orange-50 rounded-lg border border-red-200 p-4 shadow-sm">
                        <h3 class="font-semibold text-red-900 mb-2"> Inventory Alert</h3>
                        <p class="text-sm text-red-800">{{ $lowStockItems }} item{{ $lowStockItems > 1 ? 's' : '' }} below reorder level.</p>
                        <a href="#" class="text-xs text-red-700 font-semibold hover:text-red-900 mt-2 inline-block">View inventory →</a>
                    </div>
                @endif

                <!-- Recent Activity -->
                <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-sm">
                    <h3 class="font-semibold text-slate-900 mb-3">Recent Transactions</h3>
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        @forelse ($recentInvoices as $invoice)
                            <div class="pb-2 border-b border-slate-100 last:border-0">
                                <p class="text-xs font-mono text-slate-600">{{ $invoice->invoice_number }}</p>
                                <p class="text-sm text-slate-700 font-medium">TZS {{ number_format($invoice->total_amount ?? 0, 2) }}</p>
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ ucfirst($invoice->status ?? 'pending') }}
                                </span>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500 text-center py-4">No recent invoices</p>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </main>

    @include('components.modal')
    @include('components.alert')
    @include('components.confirm')
</body>

</html>
