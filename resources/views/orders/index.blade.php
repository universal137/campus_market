@extends('layouts.app')

@section('title', '我的订单 - 校园易')

@section('content')
<div class="bg-[#F9FAFB] min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">订单中心</h1>
            <p class="text-gray-500">管理您的购买和销售订单</p>
        </div>

        <!-- Silky Segmented Control (iOS-style) -->
        <div class="flex justify-center mb-8">
            <div class="relative bg-gray-100 rounded-full p-1 inline-flex">
                <!-- Sliding White Background -->
                <div id="sliding-bg" class="absolute bg-white rounded-full shadow-sm transition-all duration-300 ease-out" style="top: 4px; left: 4px; width: calc(50% - 4px); height: calc(100% - 8px);"></div>
                
                <!-- Tab Buttons -->
                <button 
                    type="button"
                    id="tab-bought"
                    onclick="switchTab('bought')"
                    class="relative z-10 px-8 py-2.5 rounded-full text-sm font-semibold transition-colors duration-200"
                    style="color: #0f172a;"
                >
                    我买到的
                </button>
                <button 
                    type="button"
                    id="tab-sold"
                    onclick="switchTab('sold')"
                    class="relative z-10 px-8 py-2.5 rounded-full text-sm font-semibold transition-colors duration-200"
                    style="color: #64748b;"
                >
                    我卖出的
                </button>
            </div>
        </div>

        <!-- Bought Orders Section (Vertical List) -->
        <div id="bought-orders" class="order-list-section">
            @forelse($boughtOrders as $order)
                <!-- Order Card (Bill Look) -->
                <div class="w-full bg-white rounded-2xl p-5 border border-gray-100 flex items-center gap-6 mb-4 hover:shadow-md transition-all duration-200" data-order-id="{{ $order->id }}" data-review-target-id="{{ $order->seller_id ?? $order->buyer_id }}">
                    <!-- Left: Product Image -->
                    <div class="flex-shrink-0">
                        @if($order->product && $order->product->image)
                            <img 
                                src="{{ $order->product->image }}" 
                                alt="{{ $order->product->title ?? '商品图片' }}"
                                class="w-20 h-20 object-cover rounded-xl"
                                onerror="this.src='https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=400&h=300&fit=crop'"
                            >
                        @else
                            <div class="w-20 h-20 bg-gray-100 rounded-xl flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Middle: Context Info -->
                    <div class="flex-1 min-w-0">
                        <!-- Row 1: Status Pill -->
                        <div class="mb-2">
                            <span id="status-badge-{{ $order->id }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                @if($order->status === 'pending') bg-orange-100 text-orange-800
                                @elseif($order->status === 'completed') bg-green-100 text-green-800
                                @elseif($order->status === 'cancelled') bg-gray-100 text-gray-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                @if($order->status === 'pending')
                                    待确认
                                @elseif($order->status === 'completed')
                                    已完成
                                @elseif($order->status === 'cancelled')
                                    已取消
                                @else
                                    {{ $order->status }}
                                @endif
                            </span>
                        </div>

                        <!-- Row 2: Product Title -->
                        <h3 class="text-base font-bold text-gray-900 mb-2 line-clamp-1">
                            {{ $order->product->title ?? '商品已删除' }}
                        </h3>

                        <!-- Row 3: Counterpart Info (Seller) -->
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-500">卖家：</span>
                            @if($order->seller)
                                @if($order->seller->avatar)
                                    <img 
                                        src="{{ asset('storage/' . $order->seller->avatar) }}" 
                                        alt="{{ $order->seller->name }}"
                                        class="w-6 h-6 rounded-full object-cover"
                                        onerror="this.onerror=null; this.src='https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($order->seller->name) }}'"
                                    >
                                @else
                                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-semibold">
                                        {{ mb_substr($order->seller->name, 0, 1) }}
                                    </div>
                                @endif
                                <span class="text-sm text-gray-700 font-medium">{{ $order->seller->name ?? '未知' }}</span>
                            @else
                                <span class="text-sm text-gray-500">未知</span>
                            @endif
                        </div>
                    </div>

                    <!-- Right: Price & Action -->
                    <div class="flex-shrink-0 flex flex-col items-end gap-3">
                        <!-- Large Price -->
                        <div class="text-xl font-bold text-gray-900">
                            ¥{{ number_format($order->price ?? $order->product->price ?? 0, 2) }}
                        </div>

                        <!-- Action Button -->
                        <div id="action-button-{{ $order->id }}">
                            @if($order->status === 'pending')
                                <button 
                                    type="button"
                                    onclick="updateStatus({{ $order->id }}, 'completed', this)"
                                    class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-200 active:scale-95"
                                >
                                    确认收货
                                </button>
                            @elseif($order->status === 'completed')
                                @if($order->review)
                                    <!-- Show rating stars if review exists -->
                                    <div class="flex items-center gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $order->review->rating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                @else
                                <button 
                                    data-review-button
                                        type="button"
                                    onclick="openReviewModal({{ $order->id }}, {{ $order->seller_id ?? $order->buyer_id }})"
                                        class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors duration-200 active:scale-95"
                                    >
                                        评价
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State for Bought Orders -->
                <div class="text-center py-20">
                    <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <p class="text-gray-500 text-lg font-medium">您还没有买过东西</p>
                    <p class="text-gray-400 text-sm mt-2">快去市场逛逛吧！</p>
                </div>
            @endforelse
        </div>

        <!-- Sold Orders Section (Vertical List) -->
        <div id="sold-orders" class="order-list-section hidden">
            @forelse($soldOrders as $order)
                <!-- Order Card (Bill Look) -->
                <div class="w-full bg-white rounded-2xl p-5 border border-gray-100 flex items-center gap-6 mb-4 hover:shadow-md transition-all duration-200" data-order-id="{{ $order->id }}" data-review-target-id="{{ $order->buyer_id ?? $order->seller_id }}">
                    <!-- Left: Product Image -->
                    <div class="flex-shrink-0">
                        @if($order->product && $order->product->image)
                            <img 
                                src="{{ $order->product->image }}" 
                                alt="{{ $order->product->title ?? '商品图片' }}"
                                class="w-20 h-20 object-cover rounded-xl"
                                onerror="this.src='https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=400&h=300&fit=crop'"
                            >
                        @else
                            <div class="w-20 h-20 bg-gray-100 rounded-xl flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Middle: Context Info -->
                    <div class="flex-1 min-w-0">
                        <!-- Row 1: Status Pill -->
                        <div class="mb-2">
                            @if($order->status === 'pending')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                    待确认
                                </span>
                            @elseif($order->status === 'completed')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    已完成
                                </span>
                            @elseif($order->status === 'cancelled')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                    已取消
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                    {{ $order->status }}
                                </span>
                            @endif
                        </div>

                        <!-- Row 2: Product Title -->
                        <h3 class="text-base font-bold text-gray-900 mb-2 line-clamp-1">
                            {{ $order->product->title ?? '商品已删除' }}
                        </h3>

                        <!-- Row 3: Counterpart Info (Buyer) -->
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-500">买家：</span>
                            @if($order->buyer)
                                @if($order->buyer->avatar)
                                    <img 
                                        src="{{ asset('storage/' . $order->buyer->avatar) }}" 
                                        alt="{{ $order->buyer->name }}"
                                        class="w-6 h-6 rounded-full object-cover"
                                        onerror="this.onerror=null; this.src='https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($order->buyer->name) }}'"
                                    >
                                @else
                                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-semibold">
                                        {{ mb_substr($order->buyer->name, 0, 1) }}
                                    </div>
                                @endif
                                <span class="text-sm text-gray-700 font-medium">{{ $order->buyer->name ?? '未知' }}</span>
                            @else
                                <span class="text-sm text-gray-500">未知</span>
                            @endif
                        </div>
                    </div>

                    <!-- Right: Price & Action -->
                    <div class="flex-shrink-0 flex flex-col items-end gap-3">
                        <!-- Large Price -->
                        <div class="text-xl font-bold text-gray-900">
                            ¥{{ number_format($order->price ?? $order->product->price ?? 0, 2) }}
                        </div>

                        <!-- Action Button -->
                        <div id="action-button-{{ $order->id }}">
                            @if($order->status === 'completed')
                                <button 
                                    type="button"
                                    class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors duration-200 active:scale-95"
                                    onclick="window.location.href='{{ route('items.show', $order->product_id ?? '#') }}'"
                                >
                                    查看详情
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State for Sold Orders -->
                <div class="text-center py-20">
                    <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-gray-500 text-lg font-medium">您还没有卖出过订单</p>
                    <p class="text-gray-400 text-sm mt-2">快去"我发布的"看看吧</p>
                    <a href="{{ route('user.published') }}" class="inline-block mt-4 px-6 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        去发布商品
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container" class="fixed bottom-8 left-1/2 transform -translate-x-1/2 z-50 pointer-events-none">
    <div id="toast" class="bg-black/80 text-white px-6 py-3 rounded-full shadow-lg opacity-0 translate-y-4 transition-all duration-300 ease-out pointer-events-auto">
        <span id="toast-message"></span>
    </div>
