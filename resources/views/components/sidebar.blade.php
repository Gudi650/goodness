<div id="sidebarBackdrop" class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden hidden" onclick="toggleSidebar()">
</div>

<div id="sidebar"
    class="fixed left-0 top-16 lg:top-0 w-64 h-screen bg-white border-r border-slate-200 flex flex-col transition-transform duration-300 z-40 lg:translate-x-0 -translate-x-full overflow-y-auto">
    <!-- Logo Section -->
    <div class="p-4 border-b border-slate-200">
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 rounded-md overflow-hidden border border-brand-100 bg-white flex items-center justify-center">
                <img src="{{ asset('favicon.png') }}" alt="Goodness Group logo" class="w-8 h-8 object-contain" />
            </div>
            <div>
                <p class="font-semibold text-slate-800 font-display text-sm">Goodness Group</p>
                <p class="text-xs text-slate-500">Enterprise</p>
            </div>
        </div>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
        <a href="/dashboard"
            class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 mx-2 transition-colors"
            data-path="/dashboard">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4-4m-4 4l-4-4m4 4v-4m0 0l-4 4m4-4l4-4" />
            </svg>
            Dashboard
        </a>
        <a href="/companies"
            class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900  mx-2 transition-colors"
            data-path="/companies">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
            </svg>
            Companies
        </a>
        <a href="/users"
            class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900  mx-2 transition-colors"
            data-path="/users">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Users
        </a>
        <a href="/finance"
            class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900  mx-2 transition-colors"
            data-path="/finance">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Finance
        </a>
        <a href="/hrm"
            class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900  mx-2 transition-colors"
            data-path="/hrm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 9h10" />
            </svg>
            HRM
        </a>
        <a href="/sales"
            class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900  mx-2 transition-colors"
            data-path="/sales">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Sales
        </a>
        <a href="/inventory"
            class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900  mx-2 transition-colors"
            data-path="/inventory">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4m0-10l8 4m-8-4v10m8-10l-8-4" />
            </svg>
            Inventory
        </a>
        <a href="/far"
            class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900  mx-2 transition-colors"
            data-path="/far">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5h9m-9 4h9m-9 4h9m-9 4h9M5 5h.01M5 9h.01M5 13h.01M5 17h.01" />
            </svg>
            Fixed Asset Register
        </a>
        <a href="/reports"
            class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900  mx-2 transition-colors"
            data-path="/reports">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Reports
        </a>
        <a href="/communication"
            class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900  mx-2 transition-colors"
            data-path="/communication">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.577 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            communication
        </a>
        <a href="/settings"
            class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900  mx-2 transition-colors"
            data-path="/settings">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Settings
        </a>
    </nav>

    <!-- User Section & Logout (sticky and visible) -->
    <div class="p-4 border-t border-slate-200 bg-white sticky bottom-0">
        @php
            // Get the authenticated user's name for display in the sidebar.
$userName = auth()->user()?->name ?? 'User';
// Show the first letter as a simple avatar initial.
$userInitial = strtoupper(substr($userName, 0, 1));
//user roles
$userRole = auth()->user()?->role?->name ?? 'N/A';
        @endphp
        <div class="flex items-center gap-3 mb-4">
            <div
                class="w-8 h-8 bg-brand-600 rounded-full flex items-center justify-center text-white font-bold text-xs font-display">
                {{ $userInitial }}</div>
            <div>
                <p class="text-sm font-medium text-slate-800">{{ $userName }}</p>
                <p class="text-xs text-slate-500">{{ $userRole }}</p>
            </div>
        </div>

        <!-- Submit a real logout POST request to invalidate the session securely. -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full text-sm text-red-700 bg-red-50 flex items-center gap-2 px-3 py-2 rounded-md hover:bg-red-100 transition-colors border-t border-slate-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
            </button>
        </form>
    </div>
</div>

<script>
    function setActiveNavLink() {
        const path = window.location.pathname;
        document.querySelectorAll('.nav-link').forEach(link => {
            const linkPath = link.getAttribute('data-path');
            if (path === linkPath || path.startsWith(linkPath + '/')) {
                link.classList.remove('text-slate-600', 'hover:text-slate-900');
                link.classList.add('bg-brand-50', 'text-brand-700', 'border-l-4', 'border-brand-600');
            } else {
                link.classList.remove('bg-brand-50', 'text-brand-700', 'border-l-4', 'border-brand-600');
                link.classList.add('text-slate-600');
            }
        });
    }

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        const isHidden = sidebar.classList.toggle('-translate-x-full');
        if (backdrop) {
            if (isHidden) {
                backdrop.classList.add('hidden');
            } else {
                backdrop.classList.remove('hidden');
            }
        }
    }

    document.addEventListener('DOMContentLoaded', setActiveNavLink);
</script>
