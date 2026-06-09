<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Fixed Asset Register - Goodness ERP</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
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
                        brand: { 50: '#fff8e5', 100: '#fde6a1', 500: '#f0b73a', 600: '#eaa106', 700: '#c88600' }
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

        <!-- Page Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Fixed Asset Register (FAR)</h1>
            <span class="text-sm text-slate-500">Generated on Jun 09, 2026 12:15 PM</span>
        </div>

        <!-- Assets Table -->
        <div class="bg-white border rounded shadow-sm">
            <h2 class="text-lg font-semibold px-4 py-3 border-b">Fixed Assets</h2>
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Code</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Asset Name</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Original Cost</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Current Value</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Acquired Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 mono text-xs text-slate-500">AST-0001</td>
                        <td class="px-4 py-3">Property</td>
                        <td class="px-4 py-3 font-medium">Office Building</td>
                        <td class="px-4 py-3 text-right mono">TZS 500,000,000</td>
                        <td class="px-4 py-3 text-right mono">TZS 450,000,000</td>
                        <td class="px-4 py-3">Jan 15, 2020</td>
                        <td class="px-4 py-3">Active</td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 mono text-xs text-slate-500">AST-0002</td>
                        <td class="px-4 py-3">Vehicles</td>
                        <td class="px-4 py-3 font-medium">Toyota Hilux</td>
                        <td class="px-4 py-3 text-right mono">TZS 90,000,000</td>
                        <td class="px-4 py-3 text-right mono">TZS 85,000,000</td>
                        <td class="px-4 py-3">Mar 10, 2021</td>
                        <td class="px-4 py-3">Active</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="mt-8 p-6 bg-white border rounded shadow-sm">
            <h3 class="text-md font-semibold mb-2">Summary</h3>
            <ul class="text-sm space-y-1">
                <li>Total Original Cost: <span class="mono">TZS 590,000,000</span></li>
                <li>Total Current Value: <span class="mono">TZS 535,000,000</span></li>
                <li class="font-bold">Net Book Value: <span class="mono">TZS 535,000,000</span></li>
            </ul>
        </div>

    </main>

    @include('components.modal')
    @include('components.alert')
    @include('components.confirm')

</body>
</html>