</div>

<!-- Review Modal -->
<div id="review-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeReviewModal()"></div>

    <div class="relative z-10 flex min-h-screen items-center justify-center p-4">
        <div id="review-card" class="w-full max-w-md rounded-3xl bg-white p-8 shadow-2xl transform transition-all duration-300 ease-out scale-95 opacity-0">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.4em] text-gray-400 mb-2">Silky Review</p>
                    <h2 class="text-2xl font-semibold text-gray-900">为本次交易打分</h2>
                </div>
                <button type="button" onclick="closeReviewModal()" class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gray-100 text-gray-500 transition hover:bg-gray-200 hover:text-gray-900">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 6l12 12M6 18L18 6" />
                    </svg>
                </button>
            </div>

            <div class="space-y-6">
                <div>
                    <p class="text-sm text-gray-500 mb-3">选择你的星级感受</p>
                    <div id="review-stars" class="flex items-center justify-between">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button" class="star-trigger text-gray-300 transition-all duration-200" data-star="{{ $i }}">
                                <svg class="h-10 w-10" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M11.1 3.519c.39-1.04 1.8-1.04 2.19 0l1.51 4.02a1 1 0 00.94.65h4.02c1.09 0 1.54 1.39.66 2.02l-3.63 2.62a1 1 0 00-.37 1.11l1.38 4.01c.37 1.07-.89 1.96-1.81 1.3l-3.63-2.63a1 1 0 00-1.18 0l-3.63 2.63c-.92.66-2.18-.23-1.81-1.3l1.38-4.01a1 1 0 00-.37-1.11l-3.63-2.63c-.87-.63-.43-2.02.66-2.02h4.02a1 1 0 00.94-.65z" />
                                </svg>
                            </button>
                        @endfor
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="review-comment" class="text-sm text-gray-600">写下你的感受...</label>
                    <textarea id="review-comment" rows="4" class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-5 py-4 text-sm text-gray-800 placeholder:text-gray-400 transition-all focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-100" placeholder="写下你的感受..."></textarea>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-3">
                <button type="button" onclick="closeReviewModal()" class="flex-1 rounded-2xl border border-gray-200 px-5 py-3 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">取消</button>
                <button type="button" onclick="submitReview()" class="flex-1 rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/30 transition hover:bg-blue-700">提交评价</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Global variables for review modal
