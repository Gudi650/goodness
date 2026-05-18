<div class="space-y-1" id="internalConversationList">

    {{-- for loop through the users please --}}
    @foreach($users as $user)
        <a href="{{ route('messages.thread', $user->id) }}" 
            class="conversation-item flex w-full items-center gap-3 rounded-xl border border-brand-100 bg-brand-50 px-3 py-3 text-left transition-colors hover:bg-slate-100" 
            data-chat="{{ $user->id }}" 
            data-mode="internal"
        >

            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-brand-600 text-sm font-semibold text-white">
                {{ substr($user->name, 0, 2) }}
            </div>

            <div class="min-w-0 flex-1">
                <div class="flex items-center justify-between gap-2">
                    <p class="truncate text-sm font-semibold text-slate-900">{{ $user->name }}</p>
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