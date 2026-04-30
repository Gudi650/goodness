<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard — Goodness Group</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { brand: {50:'#f0fdf4',100:'#dcfce7',500:'#22c55e',600:'#16a34a',700:'#15803d'} }, fontFamily:{ sans:['Inter','sans-serif'], display:['Outfit','sans-serif'] } } } }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <style>body{font-family:Inter, sans-serif}h1,h2,nav,button{font-family:Outfit, sans-serif}.mono{font-family:ui-monospace,monospace}</style>
</head>
<body class="bg-slate-50 text-slate-800">
    <div class="flex">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 h-screen fixed bg-white border-r border-slate-200 hidden md:block">
            <div class="p-6">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-brand-600"></div>
                    <div>
                        <div class="text-sm font-semibold text-slate-800">Goodness Group</div>
                        <div class="text-xs text-slate-500">Enterprise</div>
                    </div>
                </div>
            </div>
            <nav class="px-3 py-4 space-y-1">
                <a href="/dashboard" class="block px-4 py-2 rounded-l-md text-sm font-medium bg-green-50 text-green-700 border-l-4 border-green-600">Dashboard</a>
                <a href="/companies" class="block px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Companies</a>
                <a href="/users" class="block px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Users</a>
                <a href="/finance" class="block px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Finance</a>
                <a href="/hrm" class="block px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">HRM</a>
                <a href="/sales" class="block px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Sales</a>
                <a href="/inventory" class="block px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Inventory</a>
                <a href="/reports" class="block px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Reports</a>
            </nav>
        </aside>

        <!-- Main area -->
        <div class="flex-1 ml-0 md:ml-64">
            <!-- Header -->
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 md:px-8">
                <div class="flex items-center gap-4">
                    <button id="mobileToggle" class="md:hidden p-2" aria-label="Toggle sidebar">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h1 class="text-2xl font-semibold text-slate-800">Dashboard</h1>
                </div>
                <div class="flex items-center gap-4 text-sm">
                    <select class="border border-slate-200 rounded-md px-3 py-1 text-slate-700">
                        <option>Goodness Group HQ</option>
                    </select>
                    <div class="relative">
                        <button class="p-2 text-slate-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L3 17h5"/></svg></button>
                        <span class="absolute top-0 right-0 -mt-1 -mr-1 inline-block w-2 h-2 bg-green-600 rounded-full"></span>
                    </div>
                    <div class="text-sm text-slate-700">John Doe <span class="ml-2 px-2 py-1 bg-slate-100 text-slate-600 rounded-md text-xs">Admin</span></div>
                </div>
            </header>

            <main class="p-6 bg-slate-50 min-h-screen">
                <!-- Stats -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex justify-between items-center border-l-4 border-green-600">
                        <div>
                            <div class="text-sm text-slate-500">Total Companies</div>
                            <div class="text-2xl font-semibold">12</div>
                        </div>
                        <div class="text-right mono text-slate-700">TZS 0</div>
                    </div>
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex justify-between items-center border-l-4 border-green-600">
                        <div>
                            <div class="text-sm text-slate-500">Total Employees</div>
                            <div class="text-2xl font-semibold">847</div>
                        </div>
                        <div class="mono text-slate-700">—</div>
                    </div>
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex justify-between items-center border-l-4 border-green-600">
                        <div>
                            <div class="text-sm text-slate-500">Monthly Revenue</div>
                            <div class="text-2xl font-semibold mono">TZS 5,250,000</div>
                        </div>
                        <div class="text-slate-400 text-xs">This month</div>
                    </div>
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex justify-between items-center border-l-4 border-green-600">
                        <div>
                            <div class="text-sm text-slate-500">Active Users</div>
                            <div class="text-2xl font-semibold">156</div>
                        </div>
                        <div class="text-slate-400 text-xs">Online</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <section class="lg:col-span-2">
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 mb-4">
                            <h2 class="text-2xl font-semibold text-slate-800">Company Performance</h2>
                            <p class="text-sm text-slate-500">Overview of revenues and headcount</p>
                        </div>

                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-left">Company Name</th>
                                        <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-right">Revenue (TZS)</th>
                                        <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-right">Employees</th>
                                        <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-left">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr class="hover:bg-slate-50"><td class="px-4 py-3 text-sm">Goodness Tanzania Ltd</td><td class="px-4 py-3 text-sm text-right mono">TZS 2,500,000</td><td class="px-4 py-3 text-sm text-right">320</td><td class="px-4 py-3 text-sm"><span class="inline-block px-2 py-1 bg-green-50 text-green-700 rounded-md text-xs">Active</span></td></tr>
                                    <tr class="hover:bg-slate-50"><td class="px-4 py-3 text-sm">Goodness Kenya Ltd</td><td class="px-4 py-3 text-sm text-right mono">TZS 1,550,000</td><td class="px-4 py-3 text-sm text-right">215</td><td class="px-4 py-3 text-sm"><span class="inline-block px-2 py-1 bg-green-50 text-green-700 rounded-md text-xs">Active</span></td></tr>
                                    <tr class="hover:bg-slate-50"><td class="px-4 py-3 text-sm">Goodness Uganda Ltd</td><td class="px-4 py-3 text-sm text-right mono">TZS 1,200,000</td><td class="px-4 py-3 text-sm text-right">142</td><td class="px-4 py-3 text-sm"><span class="inline-block px-2 py-1 bg-slate-50 text-slate-600 rounded-md text-xs">Inactive</span></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <aside>
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4">
                            <h3 class="text-lg font-semibold text-slate-800">Recent Activity</h3>
                            <div class="mt-3 space-y-3 text-sm text-slate-700">
                                <div class="px-2 py-2 bg-slate-50 rounded-md">A new invoice was paid for Goodness Tanzania Ltd.</div>
                                <div class="px-2 py-2 bg-slate-50 rounded-md">Jane Smith was added to Goodness Kenya Ltd.</div>
                                <div class="px-2 py-2 bg-slate-50 rounded-md">Purchase order PO-003 created.</div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 mt-4">
                            <h4 class="text-sm text-slate-500">Shortcuts</h4>
                            <div class="mt-3 grid grid-cols-2 gap-2">
                                <a href="/companies" class="px-3 py-2 border border-slate-200 rounded-md text-sm text-slate-700 hover:bg-slate-50">Companies</a>
                                <a href="/users" class="px-3 py-2 border border-slate-200 rounded-md text-sm text-slate-700 hover:bg-slate-50">Users</a>
                                <a href="/finance" class="px-3 py-2 border border-slate-200 rounded-md text-sm text-slate-700 hover:bg-slate-50">Finance</a>
                                <a href="/reports" class="px-3 py-2 border border-slate-200 rounded-md text-sm text-slate-700 hover:bg-slate-50">Reports</a>
                            </div>
                        </div>
                    </aside>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Mobile sidebar toggle
        const sidebar = document.getElementById('sidebar');
        document.getElementById('mobileToggle').addEventListener('click', ()=> sidebar.classList.toggle('hidden'));
        // Logout placeholder
        function logout(){ if(confirm('Logout?')) location.href='/login' }
    </script>
</body>
</html>
