<div id="accountsPane" class="hidden">

    <div class="overflow-x-auto bg-white ">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-4 py-2 text-left">Account Name</th>
                    <th class="px-4 py-2 text-left">Account Number</th>
                    <th class="px-4 py-2 text-left">Company</th>
                    <th class="px-4 py-2 text-left">Currency</th>
                    <th class="px-4 py-2 text-left">Balance</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                {{-- display the dynamic data here --}}
                @forelse ($virtualAccounts as $account)
                    <tr class="cursor-pointer" >
                        <td class="px-4 py-2">{{ $account['bank_name'] }}</td>
                        <td class="px-4 py-2">{{ $account['account_number'] }}</td>
                        <td class="px-4 py-2">{{ $account['company_name'] }}</td>
                        <td class="px-4 py-2">{{ $account['currency'] }}</td>
                        <td class="px-4 py-2 font-semibold">
                            {{ number_format($account['balance'], 2) }}
                        </td>
                        <td class="px-4 py-2">
                            <span class="{{ $account['status'] === 'active' ? 'text-green-600' : 'text-red-600' }}">
                                {{ ucfirst($account['status']) }}
                            </span>
                        </td>

                        <td>
                            <div class="inline-flex items-center gap-6">

                                <button type="button"
                                    onclick="toggleDetails('acc{{ $account['id'] }}')"
                                    onclick="event.stopPropagation(); toggleDetails('acc{{ $account['id'] }}')"
                                    class="text-slate-600 hover:text-slate-800 transition-colors" title="Show details">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>

                                {{-- 
                                    <button type="button" class="text-blue-600 hover:text-blue-800 transition-colors"
                                        title="Edit Account">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a2.25 2.25 0 1 1 3.182 3.182L10.582 17.13a4.5 4.5 0 0 1-1.897 1.13L6 19l.74-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487ZM16.862 4.487 19.5 7.125" />
                                        </svg>
                                    </button>
                                    
                                 --}}

                            </div>
                        </td>
                    </tr>

                    <tr id="acc{{ $account['id'] }}" class="hidden bg-slate-50">
                        <td colspan="9" class="px-4 py-4">
                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">

                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Account Number
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ $account['account_number'] }}
                                    </p>
                                </div>

                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Card Number
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ $account['card_number'] }}
                                    </p>
                                </div>

                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Remaining Balance
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ number_format($account['balance'], 2) }}
                                        {{ $account['currency'] }}
                                    </p>
                                </div>

                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Description
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ $account['description'] }}
                                    </p>
                                </div>

                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Account Name
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ $account['account_name'] }}
                                    </p>
                                </div>

                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Account Type
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ ucfirst($account['account_type']) }}
                                    </p>
                                </div>

                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Status
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ ucfirst($account['status']) }}
                                    </p>
                                </div>

                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Created At
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ $account['created_at'] }}
                                    </p>
                                </div>

                            </div>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="9" class="px-4 py-6 text-sm text-slate-500 text-center">
                            No Bank Accounts yet.
                        </td>
                    </tr>
                @endforelse




            </tbody>
        </table>
    </div>


    <script>
        function toggleDetails(id) {
            const row = document.getElementById(id);
            row.classList.toggle('hidden');
        }
    </script>


</div>
