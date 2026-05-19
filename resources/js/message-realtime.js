function formatTime(ts) {
    try {
        const date = new Date(ts);
        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    } catch (error) {
        return ts || '';
    }
}

function getChatPane() {
    return document.getElementById('chatPane');
}

function getActiveThreadPanel() {
    return document.querySelector('#chatPane article.thread-panel:not(.hidden)');
}

function getActiveMessageContainer() {
    const activePanel = getActiveThreadPanel();
    return activePanel?.querySelector('.overflow-y-auto.p-4.scrollbar-hide') || activePanel?.querySelector('.overflow-y-auto.p-4') || null;
}

function scrollActiveChatToBottom() {
    const container = getActiveMessageContainer();
    if (!container) {
        return;
    }

    container.scrollTop = container.scrollHeight;
}

function createMessageNode(payload, direction = 'incoming') {
    const wrapper = document.createElement('div');
    wrapper.className = direction === 'outgoing' ? 'flex justify-end' : 'flex justify-start';

    const bubble = document.createElement('div');
    bubble.className = direction === 'outgoing'
        ? 'max-w-[82%] rounded-2xl rounded-br-md bg-brand-600 px-4 py-3 text-sm text-white shadow-sm'
        : 'max-w-[82%] rounded-2xl rounded-bl-md border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm';

    const header = document.createElement('div');
    header.className = direction === 'outgoing'
        ? 'mb-1 flex items-center justify-between gap-4 text-[11px] text-brand-100'
        : 'mb-1 flex items-center justify-between gap-4 text-[11px] text-slate-400';

    const senderName = document.createElement('span');
    senderName.className = 'font-semibold';
    senderName.textContent = direction === 'outgoing' ? 'You' : (payload.sender_name || 'Unknown');

    const time = document.createElement('span');
    time.className = 'mono';
    time.textContent = formatTime(payload.created_at);

    const message = document.createElement('p');
    message.className = 'leading-6';
    message.textContent = payload.message || '';

    header.appendChild(senderName);
    header.appendChild(time);
    bubble.appendChild(header);
    bubble.appendChild(message);

    if (payload.attachment_path) {
        const attachmentWrap = document.createElement('div');
        attachmentWrap.className = 'mt-2';

        const attachment = document.createElement('a');
        attachment.href = '/storage/' + payload.attachment_path;
        attachment.target = '_blank';
        attachment.rel = 'noopener';
        attachment.className = 'inline-flex items-center gap-2 rounded px-3 py-2 bg-slate-100 text-sm text-slate-700 mt-2';
        attachment.textContent = payload.attachment_name || 'attachment';

        attachmentWrap.appendChild(attachment);
        bubble.appendChild(attachmentWrap);
    }

    wrapper.appendChild(bubble);
    return wrapper;
}

function renderChatMessage(payload, direction = 'incoming') {
    const container = getActiveMessageContainer();
    if (!container) {
        return;
    }

    container.appendChild(createMessageNode(payload, direction));
    scrollActiveChatToBottom();
}

function getActiveInternalThreadId() {
    const chatPane = getChatPane();
    const threadId = Number(chatPane?.dataset.selectedThread || 0);

    return Number.isFinite(threadId) ? threadId : null;
}

function updateConversationPreview(payload) {
    const senderId = payload.sender_id;
    const conversation = document.querySelector(`#internalConversationList a[data-chat="${senderId}"]`);

    if (!conversation) {
        return;
    }

    const preview = conversation.querySelector('.truncate.text-sm.text-slate-600');
    if (preview) {
        preview.textContent = payload.message ? payload.message : `Attachment: ${payload.attachment_name || 'file'}`;
    }

    const timeEl = conversation.querySelector('span.text-slate-500');
    if (timeEl) {
        timeEl.textContent = formatTime(payload.created_at);
    }

    const badge = conversation.querySelector('span.inline-flex');
    if (badge) {
        const count = Number.parseInt(badge.textContent || '0', 10) || 0;
        badge.textContent = String(count + 1);
        badge.classList.remove('hidden');
    }
}

function bindChatRealtime() {
    const chatPane = getChatPane();
    if (!chatPane || !window.Echo) {
        return;
    }

    const authId = Number(chatPane.dataset.authId || 0);
    if (!authId) {
        return;
    }

    window.Echo.private(`chat.${authId}`).listen('MessageSent', (payload) => {
        const activeThreadId = getActiveInternalThreadId();
        const senderId = Number(payload.sender_id || 0);

        if (activeThreadId && activeThreadId === senderId) {
            renderChatMessage(payload, 'incoming');
            return;
        }

        updateConversationPreview(payload);
    });

    scrollActiveChatToBottom();
}

window.scrollActiveChatToBottom = scrollActiveChatToBottom;
window.renderChatMessage = renderChatMessage;

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bindChatRealtime, { once: true });
} else {
    bindChatRealtime();
}