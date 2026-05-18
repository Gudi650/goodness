    <div class="flex items-center justify-between gap-3 border-b border-slate-200 p-4">
        <div class="flex min-w-0 items-center gap-3">
            <button id="backToListBtn" type="button" class="hidden h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition-colors hover:bg-slate-50 lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </button>
            <div class="min-w-0">
                <div class="flex items-center gap-2">
                    <h2 id="activeChatTitle" class="truncate text-lg font-semibold text-slate-900">Human Resources</h2>
                    <span id="activeChatTag" class="rounded-full bg-brand-50 px-2 py-0.5 text-[11px] font-semibold text-brand-700">Internal</span>
                </div>
                <p id="activeChatMeta" class="mt-0.5 text-sm text-slate-500">3 people active, 18 messages today</p>
            </div>
        </div>

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

    <div id="chatPane" class="flex max-h-[calc(100vh-16rem)] min-h-[20rem] flex-col bg-slate-50/60">
        <div class="flex-1 space-y-4 overflow-y-auto p-4 scrollbar-hide" id="messageThread"></div>

        <div class="border-t border-slate-200 bg-white p-4">
            <div class="mb-3 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-600">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Online now
                </span>
                <span class="rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-600">Encryption enabled</span>
            </div>

            <div class="flex items-end gap-3 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
                <button type="button" class="inline-flex h-10 w-10 flex-none items-center justify-center rounded-xl bg-slate-100 text-slate-600 transition-colors hover:bg-slate-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5v6m3-3H9m6.75 4.5a9 9 0 1 0-13.5 0l1.98-1.98a6.2 6.2 0 0 1 8.76 0l1.98 1.98Z" />
                    </svg>
                </button>
                <textarea id="messageInput" rows="2" placeholder="Write a message" class="max-h-32 flex-1 resize-none border-0 bg-transparent px-1 py-2 text-sm text-slate-700 outline-none placeholder:text-slate-400"></textarea>
                <button id="sendMessageBtn" type="button" class="inline-flex h-11 flex-none items-center gap-2 rounded-xl bg-brand-600 px-4 text-sm font-semibold text-white transition-colors hover:bg-brand-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m6.75 12 4.5 4.5 6-12" />
                    </svg>
                    Send
                </button>
            </div>
        </div>
    </div>
