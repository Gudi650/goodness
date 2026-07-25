<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Production & Hatchery - Goodness Hatchery</title>
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
        h1, h2, h3, nav, button { font-family: Outfit, sans-serif; }
        .mono { font-family: ui-monospace, monospace; }
    </style>
</head>

<body class="bg-slate-50 text-slate-800">
    @include('components.topbar')
    @include('components.sidebar')

    <main class="ml-0 lg:ml-64 pt-20 p-6">

        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Production & Hatchery</h1>
            <p class="text-sm text-slate-500">Breeder flocks, egg collection, incubation batches, hatch registration and chick allocation</p>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white border-b border-slate-200 -mx-6 px-6 mb-6">
            <div class="flex gap-8 overflow-x-auto">
                <button onclick="switchPhTab('flocks', this)" class="ph-tab-btn py-4 text-sm font-medium text-slate-700 border-b-2 border-brand-600 cursor-pointer whitespace-nowrap">Breeder Flocks</button>
                <button onclick="switchPhTab('eggs', this)" class="ph-tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer whitespace-nowrap">Egg Collection</button>
                <button onclick="switchPhTab('batches', this)" class="ph-tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer whitespace-nowrap">Incubation Batches</button>
                <button onclick="switchPhTab('hatch', this)" class="ph-tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer whitespace-nowrap">Hatch Registration</button>
                <button onclick="switchPhTab('allocation', this)" class="ph-tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer whitespace-nowrap">Chick Allocation</button>
                <button onclick="switchPhTab('costing', this)" class="ph-tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer whitespace-nowrap">Batch Costing</button>
            </div>
        </div>

        <!-- Action Row -->
        <div class="flex flex-col gap-3 mb-4 lg:flex-row lg:items-center lg:justify-between">
            <h2 id="phSectionTitle" class="text-lg font-semibold">Breeder Flocks</h2>

            <div class="flex gap-6">
                {{--
                <button onclick="openAddFlockModal()"
                    class="inline-flex items-center gap-1 px-4 py-2 bg-brand-600 text-white rounded hover:bg-brand-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Flock
                </button>
                --}}
                <div id="phActionButton" class="w-full lg:w-auto"></div>
            </div>
        </div>

        <!-- Content Container -->
        <div class="bg-white border border-slate-200 rounded-lg p-4">
            <div id="phTabContent" class="space-y-6">

                {{-- ============================= BREEDER FLOCKS ============================= --}}
                <div id="ph-tab-flocks" class="ph-tab-panel">
                    <div class="bg-white border rounded shadow-sm">
                        <h2 class="text-lg font-semibold px-4 py-3 border-b">Breeder Flock Lifecycle</h2>
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Flock Code</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Breed</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">House / Location</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Bird Count</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Age (weeks)</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Placement Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($flocks ?? [] as $flock)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 mono text-xs text-slate-500">{{ $flock['code'] }}</td>
                                        <td class="px-4 py-3 font-medium">{{ $flock['breed'] }}</td>
                                        <td class="px-4 py-3">{{ $flock['house'] }}</td>
                                        <td class="px-4 py-3 text-right mono">{{ number_format($flock['bird_count']) }}</td>
                                        <td class="px-4 py-3">{{ $flock['age_weeks'] }}</td>
                                        <td class="px-4 py-3">{{ $flock['placement_date'] }}</td>
                                        <td class="px-4 py-3">
                                            @if ($flock['status'] === 'Laying')
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">Laying</span>
                                            @elseif($flock['status'] === 'Rearing')
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700 border border-yellow-200">Rearing</span>
                                            @else
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700 border border-red-200">{{ $flock['status'] }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-center gap-6">
                                                <button type="button" onclick="togglePhDetails('flock-{{ $flock['id'] }}')"
                                                    class="text-slate-600 hover:text-slate-800 transition-colors"
                                                    title="View flock details" aria-label="View flock details">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="ph-details-flock-{{ $flock['id'] }}" class="hidden bg-slate-50/70">
                                        <td colspan="8" class="px-4 py-4">
                                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Supplier / Origin</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $flock['supplier'] ?? '-' }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Mortality to Date</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $flock['mortality'] ?? '-' }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Avg Daily Egg Rate</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $flock['egg_rate'] ?? '-' }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Supervisor</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $flock['supervisor'] ?? '-' }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Created At</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $flock['created_at'] ?? '-' }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Updated At</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $flock['updated_at'] ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-3 text-center text-slate-500">No breeder flocks registered</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ============================= EGG COLLECTION ============================= --}}
                <div id="ph-tab-eggs" class="ph-tab-panel hidden">
                    <div class="bg-white border rounded shadow-sm">
                        <h2 class="text-lg font-semibold px-4 py-3 border-b">Daily Egg Collection & Grading</h2>
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Flock</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Total Collected</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Grade A</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Grade B</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Cracked / Rejected</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Collected By</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($eggCollections ?? [] as $entry)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3">{{ $entry['date'] }}</td>
                                        <td class="px-4 py-3 font-medium">{{ $entry['flock'] }}</td>
                                        <td class="px-4 py-3 text-right mono">{{ number_format($entry['total']) }}</td>
                                        <td class="px-4 py-3 text-right mono">{{ number_format($entry['grade_a']) }}</td>
                                        <td class="px-4 py-3 text-right mono">{{ number_format($entry['grade_b']) }}</td>
                                        <td class="px-4 py-3 text-right mono">{{ number_format($entry['rejected']) }}</td>
                                        <td class="px-4 py-3">{{ $entry['collected_by'] }}</td>
                                        <td class="px-4 py-3">
                                            @if ($entry['status'] === 'Sent to Store')
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">Sent to Store</span>
                                            @else
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700 border border-yellow-200">{{ $entry['status'] }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-3 text-center text-slate-500">No egg collection logs for today</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ============================= INCUBATION BATCHES ============================= --}}
                <div id="ph-tab-batches" class="ph-tab-panel hidden">
                    <div class="bg-white border rounded shadow-sm">
                        <h2 class="text-lg font-semibold px-4 py-3 border-b">Batch Creation & Incubation Tracking</h2>
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Batch Code</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Machine (Setter)</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Eggs Set</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Set Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Expected Hatch Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Day of Incubation</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($batches ?? [] as $batch)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 mono text-xs text-slate-500">{{ $batch['code'] }}</td>
                                        <td class="px-4 py-3 font-medium">{{ $batch['machine'] }}</td>
                                        <td class="px-4 py-3 text-right mono">{{ number_format($batch['eggs_set']) }}</td>
                                        <td class="px-4 py-3">{{ $batch['set_date'] }}</td>
                                        <td class="px-4 py-3">{{ $batch['expected_hatch_date'] }}</td>
                                        <td class="px-4 py-3">{{ $batch['incubation_day'] }} / 21</td>
                                        <td class="px-4 py-3">
                                            @if ($batch['status'] === 'Incubating')
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700 border border-yellow-200">Incubating</span>
                                            @elseif($batch['status'] === 'Transferred')
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-700 border border-blue-200">Transferred</span>
                                            @elseif($batch['status'] === 'Hatched')
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">Hatched</span>
                                            @else
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700 border border-red-200">{{ $batch['status'] }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-center gap-6">
                                                <button type="button" onclick="togglePhDetails('batch-{{ $batch['id'] }}')"
                                                    class="text-slate-600 hover:text-slate-800 transition-colors"
                                                    title="View batch details" aria-label="View batch details">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="ph-details-batch-{{ $batch['id'] }}" class="hidden bg-slate-50/70">
                                        <td colspan="8" class="px-4 py-4">
                                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Source Flock(s)</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $batch['source_flocks'] ?? '-' }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Transfer Date</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $batch['transfer_date'] ?? '-' }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Hatcher Machine</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $batch['hatcher_machine'] ?? '-' }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Candling Result (Fertile)</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $batch['fertile_count'] ?? '-' }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Created At</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $batch['created_at'] ?? '-' }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Updated At</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $batch['updated_at'] ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-3 text-center text-slate-500">No active batches</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ============================= HATCH REGISTRATION ============================= --}}
                <div id="ph-tab-hatch" class="ph-tab-panel hidden">
                    <div class="bg-white border rounded shadow-sm">
                        <h2 class="text-lg font-semibold px-4 py-3 border-b">Hatch Registration</h2>
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Batch Code</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Hatch Date</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Eggs Set</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Chicks Hatched</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Hatch Rate</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Culls / Rejects</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Registered By</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($hatchRegistrations ?? [] as $hatch)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 mono text-xs text-slate-500">{{ $hatch['batch_code'] }}</td>
                                        <td class="px-4 py-3">{{ $hatch['hatch_date'] }}</td>
                                        <td class="px-4 py-3 text-right mono">{{ number_format($hatch['eggs_set']) }}</td>
                                        <td class="px-4 py-3 text-right mono">{{ number_format($hatch['chicks_hatched']) }}</td>
                                        <td class="px-4 py-3 text-right mono">{{ $hatch['hatch_rate'] }}%</td>
                                        <td class="px-4 py-3 text-right mono">{{ number_format($hatch['culls']) }}</td>
                                        <td class="px-4 py-3">{{ $hatch['registered_by'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-3 text-center text-slate-500">No hatches registered yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ============================= CHICK ALLOCATION ============================= --}}
                <div id="ph-tab-allocation" class="ph-tab-panel hidden">
                    <div class="bg-white border rounded shadow-sm">
                        <h2 class="text-lg font-semibold px-4 py-3 border-b">Chick Allocation</h2>
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Batch Code</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Allocated To</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Party Type</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Quantity</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Allocation Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($chickAllocations ?? [] as $alloc)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 mono text-xs text-slate-500">{{ $alloc['batch_code'] }}</td>
                                        <td class="px-4 py-3 font-medium">{{ $alloc['allocated_to'] }}</td>
                                        <td class="px-4 py-3">{{ $alloc['party_type'] }}</td>
                                        <td class="px-4 py-3 text-right mono">{{ number_format($alloc['quantity']) }}</td>
                                        <td class="px-4 py-3">{{ $alloc['allocation_date'] }}</td>
                                        <td class="px-4 py-3">
                                            @if ($alloc['status'] === 'Delivered')
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">Delivered</span>
                                            @else
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700 border border-yellow-200">{{ $alloc['status'] }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-3 text-center text-slate-500">No chick allocations recorded</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ============================= BATCH COSTING ============================= --}}
                <div id="ph-tab-costing" class="ph-tab-panel hidden">
                    <div class="bg-white border rounded shadow-sm">
                        <h2 class="text-lg font-semibold px-4 py-3 border-b">Batch-Level Costing & Party Profitability</h2>
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Batch Code</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Party</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Total Cost</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Revenue</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Margin</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Cost / Chick</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($batchCosting ?? [] as $cost)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 mono text-xs text-slate-500">{{ $cost['batch_code'] }}</td>
                                        <td class="px-4 py-3 font-medium">{{ $cost['party'] }}</td>
                                        <td class="px-4 py-3 text-right mono">TZS {{ number_format($cost['total_cost']) }}</td>
                                        <td class="px-4 py-3 text-right mono">TZS {{ number_format($cost['revenue']) }}</td>
                                        <td class="px-4 py-3 text-right mono">TZS {{ number_format($cost['margin']) }}</td>
                                        <td class="px-4 py-3 text-right mono">TZS {{ number_format($cost['cost_per_chick']) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-3 text-center text-slate-500">No costing data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </main>

    {{-- Modals to build alongside this page --}}
    {{--
    @include('production.modals.add-flock')
    @include('production.modals.add-egg-collection')
    @include('production.modals.add-batch')
    @include('production.modals.register-hatch')
    @include('production.modals.allocate-chicks')
    --}}

    @include('components.modal')
    @include('components.alert')
    @include('components.confirm')

    <script>
        const phTabTitles = {
            flocks: 'Breeder Flocks',
            eggs: 'Egg Collection',
            batches: 'Incubation Batches',
            hatch: 'Hatch Registration',
            allocation: 'Chick Allocation',
            costing: 'Batch Costing'
        };

        function switchPhTab(tab, btn) {
            document.querySelectorAll('.ph-tab-panel').forEach(panel => panel.classList.add('hidden'));
            document.getElementById(`ph-tab-${tab}`).classList.remove('hidden');

            document.querySelectorAll('.ph-tab-btn').forEach(b => {
                b.classList.remove('text-slate-700', 'border-b-2', 'border-brand-600');
                b.classList.add('text-slate-500');
            });
            btn.classList.remove('text-slate-500');
            btn.classList.add('text-slate-700', 'border-b-2', 'border-brand-600');

            document.getElementById('phSectionTitle').textContent = phTabTitles[tab];
        }

        function togglePhDetails(id) {
            const detailsRow = document.getElementById(`ph-details-${id}`);
            if (detailsRow) {
                detailsRow.classList.toggle('hidden');
            }
        }
    </script>

</body>

</html>