<div id="rolesPane" class="hidden">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-lg font-semibold">Available Roles</h2>
            <p class="text-sm text-slate-500">List of roles that can be assigned to users</p>
        </div>
        <div>
            <button id="openCreateRoleBtnPane" type="button" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-medium">
                Create Role
            </button>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Role</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Description</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($roles as $role)
                    <tr>
                        <td class="px-4 py-3 text-sm">{{ $role->name }}</td>
                        <td class="px-4 py-3 text-sm">{{ $role->description ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-4 py-6 text-sm text-slate-500 text-center">No roles found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
