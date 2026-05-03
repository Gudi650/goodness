<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Users - Goodness Group</title>
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
    </style>
</head>

<body class="bg-slate-50 text-slate-800">
    @include('components.topbar')
    @include('components.sidebar')

    <main class="ml-0 lg:ml-64 pt-16 lg:pt-20 px-4 lg:p-6 min-h-screen">
        {{-- Success message after updating a user's role --}}
        @if (session('success'))
            <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-md px-3 py-2">
                {{ session('success') }}
            </div>
        @endif

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-md px-3 py-2">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Users & Roles</h1>
            <p class="text-sm text-slate-500">Manage user accounts and assign roles/positions</p>
        </div>

        {{-- Search form using server-side filtering --}}
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-4">
            <form method="GET" action="{{ route('users') }}" class="w-full sm:flex-1 flex gap-2">
                <input
                    id="search"
                    name="search"
                    type="text"
                    value="{{ $search ?? '' }}"
                    placeholder="Search users by name or email..."
                    class="w-full sm:flex-1 px-4 py-2 border border-slate-200 rounded-md bg-white"
                />
                <button type="submit" class="px-4 py-2 border border-slate-300 rounded-md bg-white text-slate-700 text-sm font-medium hover:bg-slate-50 transition-colors">
                    Search
                </button>
            </form>
        </div>

        {{-- Users table rendered from database --}}
        <div class="bg-white border border-slate-200 rounded-lg overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Name</th>
                        <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Email</th>
                        <th class="text-xs text-slate-500 uppercase px-4 py-3 text-center">Current Role</th>
                        <th class="text-xs text-slate-500 uppercase px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    {{-- Render users directly from database using Blade --}}
                    @forelse ($users as $user)
                        <tr>
                            <td class="px-4 py-3 text-sm">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-sm">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-sm text-center">
                                <span class="inline-block px-2 py-1 bg-slate-50 text-slate-700 rounded-md text-xs">
                                    {{ $user->role?->name ?? 'No Role' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                {{-- Button to open the assign role modal --}}
                                <button
                                    type="button"
                                    onclick="openAssignRoleModal({{ $user->id }}, '{{ $user->name }}')"
                                    class="px-3 py-1 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-xs font-medium transition-colors"
                                >
                                    Change Role
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-sm text-slate-500 text-center">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

    {{-- Plain HTML modal for assigning roles --}}
    <div id="assignRoleModalBackdrop" class="hidden fixed inset-0 bg-slate-900 bg-opacity-40 z-50 flex items-start justify-center pt-20 overflow-y-auto">
        <div class="bg-white rounded-lg shadow-xl border border-slate-200 w-full max-w-lg mx-4 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-slate-800 font-display">Assign Role</h2>
                <button id="closeAssignRoleModalBtn" type="button" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Form to update user role --}}
            <form id="assignRoleForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="selected_user_name" class="block text-sm text-slate-600 mb-1">
                        User
                    </label>
                    <input id="selected_user_name" type="text" class="w-full px-4 py-2 border border-slate-200 rounded-md bg-slate-50 text-slate-600" readonly />
                </div>

                <div>
                    <label for="role_id" class="block text-sm text-slate-600 mb-1">
                        Select New Role
                    </label>
                    <select id="role_id" name="role_id" class="w-full px-4 py-2 border border-slate-200 rounded-md bg-white text-slate-800" required>
                        <option value="">-- Choose a role --</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }} - {{ $role->description }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-3 justify-end mt-6">
                    <button id="cancelAssignRoleBtn" type="button" class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm font-medium transition-colors">
                        Cancel
                    </button>
                    <button id="submitAssignRoleBtn" type="button" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm font-medium transition-colors">
                        Assign Role
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal management
        const assignRoleModalBackdrop = document.getElementById('assignRoleModalBackdrop');
        const closeAssignRoleModalBtn = document.getElementById('closeAssignRoleModalBtn');
        const cancelAssignRoleBtn = document.getElementById('cancelAssignRoleBtn');
        const submitAssignRoleBtn = document.getElementById('submitAssignRoleBtn');
        const assignRoleForm = document.getElementById('assignRoleForm');
        const selectedUserNameInput = document.getElementById('selected_user_name');
        const roleIdInput = document.getElementById('role_id');

        let currentUserId = null;

        function openAssignRoleModal(userId, userName) {
            currentUserId = userId;
            selectedUserNameInput.value = userName;
            roleIdInput.value = '';
            assignRoleModalBackdrop.classList.remove('hidden');
        }

        function closeAssignRoleModal() {
            assignRoleModalBackdrop.classList.add('hidden');
            currentUserId = null;
        }

        closeAssignRoleModalBtn.addEventListener('click', closeAssignRoleModal);
        cancelAssignRoleBtn.addEventListener('click', closeAssignRoleModal);

        // When user clicks submit, show a confirm dialog first
        submitAssignRoleBtn.addEventListener('click', () => {
            if (!roleIdInput.value) {
                alert('Please select a role');
                return;
            }

            // Show the confirm modal
            openConfirm(
                'Confirm Role Assignment',
                `Do you want to assign this role to ${selectedUserNameInput.value}?`,
                () => {
                    // After confirming, update the form action and submit
                    assignRoleForm.action = `/users/${currentUserId}/role`;
                    assignRoleForm.submit();
                }
            );
        });

        // Close modal when clicking outside
        assignRoleModalBackdrop.addEventListener('click', (event) => {
            if (event.target === assignRoleModalBackdrop) {
                closeAssignRoleModal();
            }
        });
    </script>

    @include('components.alert')
    @include('components.confirm')
</body>

</html>
