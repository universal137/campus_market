@extends('layouts.app')

@section('title', $item->title . ' - 闲置详情')

@section('content')
    <section class="surface">
        <a href="{{ route('items.index') }}" style="font-size:13px;color:#64748b;text-decoration:none;">
            ← 返回全部闲置
        </a>
        <div style="display:flex;flex-wrap:wrap;gap:32px;margin-top:18px;">
            <!-- 左侧：图片/宣传图 -->
            <div style="flex:1 1 260px;min-width:240px;">
                @if($item->image)
                    <img 
                        src="{{ $item->image }}" 
                        alt="{{ $item->title }}"
                        class="w-full h-[400px] rounded-3xl shadow-lg object-cover"
                        style="margin-bottom:14px;"
                    >
                @else
                    <div class="w-full h-[400px] rounded-3xl shadow-lg bg-gray-100 flex items-center justify-center text-gray-400 text-sm" style="margin-bottom:14px;">
                        商品图片占位（可在后续接入上传功能）
                    </div>
                @endif
            </div>

            <!-- 右侧：关键信息 + 卖家信息 + 操作按钮 -->
            <div style="flex:2 1 320px;min-width:260px;display:flex;flex-direction:column;gap:16px;">
                <div>
                    <h1 style="font-size:22px;font-weight:600;margin:0 0 10px;">
                        {{ $item->title }}
                    </h1>
                    <p style="font-size:22px;font-weight:700;color:#ef4444;margin:0 0 6px;">
                        ¥{{ $item->price }}
                    </p>
                    <p style="color:#64748b;font-size:13px;margin:0 0 10px;">
                        分类：{{ optional($item->category)->name ?? '未分类' }}
                        ｜ 状态：{{ $item->status === 'on_sale' ? '在售中' : '已售出' }}
                    </p>
                    @if ($item->deal_place)
                        <p style="color:#475569;font-size:13px;margin:0 0 10px;">
                            建议交易地点：{{ $item->deal_place }}
                        </p>
                    @else
                        <p style="color:#475569;font-size:13px;margin:0 0 10px;">
                            交易地点待与卖家协商，建议选择校园公共区域当面验货。
                        </p>
                    @endif
                    <span class="status-pill" style="margin-bottom:4px;display:inline-block;">
                        校园闲置 · 面对面交易更安心
                    </span>
                </div>

                <!-- 卖家信息区 -->
                <div style="border:1px solid #e2e8f0;border-radius:14px;padding:12px 14px;display:flex;align-items:center;gap:12px;">
                    <div style="width:40px;height:40px;border-radius:999px;background:#eff6ff;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-weight:600;">
                        {{ mb_substr($item->user->name, 0, 1) }}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p style="margin:0 0 4px;font-weight:600;font-size:14px;color:#0f172a;">
                            {{ $item->user->name }}
                            <span style="font-size:11px;color:#16a34a;border-radius:999px;background:#dcfce7;padding:2px 8px;margin-left:6px;">
                                已认证学生（示例）
                            </span>
                        </p>
                        <p style="margin:0;color:#94a3b8;font-size:12px;">
                            校园信誉：⭐ 4.9 · 23 次成功交易（示例文案）
                        </p>
                    </div>
                </div>

                <!-- 商品描述 -->
                <div style="color:#334155;font-size:14px;line-height:1.8;white-space:pre-line;">
                    {{ $item->description }}
                </div>

                <!-- 底部操作栏（可做成悬浮） -->
                <div style="margin-top:4px;display:flex;flex-wrap:wrap;gap:10px;">
                    @auth
                        @if(auth()->id() !== $item->user_id)
                            <button 
                                type="button" 
                                id="buy-btn"
                                class="btn btn-primary" 
                                style="flex:1 1 160px;min-width:140px;position:relative;"
                                onclick="startChat({{ $item->user_id }}, {{ $item->id }}, 'product', this)"
                            >
                                <span id="buy-btn-text">我想要购买</span>
                                <svg id="buy-btn-spinner" class="hidden absolute inset-0 m-auto w-5 h-5 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        @else
                            <button 
                                type="button" 
                                class="btn btn-secondary" 
                                style="flex:1 1 160px;min-width:140px;background:#f3f4f6;color:#6b7280;border:none;cursor:not-allowed;"
                                disabled
                            >
                                这是您发布的商品
                            </button>
                        @endif
                    @else
                        <a 
                            href="{{ route('login') }}"
                            class="btn btn-primary"
                            style="flex:1 1 160px;min-width:140px;text-decoration:none;display:flex;align-items:center;justify-content:center;"
                        >
                            我想要购买
                        </a>
                    @endauth
                    @auth
                        <button 
                            type="button" 
                            id="wishlist-btn"
                            onclick="toggleWishlist({{ $item->id }}, this, 'product')"
                            class="wishlist-detail-btn"
                            style="flex:1 1 130px;min-width:120px;padding:12px 20px;border-radius:999px;font-weight:500;font-size:14px;cursor:pointer;{{ $item->isLikedBy(auth()->user()) ? 'background:#fee2e2;color:#991b1b;' : 'background:#f3f4f6;color:#374151;' }}border:none;transition:all 0.2s ease;"
                            data-item-id="{{ $item->id }}"
                            data-liked="{{ $item->isLikedBy(auth()->user()) ? 'true' : 'false' }}"
                        >
                            <span id="wishlist-text">{{ $item->isLikedBy(auth()->user()) ? '已在心愿单 ❤️' : '加入心愿单' }}</span>
                        </button>
                    @else
                        <a 
                            href="{{ route('login') }}"
                            class="btn btn-secondary"
                            style="flex:1 1 130px;min-width:120px;background:#f3f4f6;color:#374151;border:none;text-decoration:none;display:flex;align-items:center;justify-content:center;transition:all 0.2s ease;padding:12px 20px;border-radius:999px;font-weight:500;font-size:14px;"
                        >
                            加入心愿单
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-8 left-1/2 transform -translate-x-1/2 z-50 pointer-events-none">
        <div id="toast" class="bg-black/80 text-white px-6 py-3 rounded-full shadow-lg opacity-0 translate-y-4 transition-all duration-300 ease-out pointer-events-auto">
            <span id="toast-message"></span>
        </div>
    </div>

    <style>
        .wishlist-detail-btn:hover {
            background: #e5e7eb !important;
            transform: translateY(-1px);
        }
        .wishlist-detail-btn[data-liked="true"]:hover {
            background: #fecaca !important;
        }
    </style>

    <script>
        /**
         * Toggle Wishlist with Optimistic UI Update (Supports both Products and Tasks)
         * @param {number} id - The item/task ID
         * @param {HTMLElement} btnElement - The button element that was clicked
         * @param {string} type - The type: 'product' or 'task'
         */
        function toggleWishlist(id, btnElement, type = 'product') {
            const wishlistText = document.getElementById('wishlist-text');
            const isLiked = btnElement.getAttribute('data-liked') === 'true';
            
            // Determine endpoint and text based on type
            const endpoint = type === 'task' ? `/wishlist/task/${id}` : `/wishlist/${id}`;
            const likedText = type === 'task' ? '已关注任务 ❤️' : '已在心愿单 ❤️';
            const unlikedText = type === 'task' ? '关注此任务' : '加入心愿单';
            const addedToast = type === 'task' ? '已关注任务' : '已添加到心愿单';
            const removedToast = type === 'task' ? '已取消关注' : '已取消收藏';
            
            // 1. Optimistic Update - Immediately toggle the text and styling
            if (isLiked) {
                wishlistText.textContent = unlikedText;
                btnElement.setAttribute('data-liked', 'false');
                btnElement.style.background = '#f3f4f6';
                btnElement.style.color = '#374151';
            } else {
                wishlistText.textContent = likedText;
                btnElement.setAttribute('data-liked', 'true');
                btnElement.style.background = '#fee2e2';
                btnElement.style.color = '#991b1b';
            }
            
            // 2. Trigger Animation - Button scale effect (bounce)
            btnElement.style.transform = 'scale(0.95)';
            setTimeout(() => {
                btnElement.style.transform = 'scale(1)';
            }, 150);
            
            // 3. Server Request
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            
            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Show toast notification
                showToast(isLiked ? removedToast : addedToast);
            })
            .catch(error => {
                console.error('Error:', error);
                // Revert optimistic update on error
                if (isLiked) {
                    wishlistText.textContent = likedText;
                    btnElement.setAttribute('data-liked', 'true');
                    btnElement.style.background = '#fee2e2';
                    btnElement.style.color = '#991b1b';
                } else {
                    wishlistText.textContent = unlikedText;
                    btnElement.setAttribute('data-liked', 'false');
                    btnElement.style.background = '#f3f4f6';
                    btnElement.style.color = '#374151';
                }
                showToast('操作失败，请重试');
            });
        }

        /**
         * Show Toast Notification
         * @param {string} message - The message to display
         */
        function showToast(message) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            
            toastMessage.textContent = message;
            
            // Reset and show
            toast.classList.remove('opacity-0', 'translate-y-4');
            toast.classList.add('opacity-100', 'translate-y-0');
            
            // Hide after 2 seconds
            setTimeout(() => {
                toast.classList.remove('opacity-100', 'translate-y-0');
                toast.classList.add('opacity-0', 'translate-y-4');
            }, 2000);
        }

        /**
         * Start Chat - Apple-style interaction with loading state
         * @param {number} receiverId - The seller's/user's ID
         * @param {number} itemId - The product or task ID
         * @param {string} type - The type: 'product' or 'task'
         * @param {HTMLElement} buttonElement - The button element that was clicked
         */
        async function startChat(receiverId, itemId, type, buttonElement) {
            if (!receiverId || !itemId || !type) {
                showToast('参数错误，请重试');
                return;
            }

            // Get button elements
            const buttonText = buttonElement.querySelector('#buy-btn-text') || buttonElement.querySelector('#contact-btn-text') || buttonElement;
            const spinner = buttonElement.querySelector('#buy-btn-spinner') || buttonElement.querySelector('#contact-btn-spinner');
            
            // Store original state
            const originalText = buttonText.textContent;
            const originalDisabled = buttonElement.disabled;

            // Disable button and show loading state
            buttonElement.disabled = true;
            buttonElement.classList.add('opacity-75', 'cursor-wait');
            
            if (buttonText && buttonText !== buttonElement) {
                buttonText.style.display = 'none';
            }
            if (spinner) {
                spinner.classList.remove('hidden');
            } else {
                // If no spinner exists, create one or show text
                buttonText.textContent = '正在连接...';
            }

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                
                // Prepare request body
                const requestBody = {
                    receiver_id: receiverId
                };
                
                if (type === 'product') {
                    requestBody.product_id = itemId;
                } else if (type === 'task') {
                    requestBody.task_id = itemId;
                }

                const response = await fetch('/conversations/start', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestBody)
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || '无法连接到卖家');
                }

                if (data.status === 'success' && data.redirect_url) {
                    // Redirect to chat page
                    window.location.href = data.redirect_url;
                } else {
                    throw new Error('响应格式错误');
                }
            } catch (error) {
                console.error('Error starting conversation:', error);
                
                // Show error toast
                showToast('无法连接到卖家');
                
                // Reset button state
                buttonElement.disabled = originalDisabled;
                buttonElement.classList.remove('opacity-75', 'cursor-wait');
                
                if (buttonText && buttonText !== buttonElement) {
                    buttonText.style.display = '';
                    buttonText.textContent = originalText;
                } else {
                    buttonText.textContent = originalText;
                }
                
                if (spinner) {
                    spinner.classList.add('hidden');
                }
            }
        }
    </script>
@endsection


