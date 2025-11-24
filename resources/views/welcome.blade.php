@extends('layouts.app')

@section('title', '校园易 - 首页总览')

@section('content')
<div class="bg-[#F9FAFB] min-h-screen">
    <!-- Hero Search Section -->
    <section class="pt-16 pb-12 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 md:p-10 mb-8">
                <div class="text-center mb-8">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">🔍 想找什么？</h2>
                    <p class="text-gray-500 text-lg">先选类型，再输入关键字，更快找到合适的闲置或技能</p>
                </div>

                <!-- Unified Search Bar -->
                <form id="homepage-search-form"
                      method="GET"
                      action="{{ route('items.index') }}"
                      data-item-url="{{ route('items.index') }}"
                      data-task-url="{{ route('tasks.index') }}">
                    <div class="relative mb-6">
                        <div class="flex items-center bg-gray-50 rounded-full border border-gray-200 shadow-inner overflow-visible focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent transition-all duration-300">
                            <!-- Custom Type Selector Dropdown -->
                            <div class="relative flex-shrink-0">
                                <!-- Hidden Input for Form Submission -->
                                <input type="hidden" name="type" id="search_type" value="item">
                                
                                <!-- Trigger Button -->
                                <button type="button" 
                                        id="type-dropdown-trigger"
                                        class="flex items-center gap-2 px-4 py-4 bg-gray-100 rounded-l-full text-gray-700 font-medium transition-all duration-200 ease-in-out hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                        aria-label="选择搜索类型"
                                        aria-expanded="false"
                                        aria-haspopup="true">
                                    <span id="type-display-text" class="flex items-center gap-2">
                                        <!-- Cube Icon for Items -->
                                        <svg id="type-display-icon" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="7" height="7"></rect>
                                            <rect x="14" y="3" width="7" height="7"></rect>
                                            <rect x="14" y="14" width="7" height="7"></rect>
                                            <rect x="3" y="14" width="7" height="7"></rect>
                                        </svg>
                                        <span id="type-display-label">找物品</span>
                                    </span>
                                    <!-- Chevron Down Icon -->
                                    <svg id="type-chevron" class="w-4 h-4 transition-transform duration-200 ease-out" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div id="type-dropdown-menu"
                                     class="absolute top-full left-0 mt-2 bg-white rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.2)] border border-gray-100 p-2 min-w-[180px] z-50 opacity-0 invisible -translate-y-2 scale-95 transition-all duration-200 ease-out">
                                    <!-- Option: 找物品 -->
                                    <button type="button"
                                            class="dropdown-option block w-full text-left px-4 py-3 rounded-xl text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors cursor-pointer flex items-center gap-3"
                                            data-value="item"
                                            data-label="找物品"
                                            data-icon="cube">
                                        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="7" height="7"></rect>
                                            <rect x="14" y="3" width="7" height="7"></rect>
                                            <rect x="14" y="14" width="7" height="7"></rect>
                                            <rect x="3" y="14" width="7" height="7"></rect>
                                        </svg>
                                        <span>找物品</span>
                                    </button>
                                    <!-- Option: 找服务 / 互助 -->
                                    <button type="button"
                                            class="dropdown-option block w-full text-left px-4 py-3 rounded-xl text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors cursor-pointer flex items-center gap-3"
                                            data-value="task"
                                            data-label="找服务 / 互助"
                                            data-icon="handshake">
                                        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                        <span>找服务 / 互助</span>
                                    </button>
                                </div>
                            </div>
                            <div class="w-px h-6 bg-gray-300"></div>
                            <!-- Search Input -->
                            <input id="q"
                                   name="q"
                                   type="text"
                                   placeholder="例如：iPad、吉他教学、考研辅导"
                                   aria-label="搜索关键字"
                                   class="flex-1 px-4 py-4 bg-transparent border-none outline-none text-gray-900 placeholder-gray-400">
                            <!-- Search Button -->
                            <button class="flex-shrink-0 px-6 py-4 bg-blue-600 text-white font-medium transition-all duration-200 ease-in-out hover:bg-blue-700 active:scale-95" type="submit" aria-label="搜索">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="7"></circle>
                                    <line x1="16.65" y1="16.65" x2="21" y2="21"></line>
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Hot Keywords Ticker -->
                @php
                    $hotKeywords = ['考研资料', '闲置自行车', '吉他谱', '四六级口语', '复试筹划'];
                @endphp
                <div class="mb-8">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <span class="text-sm font-semibold text-gray-600 whitespace-nowrap">热搜</span>
                        <div class="flex-1 overflow-hidden">
                            <div class="hot-ticker__track flex gap-4 animate-scroll">
                                @foreach(array_merge($hotKeywords, $hotKeywords) as $keyword)
                                    <span class="text-sm text-gray-500 whitespace-nowrap">热搜：{{ $keyword }}</span>
            @endforeach
                            </div>
                        </div>
        </div>
    </div>

                <!-- Category Pills -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">或按分类快速浏览</h3>
                    <div class="flex flex-wrap gap-3">
                        @forelse($categories as $category)
                            <a href="{{ route('items.index', ['category' => $category->id]) }}" 
                               class="category-pill-entry opacity-0 translate-y-8 transition-all duration-700 ease-out transform">
                                <span class="inline-block px-6 py-2.5 bg-gray-100 text-gray-700 rounded-full font-medium transition-all duration-200 ease-in-out hover:-translate-y-1 hover:bg-gray-200 hover:shadow-md">
                                    {{ $category->name }}
                                </span>
                            </a>
                        @empty
                            <span class="text-gray-400 text-sm">还没有分类，执行 `php artisan db:seed` 生成示例数据</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Items Section -->
    <section class="py-12 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">🔥 最新闲置</h2>
                    <p class="text-gray-500 text-lg">新鲜出炉的二手好物，连同卖家昵称</p>
                </div>
                <a href="{{ route('items.index') }}" 
                   class="px-6 py-3 bg-white text-gray-700 rounded-full font-medium border border-gray-200 transition-all duration-200 ease-in-out hover:bg-gray-50 hover:shadow-md hover:-translate-y-0.5">
                    查看全部 →
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($items as $item)
                    @php
                        $isSample = !($item instanceof \App\Models\Item);
                        $itemUrl = $isSample ? '#' : route('items.show', $item);
                        $imageSrc = $isSample
                            ? 'https://via.placeholder.com/400x300?text=Campus+Market'
                            : $item->image_url;
                    @endphp
                    <a href="{{ $itemUrl }}" 
                       class="group block home-card-entry opacity-0 translate-y-8 transition-all duration-700 ease-out transform"
                       @if($isSample) onclick="return false;" style="cursor: default; opacity: 0.6;" @endif>
                        <article class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm transition-all duration-300 ease-in-out hover:shadow-2xl hover:-translate-y-2 h-full flex flex-col">
                            <!-- Image Area -->
                            <div class="aspect-[4/3] bg-gray-100 overflow-hidden">
                                <img 
                                    src="{{ $imageSrc }}" 
                                    alt="{{ $item->title }}"
                                    class="w-full h-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-105"
                                >
                            </div>
                            
                            <!-- Content -->
                            <div class="p-5 flex-1 flex flex-col">
                                <!-- Category Badge -->
                                <span class="inline-block px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-full mb-3 self-start">
                                    {{ optional($item->category)->name ?? '未分类' }}
                                </span>
                                
                                <!-- Title -->
                                <h3 class="font-bold text-gray-900 text-lg mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors duration-200 flex-1">
                                    {{ $item->title }}
                                </h3>
                                
                                <!-- Price -->
                                <div class="mb-4">
                                    <span class="text-2xl font-bold text-red-500">¥{{ number_format($item->price, 2) }}</span>
    </div>

                                <!-- Footer: Seller & Location -->
                                <div class="pt-4 border-t border-gray-100 mt-auto">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white text-xs font-semibold">
                                                {{ mb_substr($item->user->name, 0, 1) }}
                                            </div>
                                            <span class="text-xs text-gray-600 font-medium">{{ $item->user->name }}</span>
                                        </div>
                                        @if($item->deal_place)
                                            <span class="text-xs text-gray-400 truncate max-w-[100px]">
                                                📍 {{ \Illuminate\Support\Str::limit($item->deal_place, 10) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </article>
                    </a>
                @empty
                    <div class="col-span-full text-center py-16">
                        <div class="inline-block p-8 bg-white rounded-2xl border border-gray-100 shadow-sm">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <p class="text-gray-500 text-lg mb-2">暂无商品</p>
                            <p class="text-gray-400 text-sm">欢迎前往"二手交易"页面发布</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Latest Help Section -->
    <section class="py-12 px-4 pb-16">
        <div class="max-w-6xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">🤝 校园能人 / 互助任务</h2>
                    <p class="text-gray-500 text-lg">吉他教学、代取快递、考研辅导……用技能和时间互相帮助</p>
                </div>
                <a href="{{ route('tasks.index') }}" 
                   class="px-6 py-3 bg-white text-gray-700 rounded-full font-medium border border-gray-200 transition-all duration-200 ease-in-out hover:bg-gray-50 hover:shadow-md hover:-translate-y-0.5">
                    我要发布互助 →
                </a>
            </div>

            <div class="space-y-4">
                @forelse($tasks as $task)
                    @php
                        $isSample = !($task instanceof \App\Models\Task);
                        $taskUrl = $isSample ? '#' : route('tasks.show', $task);
                    @endphp
                    <a href="{{ $taskUrl }}" 
                       class="block home-card-entry opacity-0 translate-y-8 transition-all duration-700 ease-out transform"
                       @if($isSample) onclick="return false;" style="cursor: default; opacity: 0.6;" @endif>
                        <article class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-md flex items-center justify-between gap-4">
                            <!-- Left: Avatar -->
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold text-base">
                                    {{ mb_substr($task->user->name, 0, 1) }}
                                </div>
                            </div>

                            <!-- Middle: Title & Description -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-4 mb-2">
                                    <h3 class="font-bold text-gray-900 text-lg line-clamp-1 group-hover:text-blue-600 transition-colors duration-200">
                                        {{ $task->title }}
                                    </h3>
                                    <!-- Status Pill -->
                                    <span class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-full {{ $task->status === 'completed' ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-700' }}">
                                        {{ $task->status === 'completed' ? '已完成' : '招募中' }}
                                    </span>
                                </div>
                                <p class="text-gray-600 text-sm line-clamp-2 mb-2">
                                    {{ \Illuminate\Support\Str::limit($task->content, 80) }}
                                </p>
                                <div class="flex items-center gap-4 text-xs text-gray-500">
                                    <span>发布人：{{ $task->user->name }}</span>
                                    @if($task->reward)
                                        <span class="inline-flex items-center gap-1">
                                            🎁 {{ $task->reward }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Right: Action Button -->
                            <div class="flex-shrink-0">
                                <button 
                                    class="px-5 py-2.5 bg-blue-600 text-white rounded-full text-sm font-medium transition-all duration-200 ease-in-out hover:bg-blue-700 active:scale-95 shadow-sm hover:shadow-md"
                                    onclick="event.preventDefault(); window.location.href='{{ $taskUrl }}'"
                                >
                                    查看详情
                                </button>
                            </div>
                        </article>
                    </a>
                @empty
                    <div class="text-center py-16">
                        <div class="inline-block p-8 bg-white rounded-2xl border border-gray-100 shadow-sm">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-500 text-lg mb-2">暂无互助任务</p>
                            <p class="text-gray-400 text-sm mb-6">前往"互助任务"页面试着发布一个吧</p>
                            <a href="{{ route('tasks.index') }}" 
                               class="inline-block px-6 py-3 bg-blue-600 text-white rounded-full font-medium transition-all duration-200 ease-in-out hover:bg-blue-700 active:scale-95 shadow-md hover:shadow-lg">
                                发布互助任务
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    </div>

<style>
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
    
    /* Hot Keywords Scroll Animation */
    @keyframes scroll {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-50%);
        }
    }
    
    .animate-scroll {
        animation: scroll 30s linear infinite;
    }
    
    .hot-ticker__track:hover .animate-scroll {
        animation-play-state: paused;
    }
</style>

<script>
    // Homepage Search Form Handler
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('homepage-search-form');
        if (!form) {
            return;
        }

        const itemUrl = form.dataset.itemUrl || form.action;
        const taskUrl = form.dataset.taskUrl || form.dataset.itemUrl || form.action;

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const typeField = form.elements.namedItem('type');
            const keywordField = form.elements.namedItem('q');
            const type = typeField ? (typeField.value || 'item') : 'item';
            const keyword = keywordField ? keywordField.value.trim() : '';

            const url = type === 'item' ? itemUrl : taskUrl;
            const params = new URLSearchParams();
            if (keyword) {
                params.set('q', keyword);
            }

            window.location.href = params.toString()
                ? `${url}?${params.toString()}`
                : url;
        });

        // Custom Dropdown Component Logic
        const dropdownTrigger = document.getElementById('type-dropdown-trigger');
        const dropdownMenu = document.getElementById('type-dropdown-menu');
        const hiddenInput = document.getElementById('search_type');
        const displayLabel = document.getElementById('type-display-label');
        const displayIcon = document.getElementById('type-display-icon');
        const chevronIcon = document.getElementById('type-chevron');
        const dropdownOptions = document.querySelectorAll('.dropdown-option');

        let isDropdownOpen = false;

        // Helper function to update icon by cloning from selected option
        function updateIcon(selectedOption) {
            const optionIcon = selectedOption.querySelector('svg');
            if (optionIcon && displayIcon) {
                // Clone the innerHTML of the SVG
                displayIcon.innerHTML = optionIcon.innerHTML;
            }
        }

        // Toggle dropdown
        function toggleDropdown() {
            isDropdownOpen = !isDropdownOpen;
            
            if (isDropdownOpen) {
                dropdownMenu.classList.remove('opacity-0', 'invisible', '-translate-y-2', 'scale-95');
                dropdownMenu.classList.add('opacity-100', 'visible', 'translate-y-0', 'scale-100');
                chevronIcon.style.transform = 'rotate(180deg)';
                dropdownTrigger.setAttribute('aria-expanded', 'true');
            } else {
                dropdownMenu.classList.remove('opacity-100', 'visible', 'translate-y-0', 'scale-100');
                dropdownMenu.classList.add('opacity-0', 'invisible', '-translate-y-2', 'scale-95');
                chevronIcon.style.transform = 'rotate(0deg)';
                dropdownTrigger.setAttribute('aria-expanded', 'false');
            }
        }

        // Close dropdown
        function closeDropdown() {
            if (isDropdownOpen) {
                isDropdownOpen = false;
                dropdownMenu.classList.remove('opacity-100', 'visible', 'translate-y-0', 'scale-100');
                dropdownMenu.classList.add('opacity-0', 'invisible', '-translate-y-2', 'scale-95');
                chevronIcon.style.transform = 'rotate(0deg)';
                dropdownTrigger.setAttribute('aria-expanded', 'false');
            }
        }

        // Handle option selection
        dropdownOptions.forEach(option => {
            option.addEventListener('click', function() {
                const value = this.dataset.value;
                const label = this.dataset.label;

                // Update hidden input
                hiddenInput.value = value;

                // Update display text
                displayLabel.textContent = label;

                // Update icon by cloning from selected option
                updateIcon(this);

                // Close dropdown
                closeDropdown();
            });
        });

        // Toggle on trigger click
        dropdownTrigger.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleDropdown();
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!dropdownTrigger.contains(e.target) && !dropdownMenu.contains(e.target)) {
                closeDropdown();
            }
        });

        // Close dropdown on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isDropdownOpen) {
                closeDropdown();
            }
        });

        // Silky Smooth Staggered Fade-in Animation (Waterfall Effect)
        const homeCards = document.querySelectorAll('.home-card-entry');
        const categoryPills = document.querySelectorAll('.category-pill-entry');
        
        // Animate category pills first
        categoryPills.forEach((pill, index) => {
            setTimeout(() => {
                pill.classList.remove('opacity-0', 'translate-y-8');
                pill.classList.add('opacity-100', 'translate-y-0');
            }, index * 80); // 80ms delay between each pill
        });

        // Animate product and task cards
        homeCards.forEach((card, index) => {
            setTimeout(() => {
                // Remove initial invisible state
                card.classList.remove('opacity-0', 'translate-y-8');
                // Add visible state
                card.classList.add('opacity-100', 'translate-y-0');
            }, (categoryPills.length * 80) + (index * 100)); // Start after pills, 100ms delay between each card
        });
    });
</script>
@endsection
