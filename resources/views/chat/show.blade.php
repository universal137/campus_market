@extends('layouts.app')

@section('content')
@php
    $chatProduct = $conversation->product;
    $productUiStatus = null;
    if ($chatProduct) {
        $productUiStatus = match ($chatProduct->status) {
            'active', 'on_sale' => 'active',
            'pending' => 'pending',
            default => 'sold',
        };
    }
@endphp
<div class="h-[calc(100vh-64px)] bg-white flex overflow-hidden">
    
    <div class="w-80 border-r border-gray-100 bg-gray-50 flex flex-col shrink-0">
        <div class="p-5 border-b border-gray-100 bg-white">
            <h2 class="font-bold text-xl text-gray-800">消息列表</h2>
        </div>
        <div class="flex-1 overflow-y-auto custom-scrollbar p-2 space-y-1">
            @foreach($allConversations as $chat)
                <div id="chat-item-{{ $chat['id'] }}"
                     onclick="switchChat(event, {{ $chat['id'] }})" 
                     data-chat-id="{{ $chat['id'] }}"
                     class="chat-item group relative flex items-center p-3 rounded-xl transition-all duration-500 ease-out cursor-pointer {{ $chat['id'] == $conversation->id ? 'bg-white shadow-md ring-1 ring-black/5' : 'hover:bg-white/60' }}">
                    
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
                    
                    <button onclick="event.stopPropagation(); confirmDelete({{ $chat['id'] }})" class="absolute right-2 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 translate-x-4 group-hover:translate-x-0 transition-all duration-300 p-2 bg-white rounded-full shadow-md text-red-500 hover:bg-red-50 hover:scale-110 active:scale-90 cursor-pointer z-10">
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
            <div id="chat-header-product" class="flex items-center justify-between gap-3 w-full">
            @if($conversation->product)
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gray-100 overflow-hidden">
                    @php
                        $productImage = $conversation->product->image_url ?? asset('images/placeholder-product.png');
                    @endphp
                    <img src="{{ $productImage }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='{{ asset('images/placeholder-product.png') }}';">
                </div>
                <div>
                    <div class="text-sm font-bold text-gray-900">正在沟通: {{ $conversation->product->title }}</div>
                    <div class="text-xs text-red-500 font-bold">¥{{ $conversation->product->price }}</div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('items.show', $conversation->product->id) }}" class="px-4 py-1.5 bg-blue-50 text-blue-600 text-xs rounded-full font-bold hover:bg-blue-100 transition">查看详情</a>
                @if($productUiStatus === 'active')
                    <button onclick="openPaymentModal({{ $conversation->product->id }})" class="px-4 py-1.5 bg-blue-600 text-white text-xs rounded-full font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">立即购买</button>
                @elseif($productUiStatus === 'pending')
                    <button disabled class="px-4 py-1.5 bg-orange-100 text-orange-600 text-xs rounded-full font-bold cursor-not-allowed">交易进行中</button>
                @else
                    <button disabled class="px-4 py-1.5 bg-gray-100 text-gray-400 text-xs rounded-full font-bold cursor-not-allowed">已售出</button>
                @endif
            </div>
            @endif
            </div>
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

