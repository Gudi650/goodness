<script>
    const tabInternalBtn = document.getElementById('tabInternalBtn');
    const tabSmsBtn = document.getElementById('tabSmsBtn');
    const listTitle = document.getElementById('listTitle');
    const listSubtitle = document.getElementById('listSubtitle');
    const listCount = document.getElementById('listCount');
    const internalConversationList = document.getElementById('internalConversationList');
    const smsConversationList = document.getElementById('smsConversationList');
    const activeChatTitle = document.getElementById('activeChatTitle');
    const activeChatMeta = document.getElementById('activeChatMeta');
    const activeChatTag = document.getElementById('activeChatTag');
    const messageThread = document.getElementById('messageThread');
    const messageInput = document.getElementById('messageInput');
    const sendMessageBtn = document.getElementById('sendMessageBtn');
    const backToListBtn = document.getElementById('backToListBtn');
    const chatPane = document.getElementById('chatPane');

    const mobileQuery = window.matchMedia('(max-width: 1023px)');

    const appData = {
        internal: {
            title: 'Human Resources',
            subtitle: 'Departments and employees',
            count: '12',
            meta: '3 people active, 18 messages today',
            tag: 'Internal',
            selectedIndex: 0,
            messages: [
                { sender: 'other', name: 'Mariam', time: '09:30', body: 'Good morning team. Payroll documents are ready for review.' },
                { sender: 'me', name: 'You', time: '09:33', body: 'Please send the final version to finance and keep a copy in the shared folder.' },
                { sender: 'other', name: 'Amina', time: '09:42', body: 'Done. I have also updated the employee benefit notes.' }
            ]
        },
        sms: {
            title: 'Asha M.',
            subtitle: 'External phone conversations',
            count: '8',
            meta: '2 active SMS threads, last sync 1 min ago',
            tag: 'SMS',
            selectedIndex: 0,
            messages: [
                { sender: 'other', name: 'Asha', time: '10:01', body: 'I will arrive at the office by 10:30.' },
                { sender: 'me', name: 'You', time: '10:03', body: 'Thank you. Please check in at reception when you arrive.' },
                { sender: 'other', name: 'Asha', time: '10:05', body: 'Noted. See you shortly.' }
            ]
        }
    };

    let currentMode = 'internal';
    let mobileView = 'list';

    function formatMessageBubble(message) {
        const isMe = message.sender === 'me';
        return `
            <div class="flex ${isMe ? 'justify-end' : 'justify-start'}">
                <div class="max-w-[82%] rounded-2xl px-4 py-3 text-sm shadow-sm ${isMe ? 'bg-brand-600 text-white rounded-br-md' : 'bg-white text-slate-700 border border-slate-200 rounded-bl-md'}">
                    <div class="mb-1 flex items-center justify-between gap-4 text-[11px] ${isMe ? 'text-brand-100' : 'text-slate-400'}">
                        <span class="font-semibold">${message.name}</span>
                        <span class="mono">${message.time}</span>
                    </div>
                    <p class="leading-6">${message.body}</p>
                </div>
            </div>
        `;
    }

    function renderMessages() {
        const data = appData[currentMode];
        messageThread.innerHTML = data.messages.map(formatMessageBubble).join('');
        messageThread.scrollTop = messageThread.scrollHeight;
        activeChatTitle.textContent = data.title;
        activeChatMeta.textContent = data.meta;
        activeChatTag.textContent = data.tag;
        activeChatTag.className = `rounded-full px-2 py-0.5 text-[11px] font-semibold ${currentMode === 'internal' ? 'bg-brand-50 text-brand-700' : 'bg-slate-100 text-slate-700'}`;
    }

    function syncConversationListVisibility() {
        const isInternal = currentMode === 'internal';

        internalConversationList.classList.toggle('hidden', mobileView === 'chat' ? true : !isInternal);
        smsConversationList.classList.toggle('hidden', mobileView === 'chat' ? true : isInternal);
    }

    function setTabState(mode) {
        const isInternal = mode === 'internal';
        tabInternalBtn.className = `messages-tab-btn py-4 text-sm font-medium cursor-pointer transition-colors border-b-2 ${isInternal ? 'text-slate-700 border-brand-600 hover:text-slate-900' : 'text-slate-500 border-transparent hover:text-slate-700'}`;
        tabSmsBtn.className = `messages-tab-btn py-4 text-sm font-medium cursor-pointer transition-colors border-b-2 ${!isInternal ? 'text-slate-700 border-brand-600 hover:text-slate-900' : 'text-slate-500 border-transparent hover:text-slate-700'}`;
        listTitle.textContent = isInternal ? 'Internal Conversations' : 'SMS Threads';
        listSubtitle.textContent = appData[mode].subtitle;
        listCount.textContent = appData[mode].count;
        syncConversationListVisibility();
    }

    function setMode(mode) {
        currentMode = mode;
        setTabState(mode);

        document.querySelectorAll('.conversation-item').forEach((item) => {
            const itemMode = item.getAttribute('data-mode');
            const selected = itemMode === mode && Number(item.getAttribute('data-chat')) === appData[mode].selectedIndex;
            item.classList.toggle('border', selected);
            item.classList.toggle('border-brand-100', selected);
            item.classList.toggle('bg-brand-50', selected);
        });

        if (mobileQuery.matches) {
            const showChat = mobileView === 'chat';
            chatPane.classList.toggle('hidden', !showChat);
            backToListBtn.classList.toggle('hidden', !showChat);
        } else {
            chatPane.classList.remove('hidden');
            backToListBtn.classList.add('hidden');
        }

        renderMessages();
    }

    function switchConversation(index, mode) {
        appData[mode].selectedIndex = index;
        mobileView = 'chat';
        setMode(mode);
    }

    document.querySelectorAll('.conversation-item').forEach((button) => {
        button.addEventListener('click', () => {
            switchConversation(Number(button.dataset.chat), button.dataset.mode);
        });
    });

    tabInternalBtn.addEventListener('click', () => {
        mobileView = 'list';
        setMode('internal');
    });

    tabSmsBtn.addEventListener('click', () => {
        mobileView = 'list';
        setMode('sms');
    });

    backToListBtn.addEventListener('click', () => {
        mobileView = 'list';
        setMode(currentMode);
    });

    function sendMessage() {
        const text = messageInput.value.trim();
        if (!text) return;

        appData[currentMode].messages.push({
            sender: 'me',
            name: 'You',
            time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
            body: text
        });

        messageInput.value = '';
        renderMessages();
    }

    sendMessageBtn.addEventListener('click', sendMessage);
    messageInput.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            sendMessage();
        }
    });

    function syncResponsiveState() {
        setMode(currentMode);
    }

    mobileQuery.addEventListener('change', syncResponsiveState);
    syncResponsiveState();
    setMode('internal');
</script>