<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard - Goodness Group</title>
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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg border border-slate-200 p-4 border-l-4 border-green-600">
                <div class="text-sm text-slate-500">Total Companies</div>
                <div class="text-2xl font-semibold">12</div>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 p-4 border-l-4 border-green-600">
                <div class="text-sm text-slate-500">Total Employees</div>
                <div class="text-2xl font-semibold">847</div>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 p-4 border-l-4 border-green-600">
                <div class="text-sm text-slate-500">Monthly Revenue</div>
                <div class="text-2xl font-semibold mono">TZS 5,250,000</div>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 p-4 border-l-4 border-green-600">
                <div class="text-sm text-slate-500">Active Users</div>
                <div class="text-2xl font-semibold">156</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <section class="lg:col-span-2 bg-white rounded-lg border border-slate-200 overflow-x-auto">
                <div class="p-4 border-b border-slate-100">
                    <h2 class="text-xl font-semibold">Company Performance</h2>
                    <p class="text-sm text-slate-500">Overview of revenues and headcount</p>
                </div>
                <table class="min-w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Company</th>
                            <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Revenue</th>
                            <th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Employees</th>
                            <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="px-4 py-3 text-sm">Goodness Tanzania Ltd</td>
                            <td class="px-4 py-3 text-sm text-right mono">TZS 2,500,000</td>
                            <td class="px-4 py-3 text-sm text-right">320</td>
                            <td class="px-4 py-3 text-sm"><span
                                    class="px-2 py-1 bg-green-50 text-green-700 rounded-md text-xs">Active</span></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm">Goodness Kenya Ltd</td>
                            <td class="px-4 py-3 text-sm text-right mono">TZS 1,550,000</td>
                            <td class="px-4 py-3 text-sm text-right">215</td>
                            <td class="px-4 py-3 text-sm"><span
                                    class="px-2 py-1 bg-green-50 text-green-700 rounded-md text-xs">Active</span></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm">Goodness Uganda Ltd</td>
                            <td class="px-4 py-3 text-sm text-right mono">TZS 1,200,000</td>
                            <td class="px-4 py-3 text-sm text-right">142</td>
                            <td class="px-4 py-3 text-sm"><span
                                    class="px-2 py-1 bg-slate-50 text-slate-600 rounded-md text-xs">Inactive</span></td>
                        </tr>
                    </tbody>
                </table>
            </section>
            <aside class="space-y-4">
                <div class="bg-white rounded-lg border border-slate-200 p-4">
                    <h3 class="font-semibold mb-3">Recent Activity</h3>
                    <div class="space-y-2 text-sm text-slate-700">
                        <div class="px-2 py-2 bg-slate-50 rounded">A new invoice was paid for Goodness Tanzania Ltd.
                        </div>
                        <div class="px-2 py-2 bg-slate-50 rounded">Jane Smith was added to Goodness Kenya Ltd.</div>
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
