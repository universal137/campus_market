@extends('layouts.app')

@section('title', '消息 - 校园易')

@push('head')
<style>
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in-up {
        animation: fade-in-up 0.3s ease-out;
    }
    
    .message-bubble {
        max-width: 75%;
        word-wrap: break-word;
    }
    
    .message-bubble--mine {
        margin-left: auto;
    }
    
    .message-bubble--theirs {
        margin-right: auto;
    }
    
    .contact-item {
        position: relative;
    }
    
    .contact-item.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: #3b82f6;
    }
    
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endpush

@section('content')
<div class="h-[calc(100vh-80px)] flex bg-gray-100">
    <!-- Left Sidebar: Contact List -->
    <div class="hidden md:block w-1/3 bg-white border-r border-gray-200 flex flex-col">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">消息</h2>
        </div>
        
        <div id="contacts-list" class="flex-1 overflow-y-auto scrollbar-hide">
            @forelse($conversations as $conversation)
                <div 
                    class="contact-item px-4 py-3 cursor-pointer hover:bg-gray-50 transition-colors border-b border-gray-100"
                    data-conversation-id="{{ $conversation['id'] }}"
                    data-other-user-id="{{ $conversation['other_user']->id }}"
                    data-product-id="{{ $conversation['product']?->id }}"
                >
                    <div class="flex items-center gap-3">
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            @if($conversation['other_user']->avatar)
                                <img 
                                    src="{{ asset('storage/' . $conversation['other_user']->avatar) }}" 
                                    alt="{{ $conversation['other_user']->name }}"
                                    class="w-12 h-12 rounded-full object-cover"
                                >
                            @else
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold text-lg">
                                    {{ mb_substr($conversation['other_user']->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        
                        <!-- Name and Last Message -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-sm font-semibold text-gray-900 truncate">
                                    {{ $conversation['other_user']->name }}
                                </p>
                                @if($conversation['last_message_at'])
                                    <span class="text-xs text-gray-500 flex-shrink-0 ml-2">
                                        {{ \Carbon\Carbon::parse($conversation['last_message_at'])->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-600 truncate">
                                @if($conversation['last_message'])
                                    {{ Str::limit($conversation['last_message']->body, 40) }}
                                @else
                                    <span class="text-gray-400">开始对话...</span>
                                @endif
                            </p>
                        </div>
                        
                        <!-- Unread Badge -->
                        @if($conversation['unread_count'] > 0)
                            <div class="flex-shrink-0">
                                <span class="bg-blue-500 text-white text-xs font-semibold rounded-full w-5 h-5 flex items-center justify-center">
                                    {{ $conversation['unread_count'] > 9 ? '9+' : $conversation['unread_count'] }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">
                    <p class="text-sm">暂无消息</p>
                </div>
            @endforelse
        </div>
    </div>
    
    <!-- Right Area: Chat Window -->
    <div class="flex-1 md:w-2/3 flex flex-col bg-white">
        <!-- Empty State -->
        <div id="empty-state" class="flex-1 flex items-center justify-center text-gray-400">
            <div class="text-center">
                <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <p class="text-sm">选择一个对话开始聊天</p>
            </div>
        </div>
        
        <!-- Chat Window (Hidden by default) -->
        <div id="chat-window" class="hidden flex-1 flex flex-col">
            <!-- Product Context Card (Sticky Header) -->
            <div id="product-context-card" class="bg-white/80 backdrop-blur-sm shadow-sm z-10 p-3 border-b border-gray-200">
                <!-- Product info will be loaded here -->
            </div>
            
            <!-- Messages Area -->
            <div id="messages-container" class="flex-1 overflow-y-auto p-4 space-y-3 scrollbar-hide bg-gray-50">
                <!-- Messages will be loaded here -->
            </div>
            
            <!-- Input Area -->
            <div class="p-4 bg-white border-t border-gray-200">
                <form id="message-form" class="flex items-center gap-3">
                    <input
                        type="text"
                        id="message-input"
                        placeholder="输入消息..."
                        class="flex-1 rounded-full bg-gray-100 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all"
                        autocomplete="off"
                    >
                    <button
                        type="submit"
                        id="send-button"
                        class="w-10 h-10 rounded-full bg-blue-500 hover:bg-blue-600 text-white flex items-center justify-center transition-colors shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const contactsList = document.getElementById('contacts-list');
    const chatWindow = document.getElementById('chat-window');
    const emptyState = document.getElementById('empty-state');
    const messagesContainer = document.getElementById('messages-container');
    const productContextCard = document.getElementById('product-context-card');
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');
    
    let currentConversationId = null;
    let currentProductId = null;
    let pollingInterval = null;
    
    // CSRF token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    // Contact item click handler
    contactsList.addEventListener('click', function(e) {
        const contactItem = e.target.closest('.contact-item');
        if (!contactItem) return;
        
        const conversationId = contactItem.dataset.conversationId;
        const otherUserId = contactItem.dataset.otherUserId;
        const productId = contactItem.dataset.productId;
        
        // Update active state
        document.querySelectorAll('.contact-item').forEach(item => {
            item.classList.remove('active', 'bg-blue-50');
        });
        contactItem.classList.add('active', 'bg-blue-50');
        
        // Load conversation
        loadConversation(conversationId, productId);
    });
    
    // Load conversation messages
    async function loadConversation(conversationId, productId) {
        currentConversationId = conversationId;
        currentProductId = productId;
        
        try {
            const response = await fetch(`/chat/${conversationId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });
            
            if (!response.ok) throw new Error('Failed to load conversation');
            
            const data = await response.json();
            
            // Show chat window
            emptyState.classList.add('hidden');
            chatWindow.classList.remove('hidden');
            
            // Load product context card
            if (data.conversation.product) {
                loadProductContext(data.conversation.product);
            } else {
                productContextCard.innerHTML = '';
            }
            
            // Load messages
            messagesContainer.innerHTML = '';
            data.messages.forEach(message => {
                appendMessage(message);
            });
            
            // Scroll to bottom
            scrollToBottom();
            
            // Start polling for new messages
            startPolling();
            
        } catch (error) {
            console.error('Error loading conversation:', error);
            alert('加载对话失败，请重试');
        }
    }
    
    // Load product context card
    function loadProductContext(product) {
        const isOwner = product.user_id === {{ auth()->id() }};
        const buyButtonText = isOwner ? '标记已售' : '立即购买';
        const buyButtonClass = isOwner 
            ? 'bg-orange-500 hover:bg-orange-600' 
            : 'bg-blue-500 hover:bg-blue-600';
        
        productContextCard.innerHTML = `
            <div class="flex items-center gap-3">
                <img 
                    src="${product.image ? '/storage/' + product.image : 'https://via.placeholder.com/60'}" 
                    alt="${product.title}"
                    class="w-12 h-12 rounded-lg object-cover"
                >
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-semibold text-gray-900 truncate">${product.title}</h3>
                    <p class="text-sm text-blue-600 font-medium">¥${product.price}</p>
                </div>
                <button 
                    type="button"
                    class="${buyButtonClass} text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                    onclick="handleBuyNow(${product.id}, ${isOwner})"
                >
                    ${buyButtonText}
                </button>
            </div>
        `;
    }
    
    // Append message to container
    function appendMessage(message) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message-bubble ${message.is_mine ? 'message-bubble--mine' : 'message-bubble--theirs'} animate-fade-in-up`;
        
        if (message.is_mine) {
            messageDiv.innerHTML = `
                <div class="bg-gradient-to-tr from-blue-500 to-blue-600 text-white rounded-2xl rounded-tr-sm shadow-md px-4 py-2.5">
                    <p class="text-sm whitespace-pre-wrap">${escapeHtml(message.body)}</p>
                    <p class="text-xs text-blue-100 mt-1 opacity-75">${message.created_at_human}</p>
                </div>
            `;
        } else {
            messageDiv.innerHTML = `
                <div class="bg-gray-100 text-gray-800 rounded-2xl rounded-tl-sm px-4 py-2.5">
                    <p class="text-sm whitespace-pre-wrap">${escapeHtml(message.body)}</p>
                    <p class="text-xs text-gray-500 mt-1">${message.created_at_human}</p>
                </div>
            `;
        }
        
        messagesContainer.appendChild(messageDiv);
    }
    
    // Send message
    messageForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const messageBody = messageInput.value.trim();
        if (!messageBody || !currentConversationId) return;
        
        // Optimistic UI: Add message immediately
        const tempMessage = {
            id: 'temp-' + Date.now(),
            body: messageBody,
            is_mine: true,
            created_at_human: '刚刚',
        };
        appendMessage(tempMessage);
        messageInput.value = '';
        scrollToBottom();
        
        // Disable send button
        sendButton.disabled = true;
        
        try {
            const response = await fetch(`/chat/${currentConversationId}/message`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ body: messageBody })
            });
            
            if (!response.ok) throw new Error('Failed to send message');
            
            const data = await response.json();
            
            // Replace temp message with real one
            const tempDiv = messagesContainer.lastElementChild;
            if (tempDiv) {
                tempDiv.remove();
                appendMessage(data.message);
                scrollToBottom();
            }
            
        } catch (error) {
            console.error('Error sending message:', error);
            // Remove optimistic message on error
            const tempDiv = messagesContainer.lastElementChild;
            if (tempDiv && tempDiv.querySelector('[id^="temp-"]')) {
                tempDiv.remove();
            }
            alert('发送消息失败，请重试');
        } finally {
            sendButton.disabled = false;
            messageInput.focus();
        }
    });
    
    // Poll for new messages
    function startPolling() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
        }
        
        pollingInterval = setInterval(async () => {
            if (!currentConversationId) return;
            
            try {
                const response = await fetch(`/chat/${currentConversationId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });
                
                if (!response.ok) return;
                
                const data = await response.json();
                const currentMessageIds = Array.from(messagesContainer.children)
                    .map(el => el.dataset.messageId)
                    .filter(Boolean);
                
                // Add new messages
                data.messages.forEach(message => {
                    if (!currentMessageIds.includes(String(message.id))) {
                        appendMessage(message);
                    }
                });
                
                // Scroll to bottom if new messages were added
                if (data.messages.length > currentMessageIds.length) {
                    scrollToBottom();
                }
                
            } catch (error) {
                console.error('Error polling messages:', error);
            }
        }, 3000); // Poll every 3 seconds
    }
    
    // Stop polling when leaving page
    window.addEventListener('beforeunload', function() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
        }
    });
    
    // Scroll to bottom
    function scrollToBottom() {
        setTimeout(() => {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }, 100);
    }
    
    // Escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Handle Buy Now button (global function for onclick)
    window.handleBuyNow = function(productId, isOwner) {
        if (isOwner) {
            // Mark as sold logic
            if (confirm('确定要标记为已售吗？')) {
                // TODO: Implement mark as sold
                alert('标记已售功能待实现');
            }
        } else {
            // Create order logic
            if (confirm('确定要购买此商品吗？')) {
                // TODO: Implement create order
                alert('购买功能待实现');
            }
        }
    };
    
    // Auto-focus input when chat window is shown
    const observer = new MutationObserver(function(mutations) {
        if (!chatWindow.classList.contains('hidden')) {
            messageInput.focus();
        }
    });
    
    observer.observe(chatWindow, {
        attributes: true,
        attributeFilter: ['class']
    });
});
</script>
@endpush
@endsection

