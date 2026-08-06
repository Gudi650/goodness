<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>VAT Account - Goodness ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        nav,
        button {
            font-family: 'Outfit', sans-serif;
        }

        .mono {
            font-family: ui-monospace, monospace;
        }
    </style>
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
            <h1 class="text-2xl font-bold">VAT Account</h1>
            <span class="text-sm text-slate-500">Period: {{ $period ?? '' }}</span>
        </div>

        <!-- VAT Summary -->
        <div class="grid gap-4 md:grid-cols-3 mb-6">

            <div class="bg-white border rounded shadow-sm p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Output VAT (Sales)</p>
                <p class="mt-1 text-xl font-bold text-slate-800 mono">TZS {{ number_format($outputVat ?? 0, 0) }}</p>
                <p class="mt-1 text-xs text-slate-500">VAT collected on sales</p>
            </div>

            <div class="bg-white border rounded shadow-sm p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Input VAT (Purchases)</p>
                <p class="mt-1 text-xl font-bold text-slate-800 mono">TZS {{ number_format($inputVat ?? 0, 0) }}</p>
                <p class="mt-1 text-xs text-slate-500">VAT paid on purchases</p>
            </div>

            <div class="bg-white border rounded shadow-sm p-4 border-red-200">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">

                    {{   $vatPayable > 0 ? 'VAT Payable' : 'VAT Receivable' }}
                    
                </p>

                {{-- here make sure it doesnt display negative number --}}
                <p class="mt-1 text-xl font-bold mono text-red-600">

                    TZS {{ number_format(abs($vatPayable ?? 0), 0) }}

                </p>

                <p class="mt-1 text-xs text-slate-500">Input VAT &minus; Output VAT</p>
            </div>

        </div>

        <!-- Output VAT Table -->
        <div class="bg-white border rounded shadow-sm mb-6">
            <h2 class="text-lg font-semibold px-4 py-3 border-b">Output VAT </h2>
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Ref No.</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Customer</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Taxable Value</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">VAT Rate</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase">VAT Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">

                    @forelse(($outputRows ?? []) as $row)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 mono text-xs text-slate-500">{{ $row['ref_no'] }}</td>
                        <td class="px-4 py-3 font-medium">{{ $row['name'] }}</td>
                        <td class="px-4 py-3 text-right mono">TZS {{ number_format($row['taxable_value'], 0) }}</td>
                        <td class="px-4 py-3">{{ $row['vat_rate'] }}%</td>
                        <td class="px-4 py-3 text-right mono">TZS {{ number_format($row['vat_amount'], 0) }}</td>
                        <td class="px-4 py-3">{{ $row['date'] }}</td>
                        <td class="px-4 py-3"><span class="inline-flex px-2 py-0.5 rounded-full text-xs border {{ $row['status_class'] }}">{{ ucfirst($row['status']) }}</span></td>
                        <td></td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-4 py-6 text-center text-sm text-slate-500">No output VAT records found.</td></tr>
                    @endforelse

                </tbody>
                <tfoot class="bg-slate-50 border-t border-slate-200">
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-right font-semibold">Total Output VAT</td>
                        <td class="px-4 py-3 text-right font-semibold mono">TZS {{ number_format($outputVat ?? 0, 0) }}</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Input VAT Table -->
        <div class="bg-white border rounded shadow-sm mb-6">
            <h2 class="text-lg font-semibold px-4 py-3 border-b">Input VAT</h2>
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Ref No.</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Supplier</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Taxable Value</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">VAT Rate</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase">VAT Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">

                    @forelse(($inputRows ?? []) as $row)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 mono text-xs text-slate-500">{{ $row['ref_no'] }}</td>
                        <td class="px-4 py-3 font-medium">{{ $row['name'] }}</td>
                        <td class="px-4 py-3 text-right mono">TZS {{ number_format($row['taxable_value'], 0) }}</td>
                        <td class="px-4 py-3">{{ $row['vat_rate'] }}%</td>
                        <td class="px-4 py-3 text-right mono">TZS {{ number_format($row['vat_amount'], 0) }}</td>
                        <td class="px-4 py-3">{{ $row['date'] }}</td>
                        <td class="px-4 py-3"><span class="inline-flex px-2 py-0.5 rounded-full text-xs border {{ $row['status_class'] }}">{{ ucfirst($row['status']) }}</span></td>
                        <td></td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-4 py-6 text-center text-sm text-slate-500">No input VAT records found.</td></tr>
                    @endforelse

                </tbody>
                <tfoot class="bg-slate-50 border-t border-slate-200">
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-right font-semibold">Total Input VAT</td>
                        <td class="px-4 py-3 text-right font-semibold mono">TZS {{ number_format($inputVat ?? 0, 0) }}</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- VAT Reconciliation -->
        <div class="p-6 bg-white border rounded shadow-sm">
            <h3 class="text-md font-semibold mb-2">VAT Reconciliation</h3>
            <ul class="text-sm space-y-1">
                <li>Total Output VAT: <span class="mono">TZS {{ number_format($outputVat ?? 0, 0) }}</span></li>
                <li>Total Input VAT: <span class="mono">TZS {{ number_format($inputVat ?? 0, 0) }}</span></li>
                <li class="font-bold">{{   $vatPayable > 0 ? 'VAT Payable' : 'VAT Receivable' }} : <span class="{{ ($vatPayable > 0 ? 'mono text-green-600' : 'mono text-red-600') }}">
                    TZS {{ number_format(abs($vatPayable ?? 0), 0) }}
                </span>
                </li>
            </ul>
        </div>

    </main>

    <script>
        function toggleVatDetails(id) {}
    </script>

</body>

</html>
