<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Users - Goodness Group</title>
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
            <div class="mb-4 text-sm text-brand-700 bg-brand-50 border border-brand-200 rounded-md px-3 py-2">
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

        {{-- Navigation tabs: Users / Roles (styled like the screenshot with underline indicator) --}}
        <div class="mb-4">
            <nav class="border-b border-slate-200">
                <ul class="flex space-x-6 -mb-px">
                    <li>
                        <button id="tabUsersBtn" class="pb-3 text-sm font-medium text-slate-900 border-b-2 border-brand-500">Users</button>
                    </li>
                    <li>
                        <button id="tabRolesBtn" class="pb-3 text-sm font-medium text-slate-600 hover:text-slate-900 border-b-2 border-transparent">Roles</button>
                    </li>
                </ul>
            </nav>
        </div>

        {{-- Include the tab contents as partials --}}
        @include('users.list')
        @include('users.roles')

        {{-- (Users and Roles panes live in the included partials) --}}
    </main>

    {{-- Modals moved to partials --}}
    @include('users.modals.assign-role')
    @include('users.modals.create-role')
    @include('users.modals.assign-company')

    {{-- Centralized scripts for users page --}}
    @include('users-scripts')

    @include('components.alert')
    @include('components.confirm')
</body>

</html>