let currentReviewOrderId = null;
let currentReviewSellerId = null;
let selectedRating = 0;

/**
 * Switch Tab with Fade Out/In Animation
 * @param {string} type - 'bought' or 'sold'
 */
function switchTab(type) {
    const boughtSection = document.getElementById('bought-orders');
    const soldSection = document.getElementById('sold-orders');
    const boughtTab = document.getElementById('tab-bought');
    const soldTab = document.getElementById('tab-sold');
    const slidingBg = document.getElementById('sliding-bg');
    
    // Determine which section to show and hide
    const showSection = type === 'bought' ? boughtSection : soldSection;
    const hideSection = type === 'bought' ? soldSection : boughtSection;
    
    // Fade out current section
    hideSection.style.opacity = '0';
    hideSection.style.transition = 'opacity 0.2s ease-out';
    
    setTimeout(() => {
        // Hide current section and show new one
        hideSection.classList.add('hidden');
        showSection.classList.remove('hidden');
        
        // Update tab styles
        if (type === 'bought') {
            boughtTab.style.color = '#0f172a';
            soldTab.style.color = '#64748b';
            // Slide background to left (reset position)
            slidingBg.style.transform = 'translateX(0)';
        } else {
            boughtTab.style.color = '#64748b';
            soldTab.style.color = '#0f172a';
            // Slide background to right
            const tabWidth = boughtTab.offsetWidth;
            slidingBg.style.transform = `translateX(${tabWidth}px)`;
        }
        
        // Fade in new section
        setTimeout(() => {
            showSection.style.opacity = '0';
            showSection.style.transition = 'opacity 0.3s ease-in';
            // Force reflow
            showSection.offsetHeight;
            showSection.style.opacity = '1';
        }, 10);
    }, 200);
}

