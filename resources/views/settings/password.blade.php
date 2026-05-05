<!-- Password Tab -->
<div id="tab-password" class="settings-tab-content hidden p-6 lg:p-8">
    <div>
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