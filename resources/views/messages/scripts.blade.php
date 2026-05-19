<script>
    const tabInternalBtn = document.getElementById('tabInternalBtn');
    const tabSmsBtn = document.getElementById('tabSmsBtn');
    const internalModePane = document.getElementById('internalModePane');
    const smsModePane = document.getElementById('smsModePane');
    const chatPane = document.getElementById('chatPane');
    const conversationItems = Array.from(document.querySelectorAll('.conversation-item'));
    const threadPanels = Array.from(document.querySelectorAll('[data-thread-panel]'));
    const backToListButtons = Array.from(document.querySelectorAll('.back-to-list-btn'));
    const mobileQuery = window.matchMedia('(max-width: 1023px)');

    const selectedConversation = {
        internal: 0,
        sms: 0
    };

    let currentMode = 'internal';
    let mobileView = 'list';

    function setTabState(mode) {
        const isInternal = mode === 'internal';

        tabInternalBtn.className = `messages-tab-btn py-4 text-sm font-medium cursor-pointer transition-colors border-b-2 ${isInternal ? 'text-slate-700 border-brand-600 hover:text-slate-900' : 'text-slate-500 border-transparent hover:text-slate-700'}`;
        tabSmsBtn.className = `messages-tab-btn py-4 text-sm font-medium cursor-pointer transition-colors border-b-2 ${!isInternal ? 'text-slate-700 border-brand-600 hover:text-slate-900' : 'text-slate-500 border-transparent hover:text-slate-700'}`;

        internalModePane.classList.toggle('hidden', !isInternal);
        smsModePane.classList.toggle('hidden', isInternal);
    }

    function setSelectedConversation(mode, index) {
        conversationItems.forEach((item) => {
            const itemMode = item.dataset.mode;
            const itemIndex = Number(item.dataset.chat);
            const selected = itemMode === mode && itemIndex === index;

            item.classList.toggle('border', selected);
            item.classList.toggle('border-brand-100', selected);
            item.classList.toggle('bg-brand-50', selected);
        });

        const activeThreadKey = `${mode}-${index}`;
        threadPanels.forEach((panel) => {
            panel.classList.toggle('hidden', panel.dataset.threadPanel !== activeThreadKey);
        });

        if (chatPane) {
            chatPane.dataset.selectedThread = mode === 'internal' ? String(index) : '';
        }
    }

    function syncLayout() {
        const showChat = !mobileQuery.matches || mobileView === 'chat';
        chatPane.classList.toggle('hidden', !showChat && mobileQuery.matches);
        backToListButtons.forEach((button) => {
            button.classList.toggle('hidden', !mobileQuery.matches || mobileView !== 'chat');
        });
    }

    function setMode(mode) {
        currentMode = mode;
        setTabState(mode);
        setSelectedConversation(mode, selectedConversation[mode]);
        syncLayout();
    }

    function switchConversation(index, mode) {
        selectedConversation[mode] = index;
        currentMode = mode;
        mobileView = 'chat';
        setMode(mode);
    }

    conversationItems.forEach((button) => {
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

    backToListButtons.forEach((button) => {
        button.addEventListener('click', () => {
            mobileView = 'list';
            setMode(currentMode);
        });
    });

    mobileQuery.addEventListener('change', () => {
        syncLayout();
    });

    setMode('internal');
</script>
