function formatTime(ts) {
    try {
        const date = new Date(ts);
        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    } catch (error) {
        return ts || '';
    }
}

//get main chat pane element
function getChatPane() {
    return document.getElementById('chatPane');
}

//get active thread panel element
function getActiveThreadPanel() {
    return document.querySelector('#chatPane article.thread-panel:not(.hidden)');
}

//now tehe active message container inside the active thread panel
function getActiveMessageContainer() {
    const activePanel = getActiveThreadPanel();
    return activePanel?.querySelector('.overflow-y-auto.p-4.scrollbar-hide') || activePanel?.querySelector('.overflow-y-auto.p-4') || null;
}

//make the active message container scroll to bottom
function scrollActiveChatToBottom() {
    const container = getActiveMessageContainer();
    if (!container) {
        return;
    }

    container.scrollTop = container.scrollHeight;
}

//here this function looks at the message and alighns and gives styling depending if its outgoing or incoming
//Also adds header (sender name + time), message text, and optional attachment link.
function createMessageNode(payload, direction = 'incoming') {
    const wrapper = document.createElement('div');
    wrapper.className = direction === 'outgoing' ? 'flex justify-end' : 'flex justify-start';
    if (payload.id) {
        wrapper.dataset.messageId = String(payload.id);
    }

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

    const status = document.createElement('div');
    status.className = direction === 'outgoing'
        ? 'message-ticks mt-1 flex items-center justify-end gap-0.5'
        : 'mt-2 flex items-center justify-end gap-1.5 text-slate-400';
    if (payload.id) {
        status.dataset.messageTicks = 'true';
    }

    if (direction === 'outgoing') {
        const statusState = payload.status || (payload.seen ? 'seen' : (payload.delivered ? 'delivered' : 'sent'));

        if (statusState === 'seen') {
            status.innerHTML = [
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-3 w-3 text-sky-300" data-tick-single><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" /></svg>',
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-3 w-3 -ml-1 text-sky-300" data-tick-double><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" /></svg>'
            ].join('');
        } else {
            status.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-3 w-3 text-slate-300" data-tick-single><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" /></svg>';
        }
    }

    header.appendChild(senderName);
    header.appendChild(time);
    bubble.appendChild(header);
    bubble.appendChild(message);
    if (direction === 'outgoing') {
        bubble.appendChild(status);
    }

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

//renders new incoming text in the chatroom
function renderChatMessage(payload, direction = 'incoming') {
    const container = getActiveMessageContainer();
    if (!container) {
        return;
    }

    container.appendChild(createMessageNode(payload, direction));
    scrollActiveChatToBottom();
}

function updateMessageTicks(messageId, statusState) {
    const messageNode = document.querySelector(`[data-message-id="${messageId}"]`);
    if (!messageNode) {
        return;
    }

    const ticks = messageNode.querySelector('[data-message-ticks]');
    if (!ticks) {
        return;
    }

    ticks.dataset.messageStatus = statusState;
    ticks.classList.remove('text-slate-300', 'text-sky-300');

    if (statusState === 'seen') {
        ticks.classList.add('text-sky-300');
        ticks.innerHTML = [
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-3 w-3 text-sky-300" data-tick-single><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" /></svg>',
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-3 w-3 -ml-1 text-sky-300" data-tick-double><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" /></svg>'
        ].join('');
        return;
    }

    ticks.classList.add('text-slate-300');
    ticks.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-3 w-3 text-slate-300" data-tick-single><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" /></svg>';
}

function markMessageSeen(payload) {
    const messageId = Number(payload.id || 0);
    if (!messageId) {
        return;
    }

    updateMessageTicks(messageId, 'seen');
}

//get the active threadID of the active chat room we are in
function getActiveInternalThreadId() {
    const chatPane = getChatPane();
    const threadId = Number(chatPane?.dataset.selectedThread || 0);

    return Number.isFinite(threadId) ? threadId : null;
}

//this updates the preview text,timestamp and unread badge for the conversation lists
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

    const badge = conversation.querySelector('[data-unread-badge]');
    if (badge) {
        const count = Number.parseInt(badge.textContent || '0', 10) || 0;
        badge.textContent = String(count + 1);
        badge.classList.remove('hidden');
        badge.dataset.unreadBadge = 'true';
        return;
    }

    const newBadge = document.createElement('span');
    newBadge.dataset.unreadBadge = 'true';
    newBadge.className = 'inline-flex items-center justify-center rounded-full bg-red-600 text-white text-[11px] font-semibold px-2 py-0.5';
    newBadge.textContent = '1';

    const nameContainer = conversation.querySelector('.flex.items-center.gap-2');
    if (nameContainer) {
        nameContainer.appendChild(newBadge);
    }
}

function clearConversationUnreadBadge(senderId) {
    const conversation = document.querySelector(`#internalConversationList a[data-chat="${senderId}"]`);
    if (!conversation) {
        return;
    }

    const badge = conversation.querySelector('[data-unread-badge]');
    if (badge) {
        badge.remove();
    }
}

//render now the messages in the chatroom
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
        const receiverId = Number(payload.receiver_id || 0);

        if (senderId === authId) {
            if (payload.id) {
                updateMessageTicks(payload.id, payload.seen ? 'seen' : 'delivered');
            }
            return;
        }

        if (activeThreadId && (activeThreadId === senderId || activeThreadId === receiverId)) {
            const direction = senderId === authId ? 'outgoing' : 'incoming';
            renderChatMessage(payload, direction);
            return;
        }

        updateConversationPreview(payload);
    });

    window.Echo.private(`chat.${authId}`).listen('MessageSeen', (payload) => {
        markMessageSeen(payload);

        const senderId = Number(payload.sender_id || 0);
        const receiverId = Number(payload.receiver_id || 0);

        if (receiverId === authId && senderId) {
            clearConversationUnreadBadge(senderId);
        }
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