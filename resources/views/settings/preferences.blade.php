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