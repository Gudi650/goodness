<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Account Settings - Goodness ERP</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h1, h2, h3, nav, button {
            font-family: 'Outfit', sans-serif;
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
                            700: '#c88600',
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
        <div class="mb-6">
            <h1 class="text-2xl font-semibold font-display">Account Settings</h1>
            <p class="text-sm text-slate-500">Manage your profile, security, and preferences</p>
        </div>

        {{-- Alert component handles success/error messages from session --}}

        <!-- Tab Navigation -->
        <div class="bg-white border-b border-slate-200 mb-6 rounded-t-lg">
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 px-4 lg:px-6">
                <button onclick="switchSettingsTab('profile', this)"
                    class="settings-tab-btn active py-4 text-sm font-medium text-slate-700 border-b-2 border-brand-600 cursor-pointer hover:text-slate-900 transition-colors">
                    Profile
                </button>
                <button onclick="switchSettingsTab('password', this)"
                    class="settings-tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer transition-colors border-b-2 border-transparent">
                    Password
                </button>
                <button onclick="switchSettingsTab('preferences', this)"
                    class="settings-tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer transition-colors border-b-2 border-transparent">
                    Preferences
                </button>
            </div>
        </div>

        <!-- Tab Contents -->
        <div class="bg-white rounded-b-lg border border-slate-200 overflow-hidden">
            @include('settings.profile')
            @include('settings.password')
            @include('settings.preferences')
        </div>
    </main>

    <script>
        function switchSettingsTab(tab, btnEl) {
            document.querySelectorAll('.settings-tab-content').forEach(t => t.classList.add('hidden'));
            document.querySelectorAll('.settings-tab-btn').forEach(b => {
                b.classList.add('border-transparent', 'text-slate-500');
                b.classList.remove('border-brand-600', 'text-slate-700');
            });
            const content = document.getElementById('tab-' + tab);
            if (content) content.classList.remove('hidden');
            if (btnEl) {
                btnEl.classList.remove('border-transparent');
                btnEl.classList.add('border-brand-600', 'text-slate-700');
                btnEl.classList.remove('text-slate-500');
            }
        }

        function showLoader(event, loaderId) {
            if (event) {
                event.preventDefault();
            }

            const loader = document.getElementById(loaderId);
            const form = event?.currentTarget || event?.target;

            if (loader) {
                loader.classList.remove('hidden');
                loader.classList.add('flex');
            }

            setTimeout(() => {
                if (form) {
                    form.submit();
                }
            }, 75);

            return true;
        }

        function resetProfileForm() {
            const form = document.getElementById('profileForm');
            if (form) {
                form.reset();
                form.name.value = '{{ auth()->user()->name }}';
                form.email.value = '{{ auth()->user()->email }}';
                form.phone_number.value = '{{ auth()->user()->phone_number }}';
            }
        }

        function resetPasswordForm() {
            const form = document.getElementById('passwordForm');
            if (form) form.reset();
        }

        function resetPreferencesForm() {
            const form = document.getElementById('preferencesForm');
            if (form) form.reset();
        }

        document.addEventListener('DOMContentLoaded', () => {
            switchSettingsTab('profile', document.querySelector('.settings-tab-btn'));

            // Show validation errors as alerts
            @if ($errors->any())
                const errorMessages = [
                    @foreach ($errors->all() as $error)
                        '{{ $error }}',
                    @endforeach
                ];
                
                if (window.showAlert && errorMessages.length > 0) {
                    const message = errorMessages.length === 1 
                        ? errorMessages[0] 
                        : `${errorMessages.length} validation errors occurred`;
                    
                    window.showAlert('error', message, {
                        duration: 5000,
                        title: 'Validation Error'
                    });
                }
            @endif
        });
    </script>
    @include('components.modal')
    <x-alert />
    @include('components.confirm')
</body>

</html>
