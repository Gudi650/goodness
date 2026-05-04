<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Companies - Goodness Group</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { brand: {50:'#fff8e5',100:'#fde6a1',500:'#f0b73a',600:'#eaa106',700:'#c88600'} }, fontFamily:{ sans:['Inter','sans-serif'], display:['Outfit','sans-serif'] } } } }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <style>body{font-family:Inter,sans-serif}h1,h2,nav,button{font-family:Outfit,sans-serif}.mono{font-family:ui-monospace,monospace}</style>
</head>
<body class="bg-slate-50 text-slate-800">
    @include('components.topbar')
    @include('components.sidebar')

    <main class="ml-0 lg:ml-64 pt-16 lg:pt-20 px-4 lg:p-6 min-h-screen">
        {{-- Success message after creating a company --}}
        @if (session('success'))
            <div class="mb-4 text-sm text-brand-700 bg-brand-50 border border-brand-200 rounded-md px-3 py-2">
                {{ session('success') }}
            </div>
        @endif

        {{-- Validation errors from the add-company form --}}
        @if ($errors->any())
            <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-md px-3 py-2">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="mb-6"><h1 class="text-2xl font-semibold">Companies</h1><p class="text-sm text-slate-500">Manage your subsidiaries and branches</p></div>
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-4">
            {{-- Search form using server-side filtering (no JS table rendering) --}}
            <form method="GET" action="{{ route('companies') }}" class="w-full sm:flex-1 flex gap-2">
                <input
                    id="search"
                    name="search"
                    type="text"
                    value="{{ $search ?? '' }}"
                    placeholder="Search companies..."
                    class="w-full sm:flex-1 px-4 py-2 border border-slate-200 rounded-md bg-white"
                />
                <button type="submit" class="px-4 py-2 border border-slate-300 rounded-md bg-white text-slate-700 text-sm font-medium hover:bg-slate-50 transition-colors">
                    Search
                </button>
            </form>

            {{-- Opens plain HTML modal below --}}
            <button id="addCompanyBtn" type="button" class="w-full sm:w-auto flex-shrink-0 whitespace-nowrap px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium transition-colors">Add Company</button>
        </div>

        <div class="bg-white border border-slate-200 rounded-lg overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-slate-50"><tr><th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-left">Name</th><th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-left">Country</th><th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-right">Revenue (TZS)</th><th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-center">Status</th></tr></thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    {{-- Render companies directly in HTML using Blade --}}
                    @forelse ($companies as $company)
                        <tr>
                            <td class="px-4 py-3 text-sm">{{ $company->name }}</td>
                            <td class="px-4 py-3 text-sm">{{ $company->country }}</td>
                            <td class="px-4 py-3 text-sm text-right mono">TZS {{ number_format((float) $company->revenue, 0) }}</td>
                            <td class="px-4 py-3 text-sm text-center">
                                <span class="inline-block px-2 py-1 {{ $company->status === 'Active' ? 'bg-brand-50 text-brand-700' : 'bg-slate-50 text-slate-600' }} rounded-md text-xs">
                                    {{ $company->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-sm text-slate-500 text-center">No companies found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

    {{-- Plain HTML modal (not JS-generated HTML) --}}
    <div id="addCompanyModalBackdrop" class="hidden fixed inset-0 bg-slate-900 bg-opacity-40 z-50 flex items-start justify-center pt-20 overflow-y-auto">
        <div class="bg-white rounded-lg shadow-xl border border-slate-200 w-full max-w-lg mx-4 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-slate-800 font-display">Add Company</h2>
                <button id="closeAddCompanyModalBtn" type="button" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Real POST form to store company in DB --}}
            <form id="companyForm" method="POST" action="{{ route('companies.store') }}" class="space-y-4">
                @csrf

                <label for="company_name" class="block text-sm text-slate-600">
                    Company Name
                    <input id="company_name" name="name" value="{{ old('name') }}" class="mt-1 block w-full border border-slate-200 rounded p-2" required />
                </label>

                <label for="company_country" class="block text-sm text-slate-600">
                    Country
                    <input id="company_country" name="country" value="{{ old('country') }}" class="mt-1 block w-full border border-slate-200 rounded p-2" required />
                </label>

                <div class="grid grid-cols-2 gap-2">
                    <label for="company_revenue" class="block text-sm text-slate-600">
                        Revenue (TZS)
                        <input id="company_revenue" name="revenue" type="number" min="0" step="0.01" value="{{ old('revenue', 0) }}" class="mt-1 block w-full border border-slate-200 rounded p-2" required />
                    </label>

                    <label for="company_status" class="block text-sm text-slate-600">
                        Status
                        <select id="company_status" name="status" class="mt-1 block w-full border border-slate-200 rounded p-2" required>
                            <option value="Active" {{ old('status') === 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </label>
                </div>

                <div class="flex gap-3 justify-end mt-6">
                    <button id="cancelAddCompanyBtn" type="button" class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm font-medium transition-colors">
                        Cancel
                    </button>
                    <button id="submitCompanyBtn" type="button" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm font-medium transition-colors">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Simple helpers to open/close the plain HTML modal.
        const addCompanyModalBackdrop = document.getElementById('addCompanyModalBackdrop');
        const addCompanyBtn = document.getElementById('addCompanyBtn');
        const closeAddCompanyModalBtn = document.getElementById('closeAddCompanyModalBtn');
        const cancelAddCompanyBtn = document.getElementById('cancelAddCompanyBtn');
        const submitCompanyBtn = document.getElementById('submitCompanyBtn');
        const companyForm = document.getElementById('companyForm');

        function openAddCompanyModal() {
            addCompanyModalBackdrop.classList.remove('hidden');
        }

        function closeAddCompanyModal() {
            addCompanyModalBackdrop.classList.add('hidden');
        }

        addCompanyBtn.addEventListener('click', openAddCompanyModal);
        closeAddCompanyModalBtn.addEventListener('click', closeAddCompanyModal);
        cancelAddCompanyBtn.addEventListener('click', closeAddCompanyModal);

        // Show the confirm modal before saving the company.
        submitCompanyBtn.addEventListener('click', () => {
            // openConfirm comes from components/confirm.blade.php.
            openConfirm(
                'Save Company',
                'Do you want to register this company in the database?',
                () => companyForm.submit()
            );
        });

        // Allow Enter key to trigger the same confirm flow instead of direct submission.
        companyForm.addEventListener('submit', (event) => {
            event.preventDefault();
            openConfirm(
                'Save Company',
                'Do you want to register this company in the database?',
                () => companyForm.submit()
            );
        });

        // Close modal when user clicks outside the modal card.
        addCompanyModalBackdrop.addEventListener('click', (event) => {
            if (event.target === addCompanyModalBackdrop) {
                closeAddCompanyModal();
            }
        });

        // If validation failed on form submit, reopen modal so user sees errors immediately.
        @if ($errors->any())
            openAddCompanyModal();
        @endif
    </script>

    @include('components.alert')
    @include('components.confirm')
</body>
</html>
