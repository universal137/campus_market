@extends('layouts.app')

@section('content')
<div class="h-[calc(100vh-64px)] bg-white flex overflow-hidden">
    
    <div class="w-80 border-r border-gray-100 bg-gray-50 flex flex-col shrink-0">
        <div class="p-5 border-b border-gray-100 bg-white">
            <h2 class="font-bold text-xl text-gray-800">消息列表</h2>
        </div>
        <div class="flex-1 overflow-y-auto custom-scrollbar p-2 space-y-1">
            @foreach($allConversations as $chat)
                <div onclick="switchChat(event, {{ $chat['id'] }})" 
                     data-chat-id="{{ $chat['id'] }}"
                     class="chat-item group relative flex items-center p-3 rounded-xl transition-all duration-200 ease-out cursor-pointer {{ $chat['id'] == $conversation->id ? 'bg-white shadow-md ring-1 ring-black/5' : 'hover:bg-white/60' }}">
                    
                    <div class="shrink-0">
                        @if(isset($chat['other_user']) && $chat['other_user'] && ($chat['other_user']->avatar_url ?? false))
                            <img src="{{ $chat['other_user']->avatar_url }}" class="w-12 h-12 rounded-full object-cover shadow-sm">
                        @else
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center font-bold text-lg shadow-sm">
                                {{ isset($chat['other_user']) && $chat['other_user'] ? mb_substr($chat['other_user']->name ?? 'U', 0, 1) : 'U' }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="ml-3 overflow-hidden flex-1">
                        <div class="flex justify-between items-center">
                            <h4 class="font-bold text-gray-900 truncate">{{ $chat['other_user']->name ?? '用户' }}</h4>
                            <span class="text-[10px] text-gray-400">
                                @if(isset($chat['last_message_at']))
                                    {{ \Carbon\Carbon::parse($chat['last_message_at'])->format('H:i') }}
                                @else
                                    {{ now()->format('H:i') }}
                                @endif
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 truncate mt-0.5">{{ $chat['last_message']->body ?? '开始聊天...' }}</p>
                    </div>
                    
                    <button onclick="event.stopPropagation(); deleteChat({{ $chat['id'] }}, this)" class="absolute right-2 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-50 text-gray-400 hover:text-red-500 z-10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex-1 flex flex-col bg-[#F9FAFB] relative min-w-0">
        
        <div id="product-header" class="shrink-0 px-6 py-3 bg-white/80 backdrop-blur-md border-b border-gray-100 flex items-center justify-between z-10">
            @if($conversation->product)
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gray-100 overflow-hidden">
                    @php
                        $productImage = $conversation->product->image 
                            ? (str_starts_with($conversation->product->image, 'http://') || str_starts_with($conversation->product->image, 'https://')
                                ? $conversation->product->image 
                                : asset('storage/' . ltrim($conversation->product->image, '/')))
                            : asset('images/placeholder-product.png');
                    @endphp
                    <img src="{{ $productImage }}" class="w-full h-full object-cover">
                </div>
                <div>
                    <div class="text-sm font-bold text-gray-900">正在沟通: {{ $conversation->product->title }}</div>
                    <div class="text-xs text-red-500 font-bold">¥{{ $conversation->product->price }}</div>
                </div>
            </div>
            <a href="{{ route('items.show', $conversation->product->id) }}" class="px-4 py-1.5 bg-blue-50 text-blue-600 text-xs rounded-full font-bold hover:bg-blue-100 transition">查看详情</a>
            @endif
        </div>

        <div id="messages-area" class="flex-1 overflow-y-auto p-6 space-y-6 scroll-smooth">
            </div>

        <div class="shrink-0 p-4 bg-white/90 backdrop-blur border-t border-gray-100 pb-8 z-20">
            <form id="chat-form" class="relative max-w-4xl mx-auto flex items-end gap-2">
                @csrf
                <div class="relative flex-1 bg-gray-100 rounded-full overflow-hidden focus-within:ring-2 focus-within:ring-blue-500 transition-all shadow-sm">
                    <textarea id="message-input" rows="1" 
                        class="w-full bg-transparent text-gray-900 px-5 py-3 pr-12 focus:outline-none resize-none max-h-32" 
                        placeholder="输入消息... (Enter 发送)"
                        style="min-height: 48px;"></textarea>
                </div>
                
                <button type="submit" class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 hover:scale-105 transition-all shadow-md shrink-0 mb-0.5">
                    <svg class="w-5 h-5 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"></path></svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    let conversationId = {{ $conversation->id }};
    const currentUserId = {{ auth()->id() }};
    const messagesArea = document.getElementById('messages-area');
    const input = document.getElementById('message-input');
    const productHeader = document.getElementById('product-header');
    let pollingInterval = null;

    // Escape HTML helper
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // 1. Load Messages
    fetch(`/api/conversations/${conversationId}`)
        .then(res => res.json())
        .then(data => {
            // Get other user info for message rendering
            const otherUser = data.conversation?.other_user || {};
            
            if (data.messages && data.messages.length > 0) {
                data.messages.forEach(msg => {
                    // Add sender info if not from current user
                    if (!msg.is_me) {
                        msg.sender_name = otherUser.name || '用户';
                        msg.sender_avatar = otherUser.avatar_url || null;
                    }
                    msg.user_id = msg.is_me ? currentUserId : (otherUser.id || null);
                    appendMessage(msg);
                });
                scrollToBottom();
            }
        })
        .catch(err => {
            console.error('Error fetching messages:', err);
        });

    // 2. Render Logic (Smart Avatar)
    function appendMessage(msg) {
        const isMe = msg.user_id === currentUserId || msg.is_me;
        
        // Avatar Logic
        let avatarHtml = '';
        if (!isMe) {
             // If URL exists, use IMG, else use First Char
            if (msg.sender_avatar) {
                avatarHtml = `<img src="${escapeHtml(msg.sender_avatar)}" class="w-8 h-8 rounded-full object-cover mt-1 shadow-sm">`;
            } else {
                const firstChar = msg.sender_name ? msg.sender_name.charAt(0) : 'U';
                avatarHtml = `<div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 text-gray-600 flex items-center justify-center font-bold text-xs mt-1 shadow-sm">${escapeHtml(firstChar)}</div>`;
            }
        }

        const div = document.createElement('div');
        div.className = `flex w-full gap-3 ${isMe ? 'justify-end' : 'justify-start'} animate-slide-up`;
        div.setAttribute('data-message-id', msg.id || 'temp-' + Date.now());
        
        div.innerHTML = `
            ${!isMe ? avatarHtml : ''}
            <div class="max-w-[70%] flex flex-col ${isMe ? 'items-end' : 'items-start'}">
                <div class="px-5 py-2.5 rounded-2xl shadow-sm text-[15px] leading-relaxed break-words ${
                    isMe 
                    ? 'bg-blue-600 text-white rounded-br-sm' 
                    : 'bg-white text-gray-800 border border-gray-100 rounded-bl-sm'
                }">
                    ${escapeHtml(msg.body)}
                </div>
                <span class="text-[10px] text-gray-400 mt-1 mx-1">${msg.time_ago || '刚刚'}</span>
            </div>
        `;
        messagesArea.appendChild(div);
    }

    function scrollToBottom() {
        setTimeout(() => {
            messagesArea.scrollTop = messagesArea.scrollHeight;
        }, 100);
    }

    // 3. Handle Enter Key
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault(); // Stop new line
            document.getElementById('chat-form').dispatchEvent(new Event('submit')); // Trigger submit
        }
    });

    // 4. Handle Submit
    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const body = input.value.trim();
        if(!body) return;

        // Optimistic UI
        appendMessage({ 
            user_id: currentUserId, 
            body: body, 
            time_ago: '发送中...',
            sender_name: 'Me',
            is_me: true
        });
        
        scrollToBottom();
        input.value = '';
        input.style.height = 'auto'; // Reset height

        // Send API
        fetch(`/conversations/${conversationId}/messages`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ body: body })
        })
        .then(res => res.json())
        .then(data => {
            // Remove optimistic message
            const tempMsg = messagesArea.querySelector('[data-message-id^="temp-"]');
            if (tempMsg) {
                tempMsg.remove();
            }
            // Add real message
            if (data.message) {
                appendMessage({
                    id: data.message.id,
                    user_id: data.message.user_id || currentUserId,
                    body: data.message.body,
                    time_ago: data.message.time_ago || '刚刚',
                    sender_name: data.message.sender_name,
                    sender_avatar: data.message.sender_avatar,
                    is_me: data.message.is_me !== undefined ? data.message.is_me : true
                });
                scrollToBottom();
            }
        })
        .catch(err => {
            console.error('Error sending message:', err);
            // Remove optimistic message on error
            const tempMsg = messagesArea.querySelector('[data-message-id^="temp-"]');
            if (tempMsg) {
                tempMsg.remove();
            }
            alert('发送消息失败，请重试');
        });
    });

    // Auto-resize Input
    input.addEventListener("input", function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Start polling function
    function startPolling() {
        // Clear existing interval
        if (pollingInterval) {
            clearInterval(pollingInterval);
        }
        
        pollingInterval = setInterval(() => {
            fetch(`/api/conversations/${conversationId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.messages && data.messages.length > 0) {
                        // Get current message IDs
                        const currentMessageIds = new Set();
                        messagesArea.querySelectorAll('[data-message-id]').forEach(el => {
                            const msgId = el.getAttribute('data-message-id');
                            if (msgId && !msgId.startsWith('temp-')) {
                                currentMessageIds.add(String(msgId));
                            }
                        });

                        // Find new messages
                        const newMessages = data.messages.filter(msg => 
                            !currentMessageIds.has(String(msg.id))
                        );
                        
                        if (newMessages.length > 0) {
                            newMessages.forEach(msg => {
                                appendMessage(msg);
                            });
                            scrollToBottom();
                        }
                    }
                })
                .catch(err => console.error('Error polling messages:', err));
        }, 3000);
    }

    // Start polling on page load
    startPolling();

    // Handle browser back/forward buttons
    window.addEventListener('popstate', function(event) {
        if (event.state && event.state.conversationId) {
            // Find the chat item and trigger switch
            const chatItem = document.querySelector(`[data-chat-id="${event.state.conversationId}"]`);
            if (chatItem) {
                switchChat({ preventDefault: () => {} }, event.state.conversationId);
            }
        }
    });

    // Switch Chat Function - Smooth AJAX Transition
    function switchChat(event, chatId) {
        event.preventDefault();
        
        // Don't switch if already on this chat
        if (chatId === conversationId) {
            return;
        }

        // 1. Immediate Visual Feedback - Update Active State
        document.querySelectorAll('.chat-item').forEach(item => {
            const itemChatId = parseInt(item.getAttribute('data-chat-id'));
            if (itemChatId === chatId) {
                item.classList.add('bg-white', 'shadow-md', 'ring-1', 'ring-black/5');
                item.classList.remove('hover:bg-white/60');
            } else {
                item.classList.remove('bg-white', 'shadow-md', 'ring-1', 'ring-black/5');
                item.classList.add('hover:bg-white/60');
            }
        });

        // 2. Show Skeleton Loader
        messagesArea.innerHTML = `
            <div class="skeleton-container space-y-6">
                <div class="skeleton-message flex gap-3">
                    <div class="skeleton-avatar w-8 h-8 rounded-full bg-gray-200 animate-pulse"></div>
                    <div class="flex-1 space-y-2">
                        <div class="skeleton-bar h-4 bg-gray-200 rounded animate-pulse" style="width: 60%;"></div>
                        <div class="skeleton-bar h-4 bg-gray-200 rounded animate-pulse" style="width: 80%;"></div>
                    </div>
                </div>
                <div class="skeleton-message flex gap-3 justify-end">
                    <div class="flex-1 space-y-2 flex items-end flex-col">
                        <div class="skeleton-bar h-4 bg-gray-200 rounded animate-pulse" style="width: 50%;"></div>
                        <div class="skeleton-bar h-4 bg-gray-200 rounded animate-pulse" style="width: 70%;"></div>
                    </div>
                </div>
                <div class="skeleton-message flex gap-3">
                    <div class="skeleton-avatar w-8 h-8 rounded-full bg-gray-200 animate-pulse"></div>
                    <div class="flex-1 space-y-2">
                        <div class="skeleton-bar h-4 bg-gray-200 rounded animate-pulse" style="width: 65%;"></div>
                    </div>
                </div>
                <div class="skeleton-message flex gap-3 justify-end">
                    <div class="flex-1 space-y-2 flex items-end flex-col">
                        <div class="skeleton-bar h-4 bg-gray-200 rounded animate-pulse" style="width: 55%;"></div>
                    </div>
                </div>
            </div>
        `;

        // 3. Fetch Data
        fetch(`/api/conversations/${chatId}`)
            .then(res => res.json())
            .then(data => {
                // Update conversation ID
                conversationId = chatId;

                // 4. Update Header with Fade-in
                updateProductHeader(data.conversation);

                // 5. Clear Skeleton and Render Messages
                messagesArea.innerHTML = '';
                
                if (data.messages && data.messages.length > 0) {
                    // Get other user info for message rendering
                    const otherUser = data.conversation.other_user || {};
                    
                    data.messages.forEach((msg, index) => {
                        // Add sender info if not from current user
                        if (!msg.is_me) {
                            msg.sender_name = otherUser.name || '用户';
                            msg.sender_avatar = otherUser.avatar_url || null;
                        }
                        msg.user_id = msg.is_me ? currentUserId : (otherUser.id || null);
                        
                        // Add delay for staggered animation
                        setTimeout(() => {
                            appendMessage(msg);
                            if (index === data.messages.length - 1) {
                                scrollToBottom();
                            }
                        }, index * 30); // 30ms delay between each message
                    });
                } else {
                    // Show empty state
                    messagesArea.innerHTML = '<div class="text-center text-gray-400 mt-12">还没有消息，开始聊天吧！</div>';
                }

                // 6. Update URL (pushState)
                history.pushState({ conversationId: chatId }, '', `/chat/${chatId}`);

                // Restart polling for new conversation
                startPolling();
            })
            .catch(err => {
                console.error('Error switching chat:', err);
                messagesArea.innerHTML = '<div class="text-center text-red-400 mt-12">加载失败，请刷新页面重试</div>';
            });
    }

    // Update Product Header
    function updateProductHeader(conversation) {
        if (conversation && conversation.product) {
            // API already returns formatted image path (starts with /storage/ or /images/ or full URL)
            const productImage = conversation.product.image || '/images/placeholder-product.png';
            
            productHeader.innerHTML = `
                <div class="flex items-center gap-3 animate-fade-in">
                    <div class="w-10 h-10 rounded-lg bg-gray-100 overflow-hidden">
                        <img src="${productImage}" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <div class="text-sm font-bold text-gray-900">正在沟通: ${escapeHtml(conversation.product.title)}</div>
                        <div class="text-xs text-red-500 font-bold">¥${conversation.product.price}</div>
                    </div>
                </div>
                <a href="/items/${conversation.product.id}" class="px-4 py-1.5 bg-blue-50 text-blue-600 text-xs rounded-full font-bold hover:bg-blue-100 transition animate-fade-in">查看详情</a>
            `;
            productHeader.style.display = 'flex';
        } else {
            productHeader.innerHTML = '';
            productHeader.style.display = 'none';
        }
    }

    // Delete Chat Function
    function deleteChat(chatId, buttonElement) {
        if (!confirm('确定要删除这个对话吗？')) {
            return;
        }

        const chatItem = buttonElement.closest('.chat-item');
        
        fetch(`/conversations/${chatId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                // Smooth fade out animation
                chatItem.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                chatItem.style.opacity = '0';
                chatItem.style.transform = 'translateX(-20px)';
                
                setTimeout(() => {
                    chatItem.remove();
                    
                    // If this was the current conversation, redirect to chat index
                    if (chatId === conversationId) {
                        window.location.href = '{{ route("chat.index") }}';
                    }
                }, 300);
            } else {
                alert('删除失败，请重试');
            }
        })
        .catch(err => {
            console.error('Error deleting chat:', err);
            alert('删除失败，请重试');
        });
    }
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 0px; background: transparent; } /* Cleaner look */
    
    /* Slide Up Animation for Messages */
    @keyframes slideUp {
        from { 
            opacity: 0; 
            transform: translateY(10px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }
    .animate-slide-up {
        animation: slideUp 0.4s ease-out forwards;
    }

    /* Fade In Animation for Header */
    @keyframes fadeIn {
        from { 
            opacity: 0; 
        }
        to { 
            opacity: 1; 
        }
    }
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }

    /* Skeleton Pulse Animation */
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
    .skeleton-container .animate-pulse {
        animation: pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    /* Smooth transitions for sidebar items */
    .chat-item {
        transition: all 0.2s ease-out;
    }
</style>
@endsection
