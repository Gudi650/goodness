<div id="usersPane">
    {{-- Search form using server-side filtering --}}
    <div class="flex flex-col lg:flex-row lg:items-center gap-3 mb-4">
        <form method="GET" action="{{ route('users') }}" class="w-full lg:flex-1 flex gap-2">
            <input
                id="search"
                name="search"
                type="text"
                value="{{ $search ?? '' }}"
                placeholder="Search users by name or email..."
                class="w-full lg:flex-1 px-4 py-2 border border-slate-200 rounded-md bg-white"
            />
            <button type="submit" class="px-4 py-2 border border-slate-300 rounded-md bg-white text-slate-700 text-sm font-medium hover:bg-slate-50 transition-colors">
                Search
            </button>
        </form>
    </div>

    {{-- Users table rendered from database --}}
    <div class="bg-white border border-slate-200 rounded-lg overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Name</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Email</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-center">Current Role</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-center">Company</th>
                    <th class="text-xs text-slate-500 uppercase px-4 py-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                {{-- Render users directly from database using Blade --}}
                @forelse ($users as $user)
                    <tr>
                        <td class="px-4 py-3 text-sm">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-sm">{{ $user->email }}</td>
                        <td class="px-4 py-3 text-sm text-center">
                            <span class="inline-block px-2 py-1 bg-slate-50 text-slate-700 rounded-md text-xs">
                                {{ $user->role?->name ?? 'No Role' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <button
                                type="button"
                                onclick="openAssignCompanyModal({{ $user->id }}, '{{ $user->name }}')"
                                class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-xs font-medium transition-colors"
                            >
                                {{ $user->company?->name ?? 'Assign' }}
                            </button>
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            {{-- Button to open the assign role modal --}}
                            <button
                                type="button"
                                onclick="openAssignRoleModal({{ $user->id }}, '{{ $user->name }}')"
                                class="px-3 py-1 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-xs font-medium transition-colors"
                            >
                                Change Role
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-sm text-slate-500 text-center">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
