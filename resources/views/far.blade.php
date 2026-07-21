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

            <span class="text-sm text-slate-500">

                {{-- button to add fixed assets in the page 
                <button onclick="openAddAssetModal()"
                    class="inline-flex items-center gap-1 px-4 py-2 bg-brand-600 text-white rounded hover:bg-brand-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Asset
                </button>
                --}}

            </span>
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
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Net Book Value</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Acquired Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Depreciation Percentage</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Action</th>
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

                            <td>
                                <div class="flex items-center justify-center gap-6">

                                    {{-- 
                                    <a href=""
                                        class="text-emerald-600 hover:text-emerald-800 transition-colors"
                                        title="Download invoice PDF" aria-label="Download invoice PDF">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 3v12m0 0-4-4m4 4 4-4M4.5 20.25h15" />
                                        </svg>
                                    </a>
                                 --}}

                                    <button type="button" onclick="toggleFarDetails('{{ $asset['id'] }}')"
                                        class="text-slate-600 hover:text-slate-800 transition-colors"
                                        title="View invoice details" aria-label="View invoice details">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </button>

                                    {{-- editing buttons as well 

                                    <button type="button" class="text-blue-600 hover:text-blue-800 transition-colors"
                                        title="Edit invoice" aria-label="Edit invoice"
                                        onclick="openEditInvoiceModal({{ $asset['id'] }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a2.25 2.25 0 1 1 3.182 3.182L10.582 17.13a4.5 4.5 0 0 1-1.897 1.13L6 19l.74-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487ZM16.862 4.487 19.5 7.125" />
                                        </svg>
                                    </button>
                                    
                                    --}}

                                    {{--  
                                    <form action="{{ route('invoices.destroy', $invoice['id']) }}" method="POST"
                                        onsubmit="return confirm('Delete invoice {{ $invoice['invoice_number'] }}?');"
                                        class="inline-flex">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="deleteInvoice({{ $invoice['id'] }})" title="Delete"  class="text-red-600 hover:text-red-700 transition-colors"
                                            title="Delete invoice" aria-label="Delete invoice">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.245-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0V4.875c0-1.035-.84-1.875-1.875-1.875h-3.75C9.09 3 8.25 3.84 8.25 4.875v.518" />
                                            </svg>
                                        </button>
                                    </form> 
                                 --}}
                                </div>
                            </td>
                        </tr>
                        <tr id="fixedassets-details-{{ $asset['id'] }}" class="hidden bg-slate-50/70">
                            <td colspan="9" class="px-4 py-4">
                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">

                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Code:
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ $asset['code'] }}
                                    </p>
                                </div>

                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Category:
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ $asset->category->category ?? '-' }}
                                    </p>
                                </div>

                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Type:
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ number_format($asset['value'], 2) }}
                                        {{ $asset['currency'] }}
                                    </p>
                                </div>

                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Term:
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ $asset['term'] }}
                                    </p>
                                </div>

                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Status:
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ $asset['status'] }}
                                    </p>
                                </div>

                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Original Value: TZS
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ ucfirst($asset['original_value']) }}
                                    </p>
                                </div>

                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Depreciation value: TZS
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ ucfirst($asset['depreciation_value']) }}
                                    </p>
                                </div>

                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Net Book Value: TZS
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ $asset['current_value'] }}
                                    </p>
                                </div>

                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Accumulated Depreciation: TZS
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ $asset['current_value'] }}
                                    </p>
                                </div>

                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Acquired: 
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ $asset['acquired'] }}
                                    </p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Created At: 
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ $asset['created_at'] }} 
                                    </p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Updated At: 
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ $asset['updated_at'] ?? 'N/A' }}
                                    </p>
                                </div>

                            </div>
                        </td>

                                </div>
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

        {{-- here we will put the summary as well 
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

    <script>
        function toggleFarDetails(id) {
            console.log('Toggling details for asset ID:', id);

            const detailsRow = document.getElementById(`fixedassets-details-${id}`);

            if (detailsRow) {
                detailsRow.classList.toggle('hidden');
                console.log(
                    `Details row for asset ID ${id} is now ${detailsRow.classList.contains('hidden') ? 'hidden' : 'visible'}.`
                );
            }
        }

        /*
        function toggleFarDetails(id) {
            const targetRow = document.getElementById(`fixedassets-details-${invoiceId}`);
            if (!targetRow) {
                return;
            }

            const shouldOpen = targetRow.classList.contains('hidden');

            document.querySelectorAll('[id^="fixedassets-details-"]').forEach(row => {
                row.classList.add('hidden');
            });

            if (shouldOpen) {
                targetRow.classList.remove('hidden');
            }
        }
        */
    </script>

</body>

</html>
