<div id="topbar"
    class="fixed top-0 left-0 right-0 lg:left-64 h-16 bg-white border-b border-slate-200 z-50 flex items-center justify-between px-4 lg:px-6 transition-all">
    <!-- Left Side -->
    <div class="flex items-center gap-2 lg:gap-4 flex-1 min-w-0">
        <button onclick="toggleSidebar()" class="lg:hidden p-2 hover:bg-slate-100 rounded-md text-slate-600 flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <h1 id="pageTitle" class="text-sm lg:text-base font-semibold text-slate-800 font-display truncate">Dashboard</h1>
    </div>

    <!-- Right Side -->
    <div class="flex items-center gap-2 lg:gap-4 flex-shrink-0">
        <!-- Company Selector (visible on all screens) -->
        <select
            class="block border border-slate-300 rounded-md text-xs lg:text-sm px-2 lg:px-3 py-1.5 text-slate-700 bg-white focus:ring-2 focus:ring-green-500 focus:outline-none max-w-xs truncate">
            {{-- Load company names directly from the database so the selector stays dynamic. --}}
            @php
                $companyOptions = \App\Models\Company::orderBy('name')->get();
            @endphp

            @forelse ($companyOptions as $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
            @empty
                <option disabled selected>No companies available</option>
            @endforelse
        </select>

        <!-- Notification Bell -->
        <div class="relative hidden sm:block">
            <button class="p-2 hover:bg-slate-100 rounded-md text-slate-500 hover:text-slate-800 transition-colors flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0018 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </button>
            <span class="absolute top-1 right-1 w-2 h-2 bg-green-500 rounded-full"></span>
        </div>

        <!-- User Info -->
        <div class="flex items-center gap-2 lg:gap-3 pl-2 lg:pl-4 border-l border-slate-200">
            <div class="text-right hidden lg:block">
                {{-- Display the authenticated user's name --}}
                <p class="text-sm font-medium text-slate-700">{{ auth()->user()?->name ?? 'User' }}</p>
                <p class="text-xs px-2 py-0.5 rounded bg-green-100 text-green-700 font-medium">Admin</p>
            </div>

            {{-- Logout Form - submits POST request to /logout endpoint --}}
            <form action="{{ route('logout') }}" method="POST" class="flex items-center">
                {{-- CSRF Token - required by Laravel for security --}}
                @csrf
                
                {{-- Logout Button - styled as a link but submits the form --}}
                <button 
                    type="submit"
                    class="flex items-center gap-1 lg:gap-1.5 text-xs lg:text-sm text-slate-500 hover:text-red-600 transition-colors pl-2 lg:pl-4 border-l border-slate-200 flex-shrink-0"
                    title="Log out of your account"
                >
                    {{-- Logout icon --}}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="hidden lg:inline">Logout</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    /**
     * Update page title based on current URL
     * This helps users know which page they're viewing
     */
    function updatePageTitle() {
        const titles = {
            '/dashboard': 'Dashboard',
            '/companies': 'Companies',
            '/users': 'Users',
            '/finance': 'Finance',
            '/hrm': 'Human Resources',
            '/sales': 'Sales & CRM',
            '/inventory': 'Inventory',
            '/reports': 'Reports & Analytics',
            '/login': 'Login'
        };
        const path = window.location.pathname;
        document.getElementById('pageTitle').textContent = titles[path] || 'Dashboard';
    }

    // Run when page loads to set the correct title
    document.addEventListener('DOMContentLoaded', updatePageTitle);
</script>