@if($conversation->product)
<div id="cashier-modal" class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 opacity-0 pointer-events-none transition-opacity duration-300">
    <div id="cashier-card" class="w-full max-w-md mx-4 bg-white rounded-3xl shadow-2xl overflow-hidden transform scale-95 opacity-0 translate-y-6 transition duration-300">
        <div class="flex justify-end p-4">
            <button id="close-cashier-btn" class="w-9 h-9 rounded-full bg-gray-100 text-gray-400 hover:text-gray-600 flex items-center justify-center transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div id="cashier-body" class="px-8 pb-10 -mt-4 space-y-6">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-gray-400 mb-3">Cashier Desk</p>
                <h3 class="text-2xl font-bold text-gray-900 mb-6">确认订单信息</h3>
                <div class="flex items-center gap-4 p-4 rounded-2xl border border-gray-100 bg-gray-50/60">
                    <div class="w-16 h-16 rounded-2xl overflow-hidden bg-gray-200">
                        <img src="{{ $productImage }}" class="w-full h-full object-cover" alt="product image" data-cashier-image data-placeholder="{{ asset('images/placeholder-product.png') }}" onerror="this.onerror=null; this.src='{{ asset('images/placeholder-product.png') }}';">
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900 line-clamp-2" data-cashier-title>{{ $conversation->product->title }}</p>
                        <p class="text-lg font-bold text-gray-900 mt-1" data-cashier-price>¥{{ $conversation->product->price }}</p>
                    </div>
                </div>
            </div>

            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase mb-3 tracking-wider">选择支付方式</p>
                <div class="grid grid-cols-3 gap-3">
                    <button type="button" class="payment-card" data-payment-card data-method="wechat">
                        <div class="icon-circle wechat-icon">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18.946 10.618c.197-.493.305-.998.305-1.511C19.251 6.501 16.486 4 12.999 4 9.514 4 6.75 6.5 6.75 9.107c0 2.604 2.764 4.105 6.249 4.105.712 0 1.396-.07 2.045-.2l2.336 1.408-.434-1.802zM9.5 8.5c-.414 0-.75-.336-.75-.75s.336-.75.75-.75.75.336.75.75-.336.75-.75.75zm5 0c-.414 0-.75-.336-.75-.75s.336-.75.75-.75.75.336.75.75-.336.75-.75.75zM21.5 15.5c0-1.071-.682-2.05-1.804-2.697C20.443 13.422 20.75 14.447 20.75 15.5c0 1.495-.699 2.865-1.915 3.894l.603 2.506-3.249-1.958a8.639 8.639 0 01-2.19.274c-3.486 0-6.25-1.501-6.25-4.107 0-1.282.676-2.12 1.985-2.694-.122.37-.189.757-.189 1.154 0 2.606 2.764 4.107 6.25 4.107.712 0 1.396-.07 2.045-.201l3.397 1.985-.437-1.88c1.03-.778 1.6-1.8 1.6-2.973z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold">微信支付</span>
                    </button>

                    <button type="button" class="payment-card" data-payment-card data-method="alipay">
                        <div class="icon-circle alipay-icon">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M4 4h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm6.79 10.64c.8.29 1.52.51 2.16.67.86-.64 1.65-1.39 2.34-2.22-.32-.11-.64-.23-.98-.36l-.62 1.05a12.2 12.2 0 01-2.54-.77l-.36.63zm5.25-6.45c.29 0 .52.23.52.52a.525.525 0 01-.52.53h-2.98v1.07h2.54c.29 0 .53.23.53.52 0 .3-.24.53-.53.53h-2.54V13h-1.35v-1.64H10.2c-.29 0-.53-.23-.53-.53 0-.29.24-.52.53-.52h1.65V9.35H9.96c-.29 0-.52-.24-.52-.53a.52.52 0 01.52-.52h2V7h1.35v1.09h2.73z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold">支付宝</span>
                    </button>

                    <button type="button" class="payment-card" data-payment-card data-method="offline">
                        <div class="icon-circle offline-icon">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M4 5h16v2H4zm0 4h16v10H4zm2 2v6h12v-6z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold">线下交易</span>
                    </button>
                </div>
            </div>

            <button id="confirm-payment-btn" class="w-full py-3 rounded-full bg-black text-white font-semibold text-sm tracking-wide shadow-lg shadow-black/10 hover:shadow-black/20 transition disabled:opacity-60 disabled:cursor-not-allowed">
                确认付款
            </button>
        </div>

        <div id="cashier-success" class="hidden flex flex-col items-center justify-center gap-4 px-8 pb-12 pt-4">
            <div class="w-32 h-32 flex items-center justify-center rounded-full bg-emerald-50">
                <svg class="w-20 h-20 text-emerald-500" viewBox="0 0 52 52">
                    <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
                    <path class="checkmark-check" fill="none" d="M16 26l7 7 14-16"/>
                </svg>
            </div>
            <div class="text-center space-y-1">
                <p class="text-lg font-semibold text-gray-900">支付成功</p>
                <p class="text-sm text-gray-500">正在跳转订单详情...</p>
            </div>
        </div>
    </div>
