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
