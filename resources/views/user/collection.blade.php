@extends('layouts.app')

@section('title', '我的收藏中心 · 校园易')

@section('content')
<div class="bg-[#F9FAFB] min-h-screen">
    <!-- Header Section -->
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 text-center mb-8">我的收藏中心</h1>
            
            <!-- Segmented Control (Tab Switcher) -->
            <div class="flex justify-center">
                <div class="relative inline-flex bg-gray-100 rounded-full p-1.5 shadow-inner" role="tablist">
                    <!-- Sliding Background Indicator -->
                    <div 
                        id="tab-indicator" 
                        class="absolute top-1.5 bottom-1.5 left-1.5 w-[calc(50%-6px)] bg-white rounded-full shadow-sm transition-all duration-300 ease-out"
                        style="transform: translateX(0);"
                    ></div>
                    
                    <!-- Products Tab Button -->
                    <button 
                        id="tab-btn-products"
                        onclick="switchTab('products')"
                        class="relative z-10 px-8 py-2.5 rounded-full font-medium text-sm transition-all duration-300 ease-out tab-button active"
                        role="tab"
                        aria-selected="true"
                    >
                        心仪好物
                    </button>
                    
                    <!-- Tasks Tab Button -->
                    <button 
                        id="tab-btn-tasks"
                        onclick="switchTab('tasks')"
                        class="relative z-10 px-8 py-2.5 rounded-full font-medium text-sm transition-all duration-300 ease-out tab-button"
                        role="tab"
                        aria-selected="false"
                    >
                        关注任务
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Products Tab Content -->
        <div id="tab-products" class="tab-content">
            @if($wishlistProducts->isEmpty())
                <div class="text-center py-20">
                    <div class="inline-block p-12 bg-white rounded-3xl border border-gray-100 shadow-sm mb-6">
                        <div class="text-8xl mb-6">📦</div>
                        <p class="text-gray-500 text-xl font-medium mb-2">暂无收藏商品</p>
                        <p class="text-gray-400 text-sm mb-8">快去发现心仪好物吧</p>
                        <a 
                            href="{{ route('items.index') }}"
                            class="inline-block px-8 py-3.5 bg-blue-600 text-white rounded-full font-medium transition-all duration-200 ease-in-out hover:bg-blue-700 active:scale-95 shadow-md hover:shadow-lg"
                        >
                            去逛逛二手
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8">
                    @foreach($wishlistProducts as $index => $item)
                        <div class="product-card-entry opacity-0 translate-y-8 transition-all duration-700 ease-out transform">
                            <article class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm transition-all duration-300 ease hover:-translate-y-1 hover:shadow-[0_8px_24px_rgba(0,0,0,0.1)] hover:border-slate-300 relative group">
                                <!-- Image Area -->
                                <div class="aspect-[4/3] bg-gray-100 overflow-hidden relative">
                                    <a href="{{ route('products.show', $item) }}" class="block w-full h-full">
                                        <img 
                                            src="{{ $item->image_url }}" 
                                            alt="{{ $item->title }}"
                                            class="w-full h-full object-cover transition-transform duration-300 ease group-hover:scale-105"
                                        >
                                    </a>
                                    
                                    <!-- Heart Button (Top-Right) - Always Red (in collection) -->
                                    <button 
                                        type="button"
                                        onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist({{ $item->id }}, this);"
                                        class="absolute top-3 right-3 w-10 h-10 rounded-full bg-white/80 backdrop-blur-sm hover:bg-white shadow-sm flex items-center justify-center transition-all duration-200 active:scale-75 z-10 wishlist-heart-btn"
                                        data-item-id="{{ $item->id }}"
                                        data-liked="true"
                                    >
                                        <svg 
                                            class="w-5 h-5 transition-all duration-200 heart-icon text-red-500 fill-current"
                                            viewBox="0 0 24 24" 
                                            fill="currentColor" 
                                            stroke="currentColor" 
                                            stroke-width="2" 
                                            stroke-linecap="round" 
                                            stroke-linejoin="round"
                                        >
                                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                        </svg>
                                    </button>
                                </div>
                                
                                <a href="{{ route('products.show', $item) }}" class="block">
                                    <!-- Content -->
                                    <div class="p-5">
                                        <!-- Category Badge -->
                                        <span class="inline-block px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-full mb-3">
                                            {{ optional($item->category)->name ?? '未分类' }}
                                        </span>
                                        
                                        <!-- Title -->
                                        <h3 class="font-bold text-gray-900 text-lg mb-2 line-clamp-1 group-hover:text-blue-600 transition-colors">
                                            {{ $item->title }}
                                        </h3>
                                        
                                        <!-- Description -->
                                        <p class="text-gray-500 text-sm mb-4 line-clamp-2">
                                            {{ \Illuminate\Support\Str::limit($item->description, 60) }}
                                        </p>
                                        
                                        <!-- Price -->
                                        <div class="mb-4">
                                            <span class="text-2xl font-bold text-[#FF2D55]">¥{{ number_format($item->price, 2) }}</span>
                                        </div>
                                        
                                        <!-- Footer: Seller & Location -->
                                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white text-xs font-semibold">
                                                    {{ mb_substr($item->user->name, 0, 1) }}
                                                </div>
                                                <span class="text-xs text-gray-600 font-medium">{{ $item->user->name }}</span>
                                            </div>
                                            <span class="text-xs text-gray-400">
                                                📍
                                            </span>
                                        </div>
                                        @if($item->deal_place)
                                            <p class="text-xs text-gray-400 mt-2 truncate">
                                                {{ $item->deal_place }}
                                            </p>
                                        @endif
                                    </div>
                                </a>
                            </article>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Tasks Tab Content -->
        <div id="tab-tasks" class="tab-content hidden">
            @if($wishlistTasks->isEmpty())
                <div class="text-center py-20">
                    <div class="inline-block p-12 bg-white rounded-3xl border border-gray-100 shadow-sm mb-6">
                        <div class="text-8xl mb-6">🤝</div>
                        <p class="text-gray-500 text-xl font-medium mb-2">暂无关注任务</p>
                        <p class="text-gray-400 text-sm mb-8">快去发现有趣的互助任务吧</p>
                        <a 
                            href="{{ route('tasks.index') }}"
                            class="inline-block px-8 py-3.5 bg-blue-600 text-white rounded-full font-medium transition-all duration-200 ease-in-out hover:bg-blue-700 active:scale-95 shadow-md hover:shadow-lg"
                        >
                            去看看求助
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($wishlistTasks as $index => $task)
                        <div class="task-card-entry opacity-0 translate-y-8 transition-all duration-700 ease-out transform">
                            <a 
                                href="{{ route('tasks.show', $task) }}" 
                                class="group block"
                            >
                                <article class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-xl hover:border-blue-100 h-full flex flex-col">
                                    <!-- Top: User Avatar + Name + Time -->
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                                            {{ mb_substr($task->user->name, 0, 1) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-900 text-sm truncate">{{ $task->user->name }}</p>
                                            <p class="text-gray-400 text-xs">{{ $task->created_at->diffForHumans() }}</p>
                                        </div>
                                        <!-- Status Pill -->
                                        <div class="flex items-center gap-2">
                                            @if($task->status === 'open')
                                                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                                <span class="text-xs text-gray-500 font-medium">招募中</span>
                                            @else
                                                <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                                <span class="text-xs text-gray-500 font-medium">已完成</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Middle: Task Title + Description -->
                                    <div class="flex-1 mb-4">
                                        <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors duration-200">
                                            {{ $task->title }}
                                        </h3>
                                        <p class="text-gray-600 text-sm line-clamp-3 leading-relaxed">
                                            {{ $task->content }}
                                        </p>
                                    </div>

                                    <!-- Bottom: Reward Tag + Action Button -->
                                    <div class="flex items-center justify-between gap-3 pt-4 border-t border-gray-100">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                                🎁 {{ $task->reward }}
                                            </span>
                                        </div>
                                        <button 
                                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium transition-all duration-200 ease-in-out group-hover:bg-blue-600 group-hover:text-white opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0"
                                            onclick="event.preventDefault(); window.location.href='{{ route('tasks.show', $task) }}'"
                                        >
                                            我来帮
                                        </button>
                                    </div>
                                </article>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
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
        font-weight: 600;
    }
    
    .tab-button:not(.active) {
        color: #6b7280;
    }
    
    .tab-button:hover:not(.active) {
        color: #374151;
    }
    
    /* Line clamp utility */
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Heart Button Animations */
    .wishlist-heart-btn {
        transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .wishlist-heart-btn:hover {
        transform: scale(1.1);
    }
    .wishlist-heart-btn:active {
        transform: scale(0.75);
    }
    .wishlist-heart-btn.scale-110 {
        transform: scale(1.1);
    }
    
    /* Heart Icon Animation */
    .heart-icon {
        transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
</style>

<script>
    /**
     * Switch between Products and Tasks tabs with smooth sliding animation
     * @param {string} tabName - 'products' or 'tasks'
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
            // Calculate the position: button's offsetLeft is relative to container's content area
            // Indicator starts at left-1.5 (6px), so we use button's offsetLeft directly
            const buttonLeft = selectedButton.offsetLeft;
            
            indicator.style.transform = `translateX(${buttonLeft}px)`;
        }
    }
    
    /**
     * Animate tab content with staggered fade-in (Silky Animation)
     * @param {HTMLElement} container - The container element
     */
    function animateTabContent(container) {
        const cards = container.querySelectorAll('.product-card-entry, .task-card-entry');
        
        // Reset all cards to initial state
        cards.forEach(card => {
            card.classList.remove('opacity-100', 'translate-y-0');
            card.classList.add('opacity-0', 'translate-y-8');
        });
        
        // Animate each card with delay (staggered effect)
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.classList.remove('opacity-0', 'translate-y-8');
                card.classList.add('opacity-100', 'translate-y-0');
            }, index * 100); // 100ms delay between each card for silky waterfall effect
        });
    }
    
    /**
     * Toggle Wishlist with Optimistic UI Update
     * @param {number} productId - The item ID
     * @param {HTMLElement} btnElement - The button element that was clicked
     */
    function toggleWishlist(productId, btnElement) {
        const heartIcon = btnElement.querySelector('.heart-icon');
        const isLiked = btnElement.getAttribute('data-liked') === 'true';
        const cardElement = btnElement.closest('.product-card-entry');
        
        // 1. Optimistic Update - Immediately toggle the heart
        if (isLiked) {
            // Remove from wishlist (optimistic)
            heartIcon.classList.remove('text-red-500', 'fill-current');
            heartIcon.classList.add('text-gray-400', 'stroke-current', 'fill-none');
            btnElement.setAttribute('data-liked', 'false');
        } else {
            // Add to wishlist (optimistic)
            heartIcon.classList.remove('text-gray-400', 'stroke-current', 'fill-none');
            heartIcon.classList.add('text-red-500', 'fill-current');
            btnElement.setAttribute('data-liked', 'true');
        }
        
        // 2. Trigger Animation - Heart pump effect
        btnElement.classList.add('scale-110');
        setTimeout(() => {
            btnElement.classList.remove('scale-110');
        }, 200);
        
        // 3. Server Request
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        fetch(`/wishlist/${productId}`, {
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
            showToast(isLiked ? '已取消收藏' : '已添加到收藏');
            
            // If removed from wishlist, fade out and remove the card
            if (isLiked && cardElement) {
                cardElement.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                cardElement.style.opacity = '0';
                cardElement.style.transform = 'translateY(-20px)';
                
                setTimeout(() => {
                    cardElement.remove();
                    
                    // Check if list is now empty and show empty state
                    const container = document.getElementById('tab-products');
                    const remainingCards = container.querySelectorAll('.product-card-entry');
                    if (remainingCards.length === 0) {
                        location.reload(); // Reload to show empty state
                    }
                }, 300);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Revert optimistic update on error
            if (isLiked) {
                heartIcon.classList.remove('text-gray-400', 'stroke-current', 'fill-none');
                heartIcon.classList.add('text-red-500', 'fill-current');
                btnElement.setAttribute('data-liked', 'true');
            } else {
                heartIcon.classList.remove('text-red-500', 'fill-current');
                heartIcon.classList.add('text-gray-400', 'stroke-current', 'fill-none');
                btnElement.setAttribute('data-liked', 'false');
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
    
    // Initial page load animation
    document.addEventListener('DOMContentLoaded', function() {
        // Animate products tab on load
        const productsTab = document.getElementById('tab-products');
        if (productsTab && !productsTab.classList.contains('hidden')) {
            animateTabContent(productsTab);
        }
    });
</script>
@endsection