</div>
@endif

<div id="deleteModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    <div id="deleteCard" class="relative bg-white rounded-2xl shadow-2xl p-8 w-full max-w-sm transform transition-all duration-300 scale-95 opacity-0">
        <div class="w-14 h-14 rounded-full bg-red-50 text-red-500 flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 text-center mb-2">删除对话？</h3>
        <p class="text-sm text-gray-500 text-center mb-6">删除后将无法恢复。</p>
        <div class="flex items-center gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 py-3 rounded-full border border-gray-200 text-gray-600 font-semibold hover:bg-gray-50 transition">取消</button>
            <button onclick="executeDelete()" class="flex-1 py-3 rounded-full bg-red-500 text-white font-semibold hover:bg-red-600 transition">删除</button>
        </div>
    </div>
</div>
<div id="toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-sm font-semibold px-4 py-2 rounded-full shadow-lg opacity-0 pointer-events-none transition-all duration-300 translate-y-2"></div>

<script>
    let conversationId = {{ $conversation->id }};
    const currentUserId = {{ auth()->id() }};
    const messagesArea = document.getElementById('messages-area');
    const input = document.getElementById('message-input');
    const productHeader = document.getElementById('product-header');
    const deleteModal = document.getElementById('deleteModal');
    const deleteCard = document.getElementById('deleteCard');
    const toastBar = document.getElementById('toast');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    let targetDeleteId = null;
    let toastTimer = null;
    @php
        $initialProductDataPayload = $conversation->product ? [
            'id' => $conversation->product->id,
            'title' => $conversation->product->title,
            'price' => $conversation->product->price,
            'image_url' => $conversation->product->image_url ?? $conversation->product->image,
            'status' => $productUiStatus ?? 'active',
        ] : null;
    @endphp
    const initialProductData = @json($initialProductDataPayload);
    let pollingInterval = null;

    renderHeader(initialProductData);

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

            if (data.conversation && data.conversation.product) {
                renderHeader(data.conversation.product);
            }
        })
        .catch(err => {
            console.error('Error fetching messages:', err);
        });

    // 2. Render Logic (Smart Avatar)
    function appendMessage(msg) {
        const isMe = msg.user_id === currentUserId || msg.is_me;
        
        const safeBody = typeof msg.body === 'string' ? msg.body : '';
        const safeTimeAgo = (msg.time_ago && msg.time_ago !== 'undefined')
            ? msg.time_ago
            : (msg.created_at ? new Date(msg.created_at).toLocaleString('zh-CN', { hour12: false }) : '刚刚');
        
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
                    ${escapeHtml(safeBody)}
                </div>
                <span class="text-[10px] text-gray-400 mt-1 mx-1">${safeTimeAgo}</span>
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

    function renderHeader(product) {
        const headerContainer = document.getElementById('chat-header-product');
        if (!productHeader || !headerContainer) return;

        if (!product) {
            headerContainer.innerHTML = '';
            productHeader.style.display = 'none';
            return;
        }

        const imageSrc = product.image_url || product.image || '{{ asset('images/placeholder-product.png') }}';
        const status = product.status || 'active';
        console.log('Product Status:', status);
        let btnHtml = '';
        if (status === 'active') {
            btnHtml = `<button onclick="openPaymentModal(${product.id})" class="px-4 py-1.5 bg-blue-600 text-white text-xs rounded-full font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">立即购买</button>`;
        } else if (status === 'pending') {
            btnHtml = `<button disabled class="px-4 py-1.5 bg-orange-100 text-orange-600 text-xs rounded-full font-bold cursor-not-allowed">交易进行中</button>`;
        } else {
            btnHtml = `<button disabled class="px-4 py-1.5 bg-gray-100 text-gray-400 text-xs rounded-full font-bold cursor-not-allowed">已售出</button>`;
        }

        headerContainer.innerHTML = `
            <div class="flex items-center gap-3">
                <img src="${imageSrc}" class="w-10 h-10 rounded-lg object-cover bg-gray-100" onerror="this.onerror=null; this.src='{{ asset('images/placeholder-product.png') }}';">
                <div>
                    <div class="text-sm font-bold text-gray-900">正在沟通: ${escapeHtml(product.title || '')}</div>
                    <div class="text-xs text-red-500 font-bold">¥${product.price ?? '--'}</div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="/items/${product.id}" class="px-4 py-1.5 bg-blue-50 text-blue-600 text-xs rounded-full font-bold hover:bg-blue-100 transition">查看详情</a>
                ${btnHtml}
            </div>
        `;
        productHeader.style.display = 'flex';
    }

    // Update Product Header
    function updateProductHeader(conversation) {
        if (conversation && conversation.product) {
            const normalizedProduct = {
                id: conversation.product.id,
                title: conversation.product.title,
                price: conversation.product.price,
                image: conversation.product.image_url || conversation.product.image || '{{ asset('images/placeholder-product.png') }}',
                status: conversation.product.status || 'active',
            };

            renderHeader({
                id: normalizedProduct.id,
                title: normalizedProduct.title,
                price: normalizedProduct.price,
                image_url: normalizedProduct.image,
                status: normalizedProduct.status
            });
            updateCashierProductDetails(normalizedProduct);
        } else {
            renderHeader(null);
        }
    }

    // Delete Chat Function
    function confirmDelete(id) {
        targetDeleteId = id;
        if (deleteModal && deleteCard) {
            deleteModal.classList.remove('hidden');
            requestAnimationFrame(() => {
                deleteCard.classList.remove('scale-95', 'opacity-0');
                deleteCard.classList.add('scale-100', 'opacity-100');
            });
        }
    }

    function closeDeleteModal(resetTarget = true) {
        if (deleteModal && deleteCard) {
            deleteCard.classList.add('scale-95', 'opacity-0');
            deleteCard.classList.remove('scale-100', 'opacity-100');
            setTimeout(() => {
                deleteModal.classList.add('hidden');
            }, 200);
        }
        if (resetTarget) {
            targetDeleteId = null;
        }
    }

    function executeDelete() {
        if (!targetDeleteId) return;
        const deleteId = targetDeleteId;

        fetch(`/conversations/${deleteId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) {
                throw new Error('网络异常，请稍后再试');
            }
            return res.json();
        })
        .then(data => {
            if (data.status === 'success') {
                closeDeleteModal(false);
                const chatItem = document.getElementById(`chat-item-${deleteId}`);
                if (chatItem) {
                    chatItem.style.transition = 'all 0.5s ease';
                    chatItem.style.transform = 'translateX(-100%)';
                    chatItem.style.opacity = '0';
                    setTimeout(() => chatItem.remove(), 500);
                }

                if (String(deleteId) === String(conversationId)) {
                    clearInterval(pollingInterval);
                    const remainingChats = Array.from(document.querySelectorAll('.chat-item'))
                        .filter(item => item.id !== `chat-item-${deleteId}`);
                    if (remainingChats.length > 0) {
                        window.location.href = `/chat/${remainingChats[0].dataset.chatId}`;
                    } else {
                        window.location.href = '{{ route("chat.index") }}';
                    }
                }
                targetDeleteId = null;
            } else {
                showToast(data.message || '删除失败，请重试');
            }
        })
        .catch(err => {
            console.error('Error deleting chat:', err);
            showToast(err.message || '删除失败，请重试');
        });
    }

    function showToast(message) {
        if (!toastBar) return;
        toastBar.textContent = message;
        toastBar.classList.remove('opacity-0', 'translate-y-2');
        toastBar.classList.add('opacity-100', 'translate-y-0');
        if (toastTimer) {
            clearTimeout(toastTimer);
        }
        toastTimer = setTimeout(() => {
            toastBar.classList.remove('opacity-100', 'translate-y-0');
            toastBar.classList.add('opacity-0', 'translate-y-2');
        }, 2500);
    }

    deleteModal?.addEventListener('click', (event) => {
        if (event.target === deleteModal) {
            closeDeleteModal();
        }
    });

    // Cashier Desk Logic
    const cashierModal = document.getElementById('cashier-modal');
    const cashierCard = document.getElementById('cashier-card');
    const closeCashierBtn = document.getElementById('close-cashier-btn');
    const paymentCardElements = document.querySelectorAll('[data-payment-card]');
    const confirmPaymentBtn = document.getElementById('confirm-payment-btn');
    const cashierBody = document.getElementById('cashier-body');
    const cashierSuccess = document.getElementById('cashier-success');
    const cashierProductTitle = document.querySelector('[data-cashier-title]');
    const cashierProductPrice = document.querySelector('[data-cashier-price]');
    const cashierProductImage = document.querySelector('[data-cashier-image]');
    const cashierImagePlaceholder = cashierProductImage && cashierProductImage.dataset && cashierProductImage.dataset.placeholder
        ? cashierProductImage.dataset.placeholder
        : "{{ asset('images/placeholder-product.png') }}";
    @php
        $initialProductPayload = $conversation->product ? [
            'id' => $conversation->product->id,
            'title' => $conversation->product->title,
            'price' => $conversation->product->price,
            'image' => $conversation->product->image_url,
            'status' => $productUiStatus ?? 'active',
        ] : null;
    @endphp
    const orderRedirectUrl = '/orders';
    const paymentEndpoint = "{{ route('orders.create') }}";
    const productPayload = @json($initialProductPayload);
    let activeProductPayload = productPayload || null;
    let activeProductId = activeProductPayload?.id || null;
    let selectedPaymentMethod = 'wechat';
    let paymentInFlight = false;

    function updateCashierProductDetails(product) {
        if (!product) return;

        activeProductPayload = {
            id: product.id,
            title: product.title,
            price: product.price,
            image: product.image || cashierImagePlaceholder,
        };
        activeProductId = activeProductPayload.id;

        if (cashierProductTitle) {
            cashierProductTitle.textContent = activeProductPayload.title || '未命名商品';
        }

        if (cashierProductPrice) {
            const priceValue = activeProductPayload.price;
            const formattedPrice = priceValue !== undefined && priceValue !== null && !Number.isNaN(Number(priceValue))
                ? Number(priceValue).toFixed(2)
                : (priceValue ?? '--');
            cashierProductPrice.textContent = `¥${formattedPrice}`;
        }

        if (cashierProductImage && activeProductPayload.image) {
            cashierProductImage.src = activeProductPayload.image;
        }
    }

    function openPaymentModal(productId) {
        if (productId !== undefined && productId !== null) {
            activeProductId = productId;
        }

        if (!activeProductId) {
            alert('未找到商品信息，请稍后再试');
            return;
        }

        toggleCashier(true);
    }

    function toggleCashier(open) {
        if (!cashierModal) return;
        if (open) {
            cashierModal.classList.remove('pointer-events-none', 'opacity-0');
            cashierModal.classList.add('opacity-100');
            cashierCard.classList.remove('translate-y-6', 'opacity-0', 'scale-95');
            cashierCard.classList.add('scale-100', 'opacity-100', 'translate-y-0');
        } else {
            cashierModal.classList.add('pointer-events-none', 'opacity-0');
            cashierModal.classList.remove('opacity-100');
            cashierCard.classList.add('scale-95', 'opacity-0', 'translate-y-6');
            cashierCard.classList.remove('scale-100', 'opacity-100', 'translate-y-0');
            setTimeout(() => {
                cashierBody?.classList.remove('hidden');
                cashierSuccess?.classList.add('hidden');
            }, 300);
        }
    }

    function setSelectedCard(method) {
        paymentCardElements.forEach(card => {
            const isSelected = card.dataset.method === method;
            card.classList.toggle('selected', isSelected);
        });
        selectedPaymentMethod = method;
    }

    async function submitPayment() {
        if (paymentInFlight) return;
        if (!activeProductId) {
            showToast('未找到商品信息，请刷新页面后重试');
            return;
        }
        paymentInFlight = true;
        confirmPaymentBtn.disabled = true;
        confirmPaymentBtn.textContent = '支付中...';

        try {
            const response = await fetch(paymentEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    product_id: activeProductId,
                    payment_method: selectedPaymentMethod,
                }),
            });

            const result = await response.json();
            if (response.ok && result.status === 'success') {
                showPaymentSuccess();
            } else {
                throw new Error(result.message || '支付失败，请稍后再试');
            }
        } catch (error) {
            showToast(error.message || '支付失败，请稍后再试', 'error');
            triggerModalShake();
        } finally {
            paymentInFlight = false;
            confirmPaymentBtn.disabled = false;
            confirmPaymentBtn.textContent = '确认付款';
        }
    }

    function showPaymentSuccess() {
        if (!cashierBody || !cashierSuccess) return;
        cashierBody.classList.add('hidden');
        cashierSuccess.classList.remove('hidden');
        setTimeout(() => {
            window.location.href = orderRedirectUrl;
        }, 1500);
    }

    setSelectedCard(selectedPaymentMethod);
    if (activeProductPayload) {
        updateCashierProductDetails(activeProductPayload);
    }

    closeCashierBtn?.addEventListener('click', () => toggleCashier(false));
    cashierModal?.addEventListener('click', (event) => {
        if (event.target === cashierModal) {
            toggleCashier(false);
        }
    });

    paymentCardElements.forEach(card => {
        card.addEventListener('click', () => setSelectedCard(card.dataset.method));
    });

    confirmPaymentBtn?.addEventListener('click', submitPayment);

    let toastTimeout = null;
    function showToast(message, type = 'error') {
        if (!message) return;
        if (toastTimeout) {
            clearTimeout(toastTimeout);
        }
        let toast = document.getElementById('global-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'global-toast';
            toast.className = 'fixed bottom-10 left-1/2 -translate-x-1/2 z-50 px-6 py-3 rounded-full text-sm font-semibold text-white shadow-xl backdrop-blur bg-black/90 flex items-center gap-2 opacity-0 transition-all duration-300';
            document.body.appendChild(toast);
        }
        toast.textContent = message;
        toast.classList.remove('opacity-0', 'translate-y-4');
        toast.classList.add('opacity-100');
        toastTimeout = setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-4');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    function triggerModalShake() {
        if (!cashierCard) return;
        cashierCard.classList.remove('animate-shake');
        void cashierCard.offsetWidth;
        cashierCard.classList.add('animate-shake');
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

    .payment-card {
        border: 1px solid rgba(229, 231, 235, 1);
        border-radius: 1.25rem;
        background: #fff;
        padding: 1.25rem 0.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: #374151;
        box-shadow: 0 5px 20px -15px rgba(0,0,0,0.25);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .payment-card:hover {
        box-shadow: 0 12px 30px -16px rgba(0,0,0,0.35);
        transform: translateY(-2px);
    }

    .payment-card.selected {
        border: 2px solid #000;
        box-shadow: 0 20px 40px -18px rgba(0,0,0,0.4);
    }

    .icon-circle {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .wechat-icon {
        background: #ecfdf5;
        color: #059669;
    }

    .alipay-icon {
        background: #eff6ff;
        color: #2563eb;
    }

    .offline-icon {
        background: #f3f4f6;
        color: #4b5563;
    }

    .payment-card.selected .wechat-icon {
        background: #059669;
        color: #fff;
    }

    .payment-card.selected .alipay-icon {
        background: #2563eb;
        color: #fff;
    }

    .payment-card.selected .offline-icon {
        background: #111827;
        color: #fff;
    }

    .checkmark-circle {
        stroke: rgba(16, 185, 129, 0.3);
        stroke-width: 4;
        stroke-dasharray: 160;
        stroke-dashoffset: 160;
        animation: drawCircle 0.8s ease forwards;
    }

    .checkmark-check {
        stroke: #10B981;
        stroke-width: 4;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-dasharray: 36;
        stroke-dashoffset: 36;
        animation: drawCheck 0.5s ease 0.6s forwards;
    }

    @keyframes drawCircle {
        to { stroke-dashoffset: 0; }
    }

    @keyframes drawCheck {
        to { stroke-dashoffset: 0; }
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20% { transform: translateX(-6px); }
        40% { transform: translateX(6px); }
        60% { transform: translateX(-4px); }
        80% { transform: translateX(4px); }
    }
    .animate-shake {
        animation: shake 0.4s ease;
    }
</style>
@endsection

