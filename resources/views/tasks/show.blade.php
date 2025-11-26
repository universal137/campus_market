@extends('layouts.app')

@section('title', $task->title . ' - 互助任务详情')

@section('content')
    <section class="surface">
        <a href="{{ route('tasks.index') }}" style="font-size:13px;color:#64748b;text-decoration:none;">
            ← 返回互助广场
        </a>

        <div style="margin-top:18px;display:flex;flex-wrap:wrap;gap:28px;">
            <!-- 左侧：任务内容 / 服务说明 -->
            <div style="flex:2 1 320px;min-width:260px;display:flex;flex-direction:column;gap:16px;">
                <div>
                    <h1 style="font-size:22px;font-weight:600;margin:0 0 10px;">{{ $task->title }}</h1>
                    <p style="color:#64748b;font-size:13px;margin:0 0 10px;">
                        服务类型：校园互助 / 时间、地点等可在消息中进一步确认
                    </p>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;">
                        <span class="status-pill {{ $task->status === 'completed' ? 'status-pill--danger' : 'status-pill--success' }}">
                            {{ $task->status === 'completed' ? '已完成' : '招募中' }}
                        </span>
                        <span class="status-pill" style="background:#ecfeff;color:#155e75;">
                            奖励：{{ $task->reward }}
                        </span>
                    </div>
                </div>

                <div style="margin-top:4px;color:#334155;font-size:14px;line-height:1.8;white-space:pre-line;">
                    {{ $task->content }}
                </div>

                @if($task->latitude && $task->longitude)
                <div class="mt-8 pt-8 border-t border-gray-50">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-2xl bg-blue-50 flex items-center justify-center text-xl">📍</div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">任务地点</h3>
                            <p class="text-sm text-gray-500">{{ $task->location ?? '线下地点待确认' }}</p>
                        </div>
                    </div>

                    <div class="relative group">
                        <div id="task-detail-map" class="h-64 w-full rounded-2xl border border-gray-100 shadow-sm overflow-hidden"></div>
                        <a
                            href="https://uri.amap.com/marker?position={{ $task->longitude }},{{ $task->latitude }}&name={{ urlencode($task->location ?? $task->title) }}"
                            target="_blank"
                            class="absolute bottom-4 right-4 bg-white/90 backdrop-blur px-4 py-2 rounded-full shadow-md text-xs font-bold text-blue-600 flex items-center gap-2 hover:scale-105 transition"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            去这里
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <!-- 右侧：发布人信息 + 操作按钮 -->
            <div style="flex:1 1 260px;min-width:240px;display:flex;flex-direction:column;gap:14px;">
                <div style="border:1px solid #e2e8f0;border-radius:14px;padding:14px;display:flex;gap:12px;align-items:center;">
                    <div style="width:44px;height:44px;border-radius:999px;background:#fef3c7;color:#b45309;display:flex;align-items:center;justify-content:center;font-weight:600;font-size:18px;">
                        {{ mb_substr($task->user->name, 0, 1) }}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p style="margin:0 0 4px;font-weight:600;font-size:14px;color:#0f172a;">
                            {{ $task->user->name }}
                            <span style="font-size:11px;color:#16a34a;border-radius:999px;background:#dcfce7;padding:2px 8px;margin-left:6px;">
                                已认证学生（示例）
                            </span>
                        </p>
                        <p style="margin:0;color:#94a3b8;font-size:12px;">
                            校园信誉：⭐ 4.8 · 15 次互助完成（示例）
                        </p>
                    </div>
                </div>

                <div style="border-radius:12px;background:#eff6ff;color:#1d4ed8;padding:10px 12px;font-size:12px;line-height:1.6;">
                    温馨提示：线下互助请选择人流较多、熟悉的地点见面，注意保护个人隐私，勿轻信转账链接。
                </div>

                <div style="margin-top:4px;display:flex;flex-wrap:wrap;gap:10px;">
                    @auth
                        @if(auth()->id() !== $task->user_id)
                            <button 
                                type="button" 
                                id="contact-btn"
                                class="btn btn-primary" 
                                style="flex:1 1 150px;min-width:130px;position:relative;"
                                onclick="startChat({{ $task->user_id }}, {{ $task->id }}, 'task', this)"
                            >
                                <span id="contact-btn-text">我来帮忙 / 立即沟通</span>
                                <svg id="contact-btn-spinner" class="hidden absolute inset-0 m-auto w-5 h-5 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        @else
                            <button 
                                type="button" 
                                class="btn btn-secondary" 
                                style="flex:1 1 150px;min-width:130px;background:#f3f4f6;color:#6b7280;border:none;cursor:not-allowed;"
                                disabled
                            >
                                这是您发布的任务
                            </button>
                        @endif
                    @else
                        <a 
                            href="{{ route('login') }}"
                            class="btn btn-primary"
                            style="flex:1 1 150px;min-width:130px;text-decoration:none;display:flex;align-items:center;justify-content:center;"
                        >
                            我来帮忙 / 立即沟通
                        </a>
                    @endauth
                    @auth
                        @php
                            $isLiked = $task->isLikedBy(auth()->user());
                        @endphp
                        <button 
                            type="button" 
                            id="wishlist-btn"
                            class="wishlist-detail-btn {{ $isLiked ? 'wishlist-detail-btn--liked' : 'wishlist-detail-btn--unliked' }}"
                            data-task-id="{{ $task->id }}"
                            data-liked="{{ $isLiked ? 'true' : 'false' }}"
                            data-item-type="task"
                        >
                            <span id="wishlist-text">{{ $isLiked ? '已关注任务 ❤️' : '关注此任务' }}</span>
                        </button>
                    @else
                        <a 
                            href="{{ route('login') }}"
                            class="btn btn-secondary"
                            style="flex:1 1 130px;min-width:120px;background:#f3f4f6;color:#374151;border:none;text-decoration:none;display:flex;align-items:center;justify-content:center;transition:all 0.2s ease;padding:12px 20px;border-radius:999px;font-weight:500;font-size:14px;"
                        >
                            关注此任务
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
        .wishlist-detail-btn {
            flex: 1 1 130px;
            min-width: 120px;
            padding: 12px 20px;
            border-radius: 999px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
        }
        .wishlist-detail-btn--liked {
            background: #fee2e2;
            color: #991b1b;
        }
        .wishlist-detail-btn--unliked {
            background: #f3f4f6;
            color: #374151;
        }
        .wishlist-detail-btn:hover {
            background: #e5e7eb !important;
            transform: translateY(-1px);
        }
        .wishlist-detail-btn--liked:hover {
            background: #fecaca !important;
        }
        .wishlist-detail-btn:active {
            transform: scale(0.95);
        }
    </style>

    @if($task->latitude && $task->longitude)
    <script type="text/javascript">
        window._AMapSecurityConfig = {
            securityJsCode: '53cd5c8cddb263d94888f1f61fe08201'
        };
    </script>
    <script src="https://webapi.amap.com/maps?v=2.0&key=17874d29165d98aaefcd72ca015bb493&plugin=AMap.ToolBar"></script>
    @endif

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
                btnElement.classList.remove('wishlist-detail-btn--liked');
                btnElement.classList.add('wishlist-detail-btn--unliked');
            } else {
                wishlistText.textContent = likedText;
                btnElement.setAttribute('data-liked', 'true');
                btnElement.classList.remove('wishlist-detail-btn--unliked');
                btnElement.classList.add('wishlist-detail-btn--liked');
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
                    btnElement.classList.remove('wishlist-detail-btn--unliked');
                    btnElement.classList.add('wishlist-detail-btn--liked');
                } else {
                    wishlistText.textContent = unlikedText;
                    btnElement.setAttribute('data-liked', 'false');
                    btnElement.classList.remove('wishlist-detail-btn--liked');
                    btnElement.classList.add('wishlist-detail-btn--unliked');
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

        // Initialize event listener for wishlist button
    document.addEventListener('DOMContentLoaded', function() {
            const wishlistBtn = document.getElementById('wishlist-btn');
            if (wishlistBtn) {
                wishlistBtn.addEventListener('click', function() {
                    const taskId = this.getAttribute('data-task-id');
                    const itemType = this.getAttribute('data-item-type') || 'task';
                    toggleWishlist(taskId, this, itemType);
                });
            }

            // Note: Contact button now uses onclick="startChat()" function defined below

            @if($task->latitude && $task->longitude)
        initializeTaskDetailMap({{ $task->latitude }}, {{ $task->longitude }}, @json($task->location ?? $task->title));
            @endif
        });

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

        function initializeTaskDetailMap(lat, lng, label) {
            if (typeof AMap === 'undefined') {
                console.warn('AMap SDK not loaded');
                return;
            }

            const map = new AMap.Map('task-detail-map', {
                zoom: 17,
                center: [lng, lat],
                viewMode: '3D',
                scrollWheel: false,
                dragEnable: true,
                doubleClickZoom: false
            });

            const marker = new AMap.Marker({
                position: [lng, lat],
                title: label,
                offset: new AMap.Pixel(-9, -30),
                icon: new AMap.Icon({
                    size: new AMap.Size(30, 42),
                    image: 'https://webapi.amap.com/theme/v1.3/markers/n/mark_b.png',
                    imageSize: new AMap.Size(30, 42)
                })
            });

            map.add(marker);

            setTimeout(() => map.resize(), 300);
        }
    </script>
@endsection


