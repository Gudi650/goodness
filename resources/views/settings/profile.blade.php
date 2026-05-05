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