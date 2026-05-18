
<div id="chatPane" class="flex max-h-[calc(100vh-16rem)] min-h-[20rem] flex-col bg-slate-50/60">

    <article data-thread-panel="internal-0" class="thread-panel flex h-full flex-col">
        <div class="flex items-center justify-between gap-3 border-b border-slate-200 p-4 bg-white">
            <div class="flex min-w-0 items-center gap-3">

                <button type="button" class="back-to-list-btn hidden h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition-colors hover:bg-slate-50 lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </button>

                <div class="min-w-0">
                    @php
                        $convUsers = $users ?? collect();
                        $selectedUser = $convUsers->firstWhere('id', $selectedThread ?? null);
                        
                    @endphp
                    <div class="flex items-center gap-2">
                        <h2 class="truncate text-lg font-semibold text-slate-900">
                            @if($selectedUser)
                                {{ $selectedUser->name }}
                            @else
                                Select a conversation
                            @endif
                        </h2>
                        <span class="rounded-full bg-brand-50 px-2 py-0.5 text-[11px] font-semibold text-brand-700">Internal</span>
                    </div>
                    <p class="mt-0.5 text-sm text-slate-500">{{ isset($messages) ? $messages->count() : 0 }} messages</p>
                </div>

            </div>

            {{-- right side of the tabs containing action keys--}}
            <div class="flex items-center gap-2">

                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition-colors hover:bg-slate-50">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </button>

                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition-colors hover:bg-slate-50">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5.25c-4.97 0-9 3.43-9 7.65 0 2.53 1.42 4.8 3.68 6.24L6 21l2.28-1.54c1.11.29 2.29.44 3.72.44 4.97 0 9-3.43 9-7.65s-4.03-7.5-9-7.5Z" />
                    </svg>
                </button>

                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition-colors hover:bg-slate-50">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm0 6a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm0 6a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- messaging view section is here --}}
        <div class="flex flex-1 flex-col bg-slate-50/60">

            {{-- Dynamic messages loop --}}
            <div class="flex-1 space-y-4 overflow-y-auto p-4 scrollbar-hide">
                @forelse($messages ?? [] as $msg)
                    @if($msg->sender_id === Auth::id())
                        {{-- sender text (YOU) --}}
                        <div class="flex justify-end">
                            <div class="max-w-[82%] rounded-2xl rounded-br-md bg-brand-600 px-4 py-3 text-sm text-white shadow-sm">
                                <div class="mb-1 flex items-center justify-between gap-4 text-[11px] text-brand-100">
                                    <span class="font-semibold">You</span>
                                    <span class="mono">{{ $msg->created_at->format('H:i') }}</span>
                                </div>
                                <p class="leading-6">{{ $msg->message }}</p>
                            </div>
                        </div>
                    @else
                        {{-- receiver text --}}
                        <div class="flex justify-start">
                            <div class="max-w-[82%] rounded-2xl rounded-bl-md border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm">
                                <div class="mb-1 flex items-center justify-between gap-4 text-[11px] text-slate-400">
                                    <span class="font-semibold">{{ $msg->sender->name ?? 'Unknown' }}</span>
                                    <span class="mono">{{ $msg->created_at->format('H:i') }}</span>
                                </div>
                                <p class="leading-6">{{ $msg->message }}</p>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="text-center text-slate-500 py-8">
                        <p class="text-sm">No messages yet. Start the conversation!</p>
                    </div>
                @endforelse
            </div>

            {{-- footer section --}}
            <div class="border-t border-slate-200 bg-white p-4">
                <div class="mb-3 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-600">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Online now
                    </span>
                    <span class="rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-600">Encryption enabled</span>
                </div>

                <div class="flex items-end gap-3 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
                    {{-- sending the message section here --}}
                    <form action="{{ route('messages.store', $selectedThread ?? Auth::id()) }}" method="POST" enctype="multipart/form-data" class="flex items-end gap-3 w-full">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $selectedThread ?? Auth::id() }}">

                        <button type="button" class="inline-flex h-10 w-10 flex-none items-center justify-center rounded-xl bg-slate-100 text-slate-600 transition-colors hover:bg-slate-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5v6m3-3H9m6.75 4.5a9 9 0 1 0-13.5 0l1.98-1.98a6.2 6.2 0 0 1 8.76 0l1.98 1.98Z" />
                            </svg>
                        </button>

                        <textarea name="message" rows="2" placeholder="Write a message" class="max-h-32 flex-1 resize-none border-0 bg-transparent px-1 py-2 text-sm text-slate-700 outline-none placeholder:text-slate-400" required></textarea>

                        <button type="submit" class="inline-flex h-11 flex-none items-center gap-2 rounded-xl bg-brand-600 px-4 text-sm font-semibold text-white transition-colors hover:bg-brand-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m6.75 12 4.5 4.5 6-12" />
                            </svg>
                            Send
                        </button>

                    </form>
                </div>
            </div>

        </div>
    </article>

    {{-- 
    <article data-thread-panel="internal-1" class="thread-panel hidden flex h-full flex-col">
        <div class="flex items-center justify-between gap-3 border-b border-slate-200 p-4 bg-white">
            <div class="flex min-w-0 items-center gap-3">
                <button type="button" class="back-to-list-btn hidden h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition-colors hover:bg-slate-50 lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </button>
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <h2 class="truncate text-lg font-semibold text-slate-900">IT Support</h2>
                        <span class="rounded-full bg-brand-50 px-2 py-0.5 text-[11px] font-semibold text-brand-700">Internal</span>
                    </div>
                    <p class="mt-0.5 text-sm text-slate-500">1 agent active, 9 messages today</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition-colors hover:bg-slate-50"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg></button>
                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition-colors hover:bg-slate-50"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5.25c-4.97 0-9 3.43-9 7.65 0 2.53 1.42 4.8 3.68 6.24L6 21l2.28-1.54c1.11.29 2.29.44 3.72.44 4.97 0 9-3.43 9-7.65s-4.03-7.5-9-7.5Z" /></svg></button>
                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition-colors hover:bg-slate-50"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm0 6a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm0 6a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" /></svg></button>
            </div>
        </div>
        <div class="flex flex-1 flex-col bg-slate-50/60">
            <div class="flex-1 space-y-4 overflow-y-auto p-4 scrollbar-hide">
                <div class="flex justify-start"><div class="max-w-[82%] rounded-2xl rounded-bl-md border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm"><div class="mb-1 flex items-center justify-between gap-4 text-[11px] text-slate-400"><span class="font-semibold">Paul</span><span class="mono">09:10</span></div><p class="leading-6">The ERP login issue on reception is fixed.</p></div></div>
                <div class="flex justify-end"><div class="max-w-[82%] rounded-2xl rounded-br-md bg-brand-600 px-4 py-3 text-sm text-white shadow-sm"><div class="mb-1 flex items-center justify-between gap-4 text-[11px] text-brand-100"><span class="font-semibold">You</span><span class="mono">09:13</span></div><p class="leading-6">Good. Please keep monitoring for the next hour.</p></div></div>
                <div class="flex justify-start"><div class="max-w-[82%] rounded-2xl rounded-bl-md border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm"><div class="mb-1 flex items-center justify-between gap-4 text-[11px] text-slate-400"><span class="font-semibold">Paul</span><span class="mono">09:18</span></div><p class="leading-6">Will do. Reception can now access the dashboard normally.</p></div></div>
            </div>
            <div class="border-t border-slate-200 bg-white p-4">
                <div class="mb-3 flex flex-wrap items-center gap-2 text-xs text-slate-500"><span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-600"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>Online now</span><span class="rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-600">Encryption enabled</span></div>
                <div class="flex items-end gap-3 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm"><button type="button" class="inline-flex h-10 w-10 flex-none items-center justify-center rounded-xl bg-slate-100 text-slate-600 transition-colors hover:bg-slate-200"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5v6m3-3H9m6.75 4.5a9 9 0 1 0-13.5 0l1.98-1.98a6.2 6.2 0 0 1 8.76 0l1.98 1.98Z" /></svg></button><textarea rows="2" placeholder="Write a message" class="max-h-32 flex-1 resize-none border-0 bg-transparent px-1 py-2 text-sm text-slate-700 outline-none placeholder:text-slate-400"></textarea><button type="button" class="inline-flex h-11 flex-none items-center gap-2 rounded-xl bg-brand-600 px-4 text-sm font-semibold text-white transition-colors hover:bg-brand-700"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m6.75 12 4.5 4.5 6-12" /></svg>Send</button></div>
            </div>
        </div>
    </article>

    <article data-thread-panel="internal-2" class="thread-panel hidden flex h-full flex-col">
        <div class="flex items-center justify-between gap-3 border-b border-slate-200 p-4 bg-white">
            <div class="flex min-w-0 items-center gap-3">
                <button type="button" class="back-to-list-btn hidden h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition-colors hover:bg-slate-50 lg:hidden"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg></button>
                <div class="min-w-0"><div class="flex items-center gap-2"><h2 class="truncate text-lg font-semibold text-slate-900">Sales Team</h2><span class="rounded-full bg-brand-50 px-2 py-0.5 text-[11px] font-semibold text-brand-700">Internal</span></div><p class="mt-0.5 text-sm text-slate-500">5 people active, 26 messages today</p></div>
            </div>
            <div class="flex items-center gap-2"><button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition-colors hover:bg-slate-50"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg></button><button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition-colors hover:bg-slate-50"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5.25c-4.97 0-9 3.43-9 7.65 0 2.53 1.42 4.8 3.68 6.24L6 21l2.28-1.54c1.11.29 2.29.44 3.72.44 4.97 0 9-3.43 9-7.65s-4.03-7.5-9-7.5Z" /></svg></button><button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition-colors hover:bg-slate-50"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm0 6a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm0 6a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" /></svg></button></div>
        </div>
        <div class="flex flex-1 flex-col bg-slate-50/60">
            <div class="flex-1 space-y-4 overflow-y-auto p-4 scrollbar-hide"><div class="flex justify-start"><div class="max-w-[82%] rounded-2xl rounded-bl-md border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm"><div class="mb-1 flex items-center justify-between gap-4 text-[11px] text-slate-400"><span class="font-semibold">Sarah</span><span class="mono">Yesterday</span></div><p class="leading-6">Customer follow-up list has been updated.</p></div></div><div class="flex justify-end"><div class="max-w-[82%] rounded-2xl rounded-br-md bg-brand-600 px-4 py-3 text-sm text-white shadow-sm"><div class="mb-1 flex items-center justify-between gap-4 text-[11px] text-brand-100"><span class="font-semibold">You</span><span class="mono">Yesterday</span></div><p class="leading-6">Share the new prospect sheet in the sales folder.</p></div></div></div>
            <div class="border-t border-slate-200 bg-white p-4"><div class="mb-3 flex flex-wrap items-center gap-2 text-xs text-slate-500"><span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-600"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>Online now</span><span class="rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-600">Encryption enabled</span></div><div class="flex items-end gap-3 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm"><button type="button" class="inline-flex h-10 w-10 flex-none items-center justify-center rounded-xl bg-slate-100 text-slate-600 transition-colors hover:bg-slate-200"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5v6m3-3H9m6.75 4.5a9 9 0 1 0-13.5 0l1.98-1.98a6.2 6.2 0 0 1 8.76 0l1.98 1.98Z" /></svg></button><textarea rows="2" placeholder="Write a message" class="max-h-32 flex-1 resize-none border-0 bg-transparent px-1 py-2 text-sm text-slate-700 outline-none placeholder:text-slate-400"></textarea><button type="button" class="inline-flex h-11 flex-none items-center gap-2 rounded-xl bg-brand-600 px-4 text-sm font-semibold text-white transition-colors hover:bg-brand-700"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m6.75 12 4.5 4.5 6-12" /></svg>Send</button></div></div>
        </div>
    </article>

    <article data-thread-panel="internal-3" class="thread-panel hidden flex h-full flex-col">
        <div class="flex items-center justify-between gap-3 border-b border-slate-200 p-4 bg-white">
            <div class="flex min-w-0 items-center gap-3"><button type="button" class="back-to-list-btn hidden h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition-colors hover:bg-slate-50 lg:hidden"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg></button><div class="min-w-0"><div class="flex items-center gap-2"><h2 class="truncate text-lg font-semibold text-slate-900">Finance Manager</h2><span class="rounded-full bg-brand-50 px-2 py-0.5 text-[11px] font-semibold text-brand-700">Internal</span></div><p class="mt-0.5 text-sm text-slate-500">2 people active, 11 messages today</p></div></div>
            <div class="flex items-center gap-2"><button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition-colors hover:bg-slate-50"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg></button><button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition-colors hover:bg-slate-50"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5.25c-4.97 0-9 3.43-9 7.65 0 2.53 1.42 4.8 3.68 6.24L6 21l2.28-1.54c1.11.29 2.29.44 3.72.44 4.97 0 9-3.43 9-7.65s-4.03-7.5-9-7.5Z" /></svg></button><button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition-colors hover:bg-slate-50"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm0 6a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm0 6a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" /></svg></button></div>
        </div>
        <div class="flex flex-1 flex-col bg-slate-50/60"><div class="flex-1 space-y-4 overflow-y-auto p-4 scrollbar-hide"><div class="flex justify-start"><div class="max-w-[82%] rounded-2xl rounded-bl-md border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm"><div class="mb-1 flex items-center justify-between gap-4 text-[11px] text-slate-400"><span class="font-semibold">Fiona</span><span class="mono">Yesterday</span></div><p class="leading-6">Please approve the new supplier payment batch.</p></div></div><div class="flex justify-end"><div class="max-w-[82%] rounded-2xl rounded-br-md bg-brand-600 px-4 py-3 text-sm text-white shadow-sm"><div class="mb-1 flex items-center justify-between gap-4 text-[11px] text-brand-100"><span class="font-semibold">You</span><span class="mono">Yesterday</span></div><p class="leading-6">Approved. Finance can process it today.</p></div></div></div><div class="border-t border-slate-200 bg-white p-4"><div class="mb-3 flex flex-wrap items-center gap-2 text-xs text-slate-500"><span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-600"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>Online now</span><span class="rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-600">Encryption enabled</span></div><div class="flex items-end gap-3 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm"><button type="button" class="inline-flex h-10 w-10 flex-none items-center justify-center rounded-xl bg-slate-100 text-slate-600 transition-colors hover:bg-slate-200"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5v6m3-3H9m6.75 4.5a9 9 0 1 0-13.5 0l1.98-1.98a6.2 6.2 0 0 1 8.76 0l1.98 1.98Z" /></svg></button><textarea rows="2" placeholder="Write a message" class="max-h-32 flex-1 resize-none border-0 bg-transparent px-1 py-2 text-sm text-slate-700 outline-none placeholder:text-slate-400"></textarea><button type="button" class="inline-flex h-11 flex-none items-center gap-2 rounded-xl bg-brand-600 px-4 text-sm font-semibold text-white transition-colors hover:bg-brand-700"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m6.75 12 4.5 4.5 6-12" /></svg>Send</button></div></div></div>
    </article>

    <article data-thread-panel="sms-0" class="thread-panel hidden flex h-full flex-col">
        <div class="flex items-center justify-between gap-3 border-b border-slate-200 p-4 bg-white">
            <div class="flex min-w-0 items-center gap-3"><button type="button" class="back-to-list-btn hidden h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition-colors hover:bg-slate-50 lg:hidden"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg></button><div class="min-w-0"><div class="flex items-center gap-2"><h2 class="truncate text-lg font-semibold text-slate-900">Asha M.</h2><span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-700">SMS</span></div><p class="mt-0.5 text-sm text-slate-500">2 active SMS threads, last sync 1 min ago</p></div></div>
            <div class="flex items-center gap-2"><button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition-colors hover:bg-slate-50"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg></button><button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition-colors hover:bg-slate-50"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5.25c-4.97 0-9 3.43-9 7.65 0 2.53 1.42 4.8 3.68 6.24L6 21l2.28-1.54c1.11.29 2.29.44 3.72.44 4.97 0 9-3.43 9-7.65s-4.03-7.5-9-7.5Z" /></svg></button><button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition-colors hover:bg-slate-50"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm0 6a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm0 6a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" /></svg></button></div>
        </div>
        <div class="flex flex-1 flex-col bg-slate-50/60"><div class="flex-1 space-y-4 overflow-y-auto p-4 scrollbar-hide"><div class="flex justify-start"><div class="max-w-[82%] rounded-2xl rounded-bl-md border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm"><div class="mb-1 flex items-center justify-between gap-4 text-[11px] text-slate-400"><span class="font-semibold">Asha</span><span class="mono">10:01</span></div><p class="leading-6">I will arrive at the office by 10:30.</p></div></div><div class="flex justify-end"><div class="max-w-[82%] rounded-2xl rounded-br-md bg-brand-600 px-4 py-3 text-sm text-white shadow-sm"><div class="mb-1 flex items-center justify-between gap-4 text-[11px] text-brand-100"><span class="font-semibold">You</span><span class="mono">10:03</span></div><p class="leading-6">Thank you. Please check in at reception when you arrive.</p></div></div><div class="flex justify-start"><div class="max-w-[82%] rounded-2xl rounded-bl-md border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm"><div class="mb-1 flex items-center justify-between gap-4 text-[11px] text-slate-400"><span class="font-semibold">Asha</span><span class="mono">10:05</span></div><p class="leading-6">Noted. See you shortly.</p></div></div></div><div class="border-t border-slate-200 bg-white p-4"><div class="mb-3 flex flex-wrap items-center gap-2 text-xs text-slate-500"><span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-600"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>Online now</span><span class="rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-600">Encryption enabled</span></div><div class="flex items-end gap-3 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm"><button type="button" class="inline-flex h-10 w-10 flex-none items-center justify-center rounded-xl bg-slate-100 text-slate-600 transition-colors hover:bg-slate-200"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5v6m3-3H9m6.75 4.5a9 9 0 1 0-13.5 0l1.98-1.98a6.2 6.2 0 0 1 8.76 0l1.98 1.98Z" /></svg></button><textarea rows="2" placeholder="Write a message" class="max-h-32 flex-1 resize-none border-0 bg-transparent px-1 py-2 text-sm text-slate-700 outline-none placeholder:text-slate-400"></textarea><button type="button" class="inline-flex h-11 flex-none items-center gap-2 rounded-xl bg-brand-600 px-4 text-sm font-semibold text-white transition-colors hover:bg-brand-700"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m6.75 12 4.5 4.5 6-12" /></svg>Send</button></div></div></div>
    </article>

    <article data-thread-panel="sms-1" class="thread-panel hidden flex h-full flex-col">
        <div class="flex items-center justify-between gap-3 border-b border-slate-200 p-4 bg-white">
            <div class="flex min-w-0 items-center gap-3"><button type="button" class="back-to-list-btn hidden h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition-colors hover:bg-slate-50 lg:hidden"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg></button><div class="min-w-0"><div class="flex items-center gap-2"><h2 class="truncate text-lg font-semibold text-slate-900">Nuru Traders</h2><span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-700">SMS</span></div><p class="mt-0.5 text-sm text-slate-500">External phone conversations</p></div></div>
            <div class="flex items-center gap-2"><button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition-colors hover:bg-slate-50"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg></button><button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition-colors hover:bg-slate-50"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5.25c-4.97 0-9 3.43-9 7.65 0 2.53 1.42 4.8 3.68 6.24L6 21l2.28-1.54c1.11.29 2.29.44 3.72.44 4.97 0 9-3.43 9-7.65s-4.03-7.5-9-7.5Z" /></svg></button><button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition-colors hover:bg-slate-50"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm0 6a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm0 6a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" /></svg></button></div>
        </div>
        <div class="flex flex-1 flex-col bg-slate-50/60"><div class="flex-1 space-y-4 overflow-y-auto p-4 scrollbar-hide"><div class="flex justify-start"><div class="max-w-[82%] rounded-2xl rounded-bl-md border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm"><div class="mb-1 flex items-center justify-between gap-4 text-[11px] text-slate-400"><span class="font-semibold">Nuru Traders</span><span class="mono">09:20</span></div><p class="leading-6">Please share the payment confirmation.</p></div></div><div class="flex justify-end"><div class="max-w-[82%] rounded-2xl rounded-br-md bg-brand-600 px-4 py-3 text-sm text-white shadow-sm"><div class="mb-1 flex items-center justify-between gap-4 text-[11px] text-brand-100"><span class="font-semibold">You</span><span class="mono">09:24</span></div><p class="leading-6">The transfer receipt has been shared on WhatsApp.</p></div></div></div><div class="border-t border-slate-200 bg-white p-4"><div class="mb-3 flex flex-wrap items-center gap-2 text-xs text-slate-500"><span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-600"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>Online now</span><span class="rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-600">Encryption enabled</span></div><div class="flex items-end gap-3 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm"><button type="button" class="inline-flex h-10 w-10 flex-none items-center justify-center rounded-xl bg-slate-100 text-slate-600 transition-colors hover:bg-slate-200"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5v6m3-3H9m6.75 4.5a9 9 0 1 0-13.5 0l1.98-1.98a6.2 6.2 0 0 1 8.76 0l1.98 1.98Z" /></svg></button><textarea rows="2" placeholder="Write a message" class="max-h-32 flex-1 resize-none border-0 bg-transparent px-1 py-2 text-sm text-slate-700 outline-none placeholder:text-slate-400"></textarea><button type="button" class="inline-flex h-11 flex-none items-center gap-2 rounded-xl bg-brand-600 px-4 text-sm font-semibold text-white transition-colors hover:bg-brand-700"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m6.75 12 4.5 4.5 6-12" /></svg>Send</button></div></div></div>
    </article>

    <article data-thread-panel="sms-2" class="thread-panel hidden flex h-full flex-col">
        <div class="flex items-center justify-between gap-3 border-b border-slate-200 p-4 bg-white">
            <div class="flex min-w-0 items-center gap-3"><button type="button" class="back-to-list-btn hidden h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition-colors hover:bg-slate-50 lg:hidden"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg></button><div class="min-w-0"><div class="flex items-center gap-2"><h2 class="truncate text-lg font-semibold text-slate-900">Joseph K.</h2><span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-700">SMS</span></div><p class="mt-0.5 text-sm text-slate-500">External phone conversations</p></div></div>
            <div class="flex items-center gap-2"><button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition-colors hover:bg-slate-50"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg></button><button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition-colors hover:bg-slate-50"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5.25c-4.97 0-9 3.43-9 7.65 0 2.53 1.42 4.8 3.68 6.24L6 21l2.28-1.54c1.11.29 2.29.44 3.72.44 4.97 0 9-3.43 9-7.65s-4.03-7.5-9-7.5Z" /></svg></button><button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition-colors hover:bg-slate-50"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm0 6a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm0 6a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" /></svg></button></div>
        </div>
        <div class="flex flex-1 flex-col bg-slate-50/60"><div class="flex-1 space-y-4 overflow-y-auto p-4 scrollbar-hide"><div class="flex justify-start"><div class="max-w-[82%] rounded-2xl rounded-bl-md border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm"><div class="mb-1 flex items-center justify-between gap-4 text-[11px] text-slate-400"><span class="font-semibold">Joseph</span><span class="mono">Yesterday</span></div><p class="leading-6">Thanks for the delivery update.</p></div></div><div class="flex justify-end"><div class="max-w-[82%] rounded-2xl rounded-br-md bg-brand-600 px-4 py-3 text-sm text-white shadow-sm"><div class="mb-1 flex items-center justify-between gap-4 text-[11px] text-brand-100"><span class="font-semibold">You</span><span class="mono">Yesterday</span></div><p class="leading-6">The parcel is out for delivery and should arrive before noon.</p></div></div></div><div class="border-t border-slate-200 bg-white p-4"><div class="mb-3 flex flex-wrap items-center gap-2 text-xs text-slate-500"><span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-600"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>Online now</span><span class="rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-600">Encryption enabled</span></div><div class="flex items-end gap-3 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm"><button type="button" class="inline-flex h-10 w-10 flex-none items-center justify-center rounded-xl bg-slate-100 text-slate-600 transition-colors hover:bg-slate-200"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5v6m3-3H9m6.75 4.5a9 9 0 1 0-13.5 0l1.98-1.98a6.2 6.2 0 0 1 8.76 0l1.98 1.98Z" /></svg></button><textarea rows="2" placeholder="Write a message" class="max-h-32 flex-1 resize-none border-0 bg-transparent px-1 py-2 text-sm text-slate-700 outline-none placeholder:text-slate-400"></textarea><button type="button" class="inline-flex h-11 flex-none items-center gap-2 rounded-xl bg-brand-600 px-4 text-sm font-semibold text-white transition-colors hover:bg-brand-700"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m6.75 12 4.5 4.5 6-12" /></svg>Send</button></div></div></div>
    </article>
    --}}
</div>
