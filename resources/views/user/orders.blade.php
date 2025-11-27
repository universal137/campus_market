@extends('layouts.app')

@section('title', '我的订单 · 校园易')

@section('content')
<div class="bg-[#F9FAFB] min-h-screen">
    <!-- Header Section -->
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 text-center mb-8">我的订单</h1>
            
            <!-- Segmented Control (Tab Switcher) with Sliding Background -->
            <div class="flex justify-center">
                <div class="relative inline-flex bg-gray-100 rounded-full p-1.5 shadow-inner" role="tablist">
                    <!-- Sliding Background Indicator -->
                    <div 
                        id="tab-indicator" 
                        class="absolute top-1.5 bottom-1.5 left-1.5 w-[calc(50%-6px)] bg-white rounded-full shadow-sm transition-all duration-300 ease-out"
                        style="transform: translateX(0);"
                    ></div>
                    
                    <!-- Bought Tab Button -->
                    <button 
                        id="tab-btn-bought"
                        onclick="switchTab('bought')"
                        class="relative z-10 px-8 py-2.5 rounded-full font-medium text-sm transition-all duration-300 ease-out tab-button active"
                        role="tab"
                        aria-selected="true"
                    >
                        我买到的
                    </button>
                    
                    <!-- Sold Tab Button -->
                    <button 
                        id="tab-btn-sold"
                        onclick="switchTab('sold')"
                        class="relative z-10 px-8 py-2.5 rounded-full font-medium text-sm transition-all duration-300 ease-out tab-button"
                        role="tab"
                        aria-selected="false"
                    >
                        我卖出的
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Bought Orders Tab Content -->
        <div id="tab-bought" class="tab-content">
            @if(isset($boughtOrders) && $boughtOrders->count() > 0)
                <div class="space-y-4">
                    @foreach($boughtOrders as $index => $order)
                        <div class="order-card-entry opacity-0 translate-y-8 transition-all duration-700 ease-out transform">
                            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                                <div class="flex flex-col md:flex-row">
                                    <!-- Left: Product Image -->
                                    <div class="w-full md:w-48 h-48 md:h-auto flex-shrink-0 bg-gray-100 overflow-hidden">
                                        <a href="{{ route('products.show', $order->product) }}" class="block w-full h-full">
                                            <img 
                                                src="{{ $order->product->image_url }}" 
                                                alt="{{ $order->product->title }}"
                                                class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
                                            >
                                        </a>
                                    </div>
                                    
                                    <!-- Middle: Product Info -->
                                    <div class="flex-1 p-6 flex flex-col justify-between">
                                        <div>
                                            <a href="{{ route('products.show', $order->product) }}" class="block">
                                                <h3 class="text-xl font-bold text-gray-900 mb-2 hover:text-blue-600 transition-colors">
                                                    {{ $order->product->title }}
                                                </h3>
                                            </a>
                                            <div class="flex items-center gap-4 mb-3">
                                                <span class="text-2xl font-bold text-[#FF2D55]">¥{{ number_format($order->product->price, 2) }}</span>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm text-gray-500">卖家：</span>
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white text-xs font-semibold">
                                                            {{ mb_substr($order->seller->name, 0, 1) }}
                                                        </div>
                                                        <span class="text-sm font-medium text-gray-700">{{ $order->seller->name }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="text-sm text-gray-500">
                                                订单时间：{{ $order->created_at->format('Y-m-d H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <!-- Right: Status & Actions -->
                                    <div class="p-6 flex flex-col items-end justify-between gap-4 border-t md:border-t-0 md:border-l border-gray-100">
                                        <!-- Status Pill -->
                                        <div>
                                            @if($order->status === 'completed')
                                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-700">
                                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                                    已完成
                                                </span>
                                            @elseif($order->status === 'pending')
                                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-orange-100 text-orange-700">
                                                    <span class="w-2 h-2 bg-orange-500 rounded-full mr-2 animate-pulse"></span>
                                                    待确认
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-700">
                                                    <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                                    已取消
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <!-- Action Buttons -->
                                        <div class="flex flex-col gap-2 w-full md:w-auto">
                                            @if($order->status === 'pending')
                                                <button 
                                                    onclick="confirmReceipt({{ $order->id }})"
                                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium text-sm transition-all duration-200 hover:bg-blue-700 active:scale-95 shadow-sm hover:shadow-md"
                                                >
                                                    确认收货
                                                </button>
                                            @elseif($order->status === 'completed')
                                                @if($order->rating)
                                                    <button 
                                                        disabled
                                                        class="px-4 py-2 bg-gray-200 text-gray-500 rounded-lg font-medium text-sm cursor-not-allowed"
                                                    >
                                                        已评价
                                                    </button>
                                                @else
                                                    <button 
                                                        onclick="openReviewModal({{ $order->id }}, {{ $order->seller_id }}, '{{ $order->seller->name }}')"
                                                        class="px-4 py-2 bg-gradient-to-r from-yellow-400 to-orange-500 text-white rounded-lg font-medium text-sm transition-all duration-200 hover:from-yellow-500 hover:to-orange-600 active:scale-95 shadow-sm hover:shadow-md"
                                                    >
                                                        评价卖家
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20">
                    <div class="inline-block p-12 bg-white rounded-3xl border border-gray-100 shadow-sm mb-6">
                        <div class="text-8xl mb-6">🛒</div>
                        <p class="text-gray-500 text-xl font-medium mb-2">暂无购买记录</p>
                        <p class="text-gray-400 text-sm mb-8">快去发现心仪好物吧</p>
                        <a 
                            href="{{ route('items.index') }}"
                            class="inline-block px-8 py-3.5 bg-blue-600 text-white rounded-full font-medium transition-all duration-200 ease-in-out hover:bg-blue-700 active:scale-95 shadow-md hover:shadow-lg"
                        >
                            去逛逛二手
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sold Orders Tab Content -->
        <div id="tab-sold" class="tab-content hidden">
            @if(isset($soldOrders) && $soldOrders->count() > 0)
                <div class="space-y-4">
                    @foreach($soldOrders as $index => $order)
                        <div class="order-card-entry opacity-0 translate-y-8 transition-all duration-700 ease-out transform">
                            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                                <div class="flex flex-col md:flex-row">
                                    <!-- Left: Product Image -->
                                    <div class="w-full md:w-48 h-48 md:h-auto flex-shrink-0 bg-gray-100 overflow-hidden">
                                        <a href="{{ route('products.show', $order->product) }}" class="block w-full h-full">
                                            <img 
                                                src="{{ $order->product->image_url }}" 
                                                alt="{{ $order->product->title }}"
                                                class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
                                            >
                                        </a>
                                    </div>
                                    
                                    <!-- Middle: Product Info -->
                                    <div class="flex-1 p-6 flex flex-col justify-between">
                                        <div>
                                            <a href="{{ route('products.show', $order->product) }}" class="block">
                                                <h3 class="text-xl font-bold text-gray-900 mb-2 hover:text-blue-600 transition-colors">
                                                    {{ $order->product->title }}
                                                </h3>
                                            </a>
                                            <div class="flex items-center gap-4 mb-3">
                                                <span class="text-2xl font-bold text-[#FF2D55]">¥{{ number_format($order->product->price, 2) }}</span>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm text-gray-500">买家：</span>
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-green-400 to-teal-500 flex items-center justify-center text-white text-xs font-semibold">
                                                            {{ mb_substr($order->buyer->name, 0, 1) }}
                                                        </div>
                                                        <span class="text-sm font-medium text-gray-700">{{ $order->buyer->name }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="text-sm text-gray-500">
                                                订单时间：{{ $order->created_at->format('Y-m-d H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <!-- Right: Status & Actions -->
                                    <div class="p-6 flex flex-col items-end justify-between gap-4 border-t md:border-t-0 md:border-l border-gray-100">
                                        <!-- Status Pill -->
                                        <div>
                                            @if($order->status === 'completed')
                                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-700">
                                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                                    已完成
                                                </span>
                                            @elseif($order->status === 'pending')
                                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-orange-100 text-orange-700">
                                                    <span class="w-2 h-2 bg-orange-500 rounded-full mr-2 animate-pulse"></span>
                                                    待确认
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-700">
                                                    <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                                    已取消
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <!-- Action Buttons -->
                                        <div class="flex flex-col gap-2 w-full md:w-auto">
                                            @if($order->status === 'pending')
                                                <button 
                                                    onclick="confirmHandover({{ $order->id }})"
                                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium text-sm transition-all duration-200 hover:bg-blue-700 active:scale-95 shadow-sm hover:shadow-md"
                                                >
                                                    确认交付
                                                </button>
                                            @elseif($order->status === 'completed')
                                                @if($order->rating)
                                                    <button 
                                                        disabled
                                                        class="px-4 py-2 bg-gray-200 text-gray-500 rounded-lg font-medium text-sm cursor-not-allowed"
                                                    >
                                                        已评价
                                                    </button>
                                                @else
                                                    <button 
                                                        onclick="openReviewModal({{ $order->id }}, {{ $order->buyer_id }}, '{{ $order->buyer->name }}')"
                                                        class="px-4 py-2 bg-gradient-to-r from-yellow-400 to-orange-500 text-white rounded-lg font-medium text-sm transition-all duration-200 hover:from-yellow-500 hover:to-orange-600 active:scale-95 shadow-sm hover:shadow-md"
                                                    >
                                                        评价买家
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20">
                    <div class="inline-block p-12 bg-white rounded-3xl border border-gray-100 shadow-sm mb-6">
                        <div class="text-8xl mb-6">💰</div>
                        <p class="text-gray-500 text-xl font-medium mb-2">暂无销售记录</p>
                        <p class="text-gray-400 text-sm mb-8">快去发布商品吧</p>
                        <a 
                            href="{{ route('items.index') }}"
                            class="inline-block px-8 py-3.5 bg-blue-600 text-white rounded-full font-medium transition-all duration-200 ease-in-out hover:bg-blue-700 active:scale-95 shadow-md hover:shadow-lg"
                        >
                            去发布商品
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Review Modal -->
<div id="review-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 transform transition-all duration-300 scale-95 opacity-0" id="review-modal-content">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">评价用户</h2>
            <p class="text-gray-500 text-sm" id="review-user-name"></p>
        </div>
        
        <!-- Star Rating -->
        <div class="flex justify-center gap-2 mb-6" id="star-rating">
            @for($i = 1; $i <= 5; $i++)
                <button 
                    type="button"
                    onclick="setRating({{ $i }})"
                    class="star-btn w-12 h-12 text-gray-300 hover:text-yellow-400 transition-all duration-200 hover:scale-110 active:scale-95"
                    data-rating="{{ $i }}"
                >
                    <svg class="w-full h-full fill-current" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </button>
            @endfor
        </div>
        
        <!-- Comment Textarea -->
        <div class="mb-6">
            <textarea 
                id="review-comment"
                rows="4"
                placeholder="分享您的交易体验..."
                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none transition-all duration-200"
            ></textarea>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex gap-3">
            <button 
                onclick="closeReviewModal()"
                class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl font-medium transition-all duration-200 hover:bg-gray-200 active:scale-95"
            >
                取消
            </button>
            <button 
                onclick="submitReview()"
                class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-medium transition-all duration-200 hover:from-blue-700 hover:to-blue-800 active:scale-95 shadow-md hover:shadow-lg"
            >
                提交评价
            </button>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container" class="fixed bottom-8 left-1/2 transform -translate-x-1/2 z-50 pointer-events-none">
    <div id="toast" class="bg-black/80 text-white px-6 py-3 rounded-full shadow-lg opacity-0 translate-y-4 transition-all duration-300 ease-out pointer-events-auto">
        <span id="toast-message"></span>
    </div>
</div>

<style>
    /* Tab Button Active State */
    .tab-button.active {
        color: #1f2937;
    }
    
    .tab-button:not(.active) {
        color: #6b7280;
    }
    
    .tab-button:hover:not(.active) {
        color: #374151;
    }
    
    /* Star Rating Hover Effect */
    .star-btn.hovered {
        transform: scale(1.15);
    }
    
    /* Modal Animation */
    #review-modal.show #review-modal-content {
        transform: scale(1);
        opacity: 1;
    }
</style>

<script>
    // Current review data
    let currentReviewData = {
        orderId: null,
        userId: null,
        rating: 0,
    };

    /**
     * Switch between Bought and Sold tabs with smooth sliding animation
     * @param {string} tabName - 'bought' or 'sold'
     */
    function switchTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Remove active state from all buttons
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active');
            btn.setAttribute('aria-selected', 'false');
        });
        
        // Show selected tab content
        const selectedContent = document.getElementById(`tab-${tabName}`);
        if (selectedContent) {
            selectedContent.classList.remove('hidden');
            
            // Trigger staggered animation for the visible tab
            setTimeout(() => {
                animateTabContent(selectedContent);
            }, 50);
        }
        
        // Activate selected button
        const selectedButton = document.getElementById(`tab-btn-${tabName}`);
        if (selectedButton) {
            selectedButton.classList.add('active');
            selectedButton.setAttribute('aria-selected', 'true');
        }
        
        // Animate the sliding indicator (smooth slide animation)
        const indicator = document.getElementById('tab-indicator');
        if (indicator) {
            const buttonLeft = selectedButton.offsetLeft;
            indicator.style.transform = `translateX(${buttonLeft}px)`;
        }
    }
    
    /**
     * Animate tab content with staggered fade-in
     * @param {HTMLElement} container - The container element
     */
    function animateTabContent(container) {
        const cards = container.querySelectorAll('.order-card-entry');
        
        // Reset all cards to initial state
        cards.forEach(card => {
            card.classList.remove('opacity-100', 'translate-y-0');
            card.classList.add('opacity-0', 'translate-y-8');
        });
        
        // Animate each card with delay
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.classList.remove('opacity-0', 'translate-y-8');
                card.classList.add('opacity-100', 'translate-y-0');
            }, index * 100);
        });
    }
    
    /**
     * Confirm Receipt (Buyer)
     * @param {number} orderId - The order ID
     */
    function confirmReceipt(orderId) {
        if (!confirm('确认已收到商品？')) {
            return;
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        fetch(`/orders/${orderId}/confirm-receipt`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('确认收货成功');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showToast(data.message || '操作失败，请重试');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('操作失败，请重试');
        });
    }
    
    /**
     * Confirm Handover (Seller)
     * @param {number} orderId - The order ID
     */
    function confirmHandover(orderId) {
        if (!confirm('确认已交付商品？')) {
            return;
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        fetch(`/orders/${orderId}/confirm-handover`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('确认交付成功');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showToast(data.message || '操作失败，请重试');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('操作失败，请重试');
        });
    }
    
    /**
     * Open Review Modal
     * @param {number} orderId - The order ID
     * @param {number} userId - The user ID to rate
     * @param {string} userName - The user name
     */
    function openReviewModal(orderId, userId, userName) {
        currentReviewData.orderId = orderId;
        currentReviewData.userId = userId;
        currentReviewData.rating = 0;
        
        document.getElementById('review-user-name').textContent = `评价 ${userName}`;
        document.getElementById('review-comment').value = '';
        
        // Reset stars
        document.querySelectorAll('.star-btn').forEach((btn, index) => {
            btn.classList.remove('text-yellow-400');
            btn.classList.add('text-gray-300');
        });
        
        // Show modal
        const modal = document.getElementById('review-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            modal.classList.add('show');
        }, 10);
        
        // Add hover effect to stars
        document.querySelectorAll('.star-btn').forEach((btn, index) => {
            btn.addEventListener('mouseenter', function() {
                highlightStars(index + 1);
            });
        });
        
        document.getElementById('star-rating').addEventListener('mouseleave', function() {
            highlightStars(currentReviewData.rating);
        });
    }
    
    /**
     * Close Review Modal
     */
    function closeReviewModal() {
        const modal = document.getElementById('review-modal');
        modal.classList.remove('show');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }
    
    /**
     * Set Rating
     * @param {number} rating - The rating (1-5)
     */
    function setRating(rating) {
        currentReviewData.rating = rating;
        highlightStars(rating);
    }
    
    /**
     * Highlight Stars
     * @param {number} rating - The rating (1-5)
     */
    function highlightStars(rating) {
        document.querySelectorAll('.star-btn').forEach((btn, index) => {
            if (index < rating) {
                btn.classList.remove('text-gray-300');
                btn.classList.add('text-yellow-400');
            } else {
                btn.classList.remove('text-yellow-400');
                btn.classList.add('text-gray-300');
            }
        });
    }
    
    /**
     * Submit Review
     */
    function submitReview() {
        if (currentReviewData.rating === 0) {
            showToast('请选择评分');
            return;
        }
        
        const comment = document.getElementById('review-comment').value.trim();
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        fetch(`/orders/${currentReviewData.orderId}/review`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                user_id: currentReviewData.userId,
                rating: currentReviewData.rating,
                review: comment
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('评价提交成功');
                closeReviewModal();
                setTimeout(() => {
                    location.reload();
                }, 1000);
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
    
    // Close modal when clicking outside
    document.getElementById('review-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeReviewModal();
        }
    });
    
    // Initial page load animation
    document.addEventListener('DOMContentLoaded', function() {
        // Animate bought tab on load
        const boughtTab = document.getElementById('tab-bought');
        if (boughtTab && !boughtTab.classList.contains('hidden')) {
            animateTabContent(boughtTab);
        }
    });
</script>
@endsection
