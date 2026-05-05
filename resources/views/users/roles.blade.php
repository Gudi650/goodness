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
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($roles as $role)
                    <tr>
                        <td class="px-4 py-3 text-sm">{{ $role->name }}</td>
                        <td class="px-4 py-3 text-sm">{{ $role->description ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-center">
                            <div class="inline-flex items-center gap-2">
                                <button
                                    type="button"
                                    onclick="openEditRoleModal({{ $role->id }}, @js($role->name), @js($role->description ?? ''))"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors"
                                    aria-label="Edit role"
                                    title="Edit"
                                >
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l2.651 2.651M5 19l4.5-1L19 8.5a1.5 1.5 0 0 0 0-2.121l-1.379-1.379a1.5 1.5 0 0 0-2.121 0L6 14.5 5 19z" />
                                    </svg>
                                </button>

                                <form id="delete-role-form-{{ $role->id }}" method="POST" action="{{ route('roles.destroy', $role) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="button"
                                        onclick="openConfirm({ title: 'Delete Role', message: @js("Are you sure you want to delete {$role->name}?"), confirmText: 'Delete', cancelText: 'Cancel', variant: 'warning', onConfirm: () => { document.getElementById('deleteRoleLoader').classList.remove('hidden'); setTimeout(() => document.getElementById('delete-role-form-{{ $role->id }}').submit(), 75); } })"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-red-200 text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors"
                                        aria-label="Delete role"
                                        title="Delete"
                                    >
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2m2 0-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-sm text-slate-500 text-center">No roles found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-loading id="deleteRoleLoader" class="hidden" full-page="true" />
</div>
