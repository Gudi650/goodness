<div id="createRoleModalBackdrop" class="hidden fixed inset-0 bg-slate-900 bg-opacity-40 z-50 flex items-start justify-center pt-20 overflow-y-auto">
    <div class="bg-white rounded-lg shadow-xl border border-slate-200 w-full max-w-lg mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-slate-800 font-display">Create New Role</h2>
            <button id="closeCreateRoleModalBtn" type="button" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Form to create a role --}}
        <form id="createRoleForm" method="POST" action="{{ route('roles.store') }}" class="space-y-4" onsubmit="return showLoader(event, 'createRoleLoader')">
            @csrf

            <div>
                <label for="role_template" class="block text-sm text-slate-600 mb-1">Choose Role</label>
                <select id="role_template" class="w-full px-4 py-2 border border-slate-200 rounded-md bg-white">
                    <option value="">-- Select common role or choose Other --</option>
                    <option value="CEO">CEO</option>
                    <option value="Admin">Admin</option>
                    <option value="Accountant">Accountant</option>
                    <option value="HR Manager">HR Manager</option>
                </select>
            </div>

            <div class="mt-3">
                <label for="role_name" class="block text-sm text-slate-600 mb-1">Role Name</label>
                <input id="role_name" name="name" type="text" required class="w-full px-4 py-2 border border-slate-200 rounded-md bg-white" placeholder="e.g. Manager" />
                <p id="role_name_help" class="text-xs text-slate-500 mt-1 hidden">Choose "Other" above to enter a custom role name.</p>
            </div>

            <div>
                <label for="role_description" class="block text-sm text-slate-600 mb-1">Description (optional)</label>
                <textarea id="role_description" name="description" rows="3" class="w-full px-4 py-2 border border-slate-200 rounded-md bg-white" placeholder="Short description for this role"></textarea>
            </div>

            <div class="flex gap-3 justify-end mt-6">
                <button id="cancelCreateRoleBtn" type="button" class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm font-medium transition-colors">Cancel</button>
                <button id="submitCreateRoleBtn" type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-medium">Create Role</button>
            </div>
        </form>
    </div>
</div>

<script>
    (function(){
        var template = document.getElementById('role_template');
        var nameInput = document.getElementById('role_name');
        var help = document.getElementById('role_name_help');
        function update(){
            if(!template) return;
            var v = template.value;
            if(!v){
                nameInput.value = '';
                nameInput.readOnly = false;
                help.classList.remove('hidden');
            } else if(v === 'other'){
                nameInput.value = '';
                nameInput.readOnly = false;
                nameInput.focus();
                help.classList.add('hidden');
            } else {
                nameInput.value = v;
                nameInput.readOnly = true;
                help.classList.add('hidden');
            }
        }
        if(template) template.addEventListener('change', update);
        // initialize
        if(document.readyState === 'loading'){
            document.addEventListener('DOMContentLoaded', update);
        } else {
            update();
        }
    })();
</script>

<x-loading id="createRoleLoader" message="Creating role..." :show="false" fullPage />
    </div>
</div>
