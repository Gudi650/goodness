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
                @include('hatcheryProduction.flocks', ['flocks' => $flocks ?? []])
                @include('hatcheryProduction.eggs', ['eggCollections' => $eggCollections ?? []])
                @include('hatcheryProduction.incubation', ['batches' => $batches ?? []])
                @include('hatcheryProduction.hatchRegistration', ['hatchRegistrations' => $hatchRegistrations ?? []])
                @include('hatcheryProduction.allocation', ['chickAllocations' => $chickAllocations ?? []])
                @include('hatcheryProduction.costings', ['batchCosting' => $batchCosting ?? []])
            </div>
        </div>
    </main>

    <!-- Modals -->
    {{--
    @include('production.modals.add-flock')
    @include('production.modals.add-egg-collection')
    @include('production.modals.add-batch')
    @include('production.modals.register-hatch')
    @include('production.modals.allocate-chicks')
    --}}

    <!-- Shared Components -->
    @include('components.modal')
    @include('components.alert')
    @include('components.confirm')

    <!-- Scripts -->
    @include('hatcheryProduction.scripts')
</body>

</html>
