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
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Depreciation Percentage</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($fixedAssets as $asset)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 mono text-xs text-slate-500">{{ $asset['code'] }}</td>
                            <td class="px-4 py-3">{{ $asset->category->category ?? '-' }}</td>
                            <td class="px-4 py-3 font-medium">{{ $asset['name'] }}</td>
                            <td class="px-4 py-3 text-right mono">TZS {{ number_format($asset['original_value'], 0) }}
                            </td>
                            <td class="px-4 py-3 text-right mono">TZS {{ number_format($asset['current_value'], 0) }}
                            </td>
                            <td class="px-4 py-3 ">{{ $asset['acquired'] }}</td>
                            <td class="px-4 py-3 "> 
                                {{ $asset['depreciation_value'] ? $asset['depreciation_value'] . '%' : '-' }}
                            </td>

                            <td class="px-4 py-3">
                                @if ($asset['status'] === 'Active')
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">{{ $asset['status'] }}</span>
                                @elseif($asset['status'] === 'Disposed')
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700 border border-red-200">{{ $asset['status'] }}</span>
                                @else
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700 border border-yellow-200">{{ $asset['status'] }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-3 text-center text-slate-500">No assets found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

        {{-- 
        <!-- Summary -->
        <div class="mt-8 p-6 bg-white border rounded shadow-sm">
            <h3 class="text-md font-semibold mb-2">Summary</h3>
            <ul class="text-sm space-y-1">
                <li>Total Original Cost: <span class="mono">TZS 590,000,000</span></li>
                <li>Total Current Value: <span class="mono">TZS 535,000,000</span></li>
                <li class="font-bold">Net Book Value: <span class="mono">TZS 535,000,000</span></li>
            </ul>
        </div>
         --}}

    </main>

    @include('components.modal')
    @include('components.alert')
    @include('components.confirm')

</body>

</html>
