<div class="space-y-1" id="internalConversationList">

    {{-- for loop through the users please --}}
    @foreach($users as $user)

        @php $isActive = isset($selectedThread) && $selectedThread == $user->id; @endphp
        
        <a href="{{ route('messages.thread', $user->id) }}" 
            class="conversation-item flex w-full items-center gap-3 rounded-xl border border-brand-100 px-3 py-3 text-left transition-colors hover:bg-slate-100{{ $isActive ? ' bg-slate-200 border-brand-300' : ' bg-brand-50' }}" 
            data-chat="{{ $user->id }}" 
            data-mode="internal"
        >

            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-brand-600 text-sm font-semibold text-white">
                {{ substr($user->name, 0, 2) }}
            </div>

            <div class="min-w-0 flex-1">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <p class="truncate text-sm font-semibold text-slate-900">{{ $user->name }}</p>

                        {{-- unread batch and the number as well --}}
                        @if(!empty($user->unread_count) && $user->unread_count > 0)
                            <span class="inline-flex items-center justify-center rounded-full bg-red-600 text-white text-[11px] font-semibold px-2 py-0.5">{{ $user->unread_count }}</span>
                        @endif

                    </div>
                    <span class="text-[11px] font-medium text-slate-500">
                        @if(!empty($user->last_message_at))
                            {{ \Carbon\Carbon::parse($user->last_message_at)->format('H:i') }}
                        @endif
                    </span>
                </div>

            <p class="truncate text-sm text-slate-600">{{ $user->last_message_text ?? 'No messages yet' }}</p>

        </div>
    </a>
    @endforeach

</div>