/**
 * Update Order Status with Optimistic UI
 * @param {number} orderId - The order ID
 * @param {string} newStatus - The new status ('completed' or 'cancelled')
 * @param {HTMLElement} btnElement - The button element that was clicked
 */
function updateStatus(orderId, newStatus, btnElement) {
    // Find the order card
    const orderCard = btnElement.closest('[data-order-id]');
    const statusBadge = document.getElementById(`status-badge-${orderId}`);
    const actionButtonContainer = document.getElementById(`action-button-${orderId}`);
    
    // Optimistic UI Update: Immediately change status badge
    if (newStatus === 'completed') {
        statusBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800';
        statusBadge.textContent = '已完成';
        
        // Hide the action button immediately
        actionButtonContainer.innerHTML = '';
    }
    
    // Send POST request to backend
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    fetch(`/orders/${orderId}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            status: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('订单状态已更新');
            // Show review button after status update
            const reviewTargetId = orderCard?.dataset?.reviewTargetId ?? 'null';
            actionButtonContainer.innerHTML = `
                <button 
                    data-review-button
                    type="button"
                    onclick="openReviewModal(${orderId}, ${reviewTargetId})"
                    class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors duration-200 active:scale-95"
                >
                    评价
                </button>
            `;
        } else {
            // Revert optimistic update on error
            statusBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800';
            statusBadge.textContent = '待确认';
            actionButtonContainer.innerHTML = `
                <button 
                    type="button"
                    onclick="updateStatus(${orderId}, 'completed', this)"
                    class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-200 active:scale-95"
                >
                    确认收货
                </button>
            `;
            showToast(data.message || '更新失败，请重试');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Revert optimistic update on error
        statusBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800';
        statusBadge.textContent = '待确认';
        actionButtonContainer.innerHTML = `
            <button 
                type="button"
                onclick="updateStatus(${orderId}, 'completed', this)"
                class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-200 active:scale-95"
            >
                确认收货
            </button>
        `;
        showToast('更新失败，请重试');
    });
}

/**
 * Open Review Modal (Apple style)
 * @param {number} orderId
 * @param {number|null} sellerId
 */
function openReviewModal(orderId, sellerId) {
    currentReviewOrderId = orderId;
    currentReviewSellerId = sellerId ?? null;
    selectedRating = 0;

    document.getElementById('review-comment').value = '';
    paintStars(0);

    const modal = document.getElementById('review-modal');
    const card = document.getElementById('review-card');

    modal.classList.remove('hidden');
    requestAnimationFrame(() => {
        card.classList.remove('scale-95', 'opacity-0');
        card.classList.add('scale-100', 'opacity-100');
    });
}

/**
 * Close Review Modal
 */
function closeReviewModal() {
    const modal = document.getElementById('review-modal');
    const card = document.getElementById('review-card');

    card.classList.remove('scale-100', 'opacity-100');
    card.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        modal.classList.add('hidden');
        currentReviewOrderId = null;
        currentReviewSellerId = null;
        selectedRating = 0;
        paintStars(0);
    }, 200);
}

/**
 * Paint stars up to rating
 * @param {number} rating
 */
function paintStars(rating) {
    const stars = document.querySelectorAll('[data-star]');
    stars.forEach((btn) => {
        const value = Number(btn.dataset.star);
        const icon = btn.querySelector('svg');
        if (value <= rating) {
            icon.classList.remove('text-gray-300');
            icon.classList.add('text-yellow-400');
        } else {
            icon.classList.remove('text-yellow-400');
            icon.classList.add('text-gray-300');
        }
    });
}

/**
 * Init silky star hover + pop interactions
 */
function initStarInteractions() {
    const stars = document.querySelectorAll('[data-star]');
    stars.forEach((btn) => {
        const rating = Number(btn.dataset.star);

        btn.addEventListener('mouseenter', () => paintStars(rating));
        btn.addEventListener('mouseleave', () => paintStars(selectedRating));
        btn.addEventListener('click', () => {
            selectedRating = rating;
            btn.classList.add('star-pop');
            setTimeout(() => btn.classList.remove('star-pop'), 180);
            paintStars(selectedRating);
        });
    });
}

/**
 * Submit Review
 */
function submitReview() {
    if (selectedRating === 0) {
        showToast('请选择评分');
        return;
    }
    
    const orderId = currentReviewOrderId;
    const comment = document.getElementById('review-comment').value.trim();
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    fetch(`/orders/${orderId}/review`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            rating: selectedRating,
            comment: comment || null,
            seller_id: currentReviewSellerId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeReviewModal();

            const orderCard = document.querySelector(`[data-order-id="${orderId}"]`);
            const actionButtonContainer = document.getElementById(`action-button-${orderId}`);
            const reviewButton = orderCard?.querySelector('[data-review-button]');

            let starsHtml = '';
            if (actionButtonContainer) {
                starsHtml = '<div class="flex items-center gap-0.5">';
                for (let i = 1; i <= 5; i++) {
                    const starClass = i <= selectedRating ? 'text-yellow-400 fill-current' : 'text-gray-300';
                    starsHtml += `
                        <svg class="w-4 h-4 ${starClass}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    `;
                }
                starsHtml += '</div>';
                actionButtonContainer.innerHTML = starsHtml;
            } else if (reviewButton) {
                const badge = document.createElement('span');
                badge.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700';
                badge.textContent = '已评价';
                reviewButton.replaceWith(badge);
            }

            showToast('评价成功！信誉分已更新');
        } else {
            showToast(data.message || '提交失败，请重试');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('提交失败，请重试');
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

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Ensure the sliding background starts at the correct position
    switchTab('bought');
    
    // Initialize opacity for order sections
    const boughtSection = document.getElementById('bought-orders');
    const soldSection = document.getElementById('sold-orders');
    boughtSection.style.opacity = '1';
    soldSection.style.opacity = '1';

    initStarInteractions();
    paintStars(0);
});
</script>
@endpush

<style>
/* Line clamp utility */
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Order list section base styles */
.order-list-section {
    transition: opacity 0.3s ease;
}

.star-trigger {
    transform-origin: center;
}

.star-trigger.star-pop {
    transform: scale(1.25);
}
</style>
@endsection
