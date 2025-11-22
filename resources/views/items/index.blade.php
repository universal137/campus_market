@extends('layouts.app')

@section('title', '二手交易 · 校园易')

@section('content')
    <div class="bg-[#F9FAFB] min-h-screen">
    <!-- Hero Section with Search & Filters -->
    <div class="bg-gradient-to-br from-gray-50 via-white to-gray-50 min-h-[400px] flex flex-col items-center justify-center px-4 py-16">
        <div class="w-full max-w-4xl">
            <!-- Page Title -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-3">二手好物广场</h1>
                <p class="text-gray-500 text-lg">按照分类、关键字筛选，快速找到心仪闲置</p>
            </div>

            <!-- Large Floating Search Bar -->
            <form method="GET" class="mb-8">
                <div class="relative max-w-2xl mx-auto">
                    <input 
                        type="text" 
                        id="q" 
                        name="q" 
                        value="{{ $filters['q'] }}" 
                        placeholder="搜索商品，如 iPad、计算器、教材..." 
                        class="w-full px-6 py-4 pl-14 pr-32 text-lg rounded-full border border-gray-200 bg-white shadow-lg focus:outline-none transition-shadow duration-300 ease-in-out focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        style="display: block;"
                    >
                    <svg class="absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none" 
                         width="20" 
                         height="20" 
                         fill="none" 
                         stroke="currentColor" 
                         viewBox="0 0 24 24"
                         style="max-width: 20px; max-height: 20px;"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <button 
                        type="submit" 
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 px-6 py-2 bg-blue-600 text-white rounded-full font-medium transition-all duration-200 ease-in-out hover:bg-blue-700 active:scale-95 z-10"
                    >
                        搜索
                    </button>
                </div>
            </form>

            <!-- Horizontal Scrollable Category Pills -->
            <div class="flex gap-3 overflow-x-auto pb-4 scrollbar-hide -mx-4 px-4">
                <a 
                    href="{{ route('items.index', array_merge(request()->except('category'), ['category' => ''])) }}"
                    class="flex-shrink-0 px-6 py-2.5 rounded-full font-medium transition-all duration-200 {{ !$filters['category'] ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 transition-colors duration-200 ease-in-out hover:bg-gray-200' }}"
                >
                    全部
                </a>
                @foreach($categories as $category)
                    <a 
                        href="{{ route('items.index', array_merge(request()->except('category'), ['category' => $category->id])) }}"
                        class="flex-shrink-0 px-6 py-2.5 rounded-full font-medium transition-all duration-200 {{ $filters['category'] == $category->id ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 transition-colors duration-200 ease-in-out hover:bg-gray-200' }}"
                    >
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- CTA Banner (Replaces Quick Publish Form) -->
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl p-6 md:p-8 shadow-lg">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex-1 text-center md:text-left">
                    <h3 class="text-2xl font-bold text-white mb-2">有闲置要出售？</h3>
                    <p class="text-blue-100">快速发布你的商品，让闲置物品找到新主人</p>
                </div>
                <a 
                    href="#publish-form" 
                    onclick="document.getElementById('publish-form').scrollIntoView({ behavior: 'smooth' }); return false;"
                    class="px-8 py-3 bg-white text-blue-600 font-semibold rounded-full transition-all duration-200 ease-in-out hover:bg-gray-50 active:scale-95 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                >
                    立即发布
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 shadow-sm">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Product Grid Section -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">最新商品</h2>
        
        @if($items->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8">
                @foreach($items as $item)
                    <div class="product-card-entry opacity-0 translate-y-8 transition-all duration-700 ease-out transform">
                        <article class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm transition-all duration-300 ease hover:-translate-y-1 hover:shadow-[0_8px_24px_rgba(0,0,0,0.1)] hover:border-slate-300 relative group">
                            <!-- Image Area -->
                            <div class="aspect-[4/3] bg-gray-100 overflow-hidden relative">
                                <a href="{{ route('items.show', $item) }}" class="block w-full h-full">
                                    <img 
                                        src="{{ $item->image ?? 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=400&h=300&fit=crop' }}" 
                                        alt="{{ $item->title }}"
                                        class="w-full h-full object-cover transition-transform duration-300 ease group-hover:scale-105"
                                    >
                                </a>
                                
                                <!-- Heart Button (Top-Right) -->
                                @auth
                                    <button 
                                        type="button"
                                        onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist({{ $item->id }}, this);"
                                        class="absolute top-3 right-3 w-10 h-10 rounded-full bg-white/80 backdrop-blur-sm hover:bg-white shadow-sm flex items-center justify-center transition-all duration-200 active:scale-75 z-10 wishlist-heart-btn"
                                        data-item-id="{{ $item->id }}"
                                        data-liked="{{ auth()->check() && $item->isLikedBy(auth()->user()) ? 'true' : 'false' }}"
                                    >
                                        <svg 
                                            class="w-5 h-5 transition-all duration-200 heart-icon {{ auth()->check() && $item->isLikedBy(auth()->user()) ? 'text-red-500 fill-current' : 'text-gray-400 stroke-current fill-none' }}"
                                            viewBox="0 0 24 24" 
                                            fill="none" 
                                            stroke="currentColor" 
                                            stroke-width="2" 
                                            stroke-linecap="round" 
                                            stroke-linejoin="round"
                                        >
                                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                        </svg>
                                    </button>
                                @endauth
                            </div>
                            
                            <a href="{{ route('items.show', $item) }}" class="block">
                            
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
                                    
                                    <!-- Description (Optional - can be hidden for cleaner look) -->
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
                                            {{ $item->deal_place ? '📍' : '📍' }}
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
        @else
            <div class="text-center py-16">
                <div class="inline-block p-6 bg-gray-100 rounded-2xl mb-4">
                    <svg class="w-16 h-16 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <p class="text-gray-500 text-lg">还没有任何商品，快来成为第一位发布者吧。</p>
            </div>
        @endif

        <!-- Pagination -->
        @if($items->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $items->links() }}
            </div>
        @endif
    </div>

    <!-- Publish Form Section (Hidden by default, shown when CTA is clicked) -->
    <section id="publish-form" class="max-w-4xl mx-auto px-4 py-12">
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 md:p-10">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">快速发布一条二手商品</h3>
            <p class="text-gray-500 mb-8">填写联系人信息即可体验发布流程（推荐约在校园公共区域当面交易）</p>

            <form id="publish-item-form" method="POST" action="{{ route('items.store') }}" class="space-y-6">
                @csrf
                
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4">
                        <strong class="font-semibold">请检查以下输入：</strong>
                        <ul class="mt-2 ml-4 list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="seller_name" class="block text-sm font-semibold text-gray-700 mb-2">联系人昵称</label>
                        <input 
                            id="seller_name" 
                            name="seller_name" 
                            value="{{ old('seller_name') }}" 
                            required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        >
                    </div>
                    <div>
                        <label for="seller_email" class="block text-sm font-semibold text-gray-700 mb-2">校园邮箱</label>
                        <input 
                            type="email" 
                            id="seller_email" 
                            name="seller_email" 
                            value="{{ old('seller_email') }}" 
                            required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        >
                    </div>
                    <div>
                        <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">商品分类</label>
                        <select 
                            id="category_id" 
                            name="category_id" 
                            required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        >
                            <option value="">请选择</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">价格 (¥)</label>
                        <input 
                            type="number" 
                            step="0.01" 
                            min="0" 
                            id="price" 
                            name="price" 
                            value="{{ old('price') }}" 
                            required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        >
                    </div>
                </div>

                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">标题</label>
                    <input 
                        id="title" 
                        name="title" 
                        value="{{ old('title') }}" 
                        placeholder="例如：九成新考研英语黄皮书" 
                        required
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                    >
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">详情描述</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        placeholder="成色、使用情况、打包赠品等" 
                        required
                        rows="4"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"
                    >{{ old('description') }}</textarea>
                </div>

                <div>
                    <label for="deal_place" class="block text-sm font-semibold text-gray-700 mb-2">交易地点（可选）</label>
                    <input 
                        id="deal_place" 
                        name="deal_place" 
                        value="{{ old('deal_place') }}"
                        placeholder="例如：东门奶茶店 / 图书馆一楼大厅 / 宿舍楼下公共区域"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                    >
                    <p class="text-gray-500 text-sm mt-2">建议选择人流量较大的校园公共区域，优先当面当场验货交易。</p>
                </div>

                <button 
                    type="submit" 
                    class="w-full md:w-auto px-8 py-3 bg-blue-600 text-white font-semibold rounded-full transition-all duration-200 ease-in-out hover:bg-blue-700 active:scale-95 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                >
                    发布商品
                </button>
            </form>
        </div>
    </section>
    </div>

    <style>
        /* Hide scrollbar for category pills */
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
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

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-8 left-1/2 transform -translate-x-1/2 z-50 pointer-events-none">
        <div id="toast" class="bg-black/80 text-white px-6 py-3 rounded-full shadow-lg opacity-0 translate-y-4 transition-all duration-300 ease-out pointer-events-auto">
            <span id="toast-message"></span>
        </div>
    </div>

    <script>
        // Staggered Fade-in Entry Animation
        document.addEventListener('DOMContentLoaded', function() {
            const productCards = document.querySelectorAll('.product-card-entry');
            
            productCards.forEach((card, index) => {
                setTimeout(() => {
                    // Remove initial invisible state
                    card.classList.remove('opacity-0', 'translate-y-8');
                    // Add visible state
                    card.classList.add('opacity-100', 'translate-y-0');
                }, index * 100); // 100ms delay between each card
            });
        });

        /**
         * Toggle Wishlist with Optimistic UI Update
         * @param {number} productId - The item ID
         * @param {HTMLElement} btnElement - The button element that was clicked
         */
        function toggleWishlist(productId, btnElement) {
            const heartIcon = btnElement.querySelector('.heart-icon');
            const isLiked = btnElement.getAttribute('data-liked') === 'true';
            
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
                showToast(isLiked ? '已取消收藏' : '已添加到心愿单');
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
    </script>
@endsection
