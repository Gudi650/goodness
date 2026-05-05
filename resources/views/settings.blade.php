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
            <!-- Profile Tab -->
            <div id="tab-profile" class="settings-tab-content p-6 lg:p-8">
                <h2 class="text-lg font-semibold mb-6 font-display">Profile Information</h2>
                <form id="profileForm" method="POST" action="{{ route('settings.update.profile') }}" onsubmit="return showLoader(event, 'profileLoader')" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Full Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required
                                class="w-full border border-slate-300 rounded-md px-4 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition" />
                            @error('name')<span class="text-xs text-red-600 mt-1">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                                class="w-full border border-slate-300 rounded-md px-4 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition" />
                            @error('email')<span class="text-xs text-red-600 mt-1">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-slate-700 mb-2">Phone Number</label>
                            <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number', auth()->user()->phone_number) }}"
                                class="w-full border border-slate-300 rounded-md px-4 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition" />
                            @error('phone_number')<span class="text-xs text-red-600 mt-1">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label for="company" class="block text-sm font-medium text-slate-700 mb-2">Company</label>
                            <input type="text" id="company" disabled
                                value="{{ auth()->user()->company?->name ?? 'Not assigned' }}"
                                class="w-full border border-slate-300 rounded-md px-4 py-2 text-sm bg-slate-50 text-slate-600" />
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200 flex gap-3 justify-end">
                        <button type="button" onclick="resetProfileForm()"
                            class="px-6 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm font-medium transition-colors">
                            Cancel
                        </button>
                        <button type="submit" id="profileSubmitBtn"
                            class="px-6 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium transition-colors">
                            Save Changes
                        </button>
                    </div>
                </form>
                <x-loading id="profileLoader" size="lg" color="amber" full-page="true" message="Updating profile..." :show="false" />
            </div>

            <!-- Password Tab -->
            <div id="tab-password" class="settings-tab-content hidden p-6 lg:p-8">
                <div class="">
                    <h2 class="text-lg font-semibold mb-6 font-display">Change Password</h2>
                    <form id="passwordForm" method="POST" action="{{ route('settings.update.password') }}" onsubmit="return showLoader(event, 'passwordLoader')" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="current_password" class="block text-sm font-medium text-slate-700 mb-2">Current Password</label>
                            <input type="password" id="current_password" name="current_password" required
                                class="w-full border border-slate-300 rounded-md px-4 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition" />
                            @error('current_password')<span class="text-xs text-red-600 mt-1">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 mb-2">New Password</label>
                            <input type="password" id="password" name="password" required
                                class="w-full border border-slate-300 rounded-md px-4 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition" />
                            <div id="passwordStrength" class="mt-2 text-xs text-slate-600">
                                Password requirements: At least 8 characters, mix of uppercase, lowercase, numbers, and special characters
                            </div>
                            @error('password')<span class="text-xs text-red-600 mt-1">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-2">Confirm New Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="w-full border border-slate-300 rounded-md px-4 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition" />
                            @error('password_confirmation')<span class="text-xs text-red-600 mt-1">{{ $message }}</span>@enderror
                        </div>

                        <div class="pt-4 border-t border-slate-200 flex gap-3 justify-end">
                            <button type="button" onclick="resetPasswordForm()"
                                class="px-6 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm font-medium transition-colors">
                                Cancel
                            </button>
                            <button type="submit" id="passwordSubmitBtn"
                                class="px-6 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium transition-colors">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
                <x-loading id="passwordLoader" size="lg" color="amber" full-page="true" message="Updating password..." :show="false" />
            </div>

            <!-- Preferences Tab -->
            <div id="tab-preferences" class="settings-tab-content hidden p-6 lg:p-8">
                <div class="max-w-md">
                    <h2 class="text-lg font-semibold mb-6 font-display">Preferences</h2>
                    <form id="preferencesForm" method="POST" action="{{ route('settings.update.preferences') }}" onsubmit="return showLoader(event, 'preferencesLoader')" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="email_notifications" value="1" 
                                    {{ old('email_notifications', true) ? 'checked' : '' }}
                                    class="w-4 h-4 border-slate-300 rounded text-brand-600 focus:ring-brand-600 transition" />
                                <span class="text-sm font-medium text-slate-700">Receive email notifications</span>
                            </label>
                            <p class="text-xs text-slate-500 mt-1 ml-7">Get notified about important updates and activities</p>
                        </div>

                        <div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="weekly_digest" value="1" 
                                    {{ old('weekly_digest', false) ? 'checked' : '' }}
                                    class="w-4 h-4 border-slate-300 rounded text-brand-600 focus:ring-brand-600 transition" />
                                <span class="text-sm font-medium text-slate-700">Receive weekly digest</span>
                            </label>
                            <p class="text-xs text-slate-500 mt-1 ml-7">Get a summary of weekly activities every Monday</p>
                        </div>

                        <div class="border-t border-slate-200 pt-6">
                            <h3 class="text-sm font-medium text-slate-700 mb-3">Language & Localization</h3>
                            <select name="language"
                                class="w-full border border-slate-300 rounded-md px-4 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition">
                                <option value="en" {{ old('language', 'en') === 'en' ? 'selected' : '' }}>English</option>
                                <option value="es" {{ old('language') === 'es' ? 'selected' : '' }}>Español</option>
                                <option value="fr" {{ old('language') === 'fr' ? 'selected' : '' }}>Français</option>
                            </select>
                        </div>

                        <div class="pt-4 border-t border-slate-200 flex gap-3 justify-end">
                            <button type="button" onclick="resetPreferencesForm()"
                                class="px-6 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm font-medium transition-colors">
                                Cancel
                            </button>
                            <button type="submit" id="preferencesSubmitBtn"
                                class="px-6 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium transition-colors">
                                Save Preferences
                            </button>
                        </div>
                    </form>
                </div>
                <x-loading id="preferencesLoader" size="lg" color="amber" full-page="true" message="Updating preferences..." :show="false" />
            </div>
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
            const form = event.target;

            if (loader) {
                loader.classList.remove('hidden');
                loader.classList.add('flex');
            }

            setTimeout(() => {
                form.submit();
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
