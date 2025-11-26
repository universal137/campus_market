@extends('layouts.app')

@section('title', '校园易 - 首页总览')

@section('content')
<div id="main-content" class="bg-[#F9FAFB] min-h-screen">
    <!-- Hero Search Section -->
    <section class="py-16 px-4">
        <div id="transition-blob" class="fixed rounded-full pointer-events-none z-[9999] transform scale-0 opacity-0 transition-all duration-500 ease-[cubic-bezier(0.4,0,0.2,1)]"></div>
        <div class="max-w-4xl mx-auto space-y-8">
            <div class="bg-white rounded-full h-20 shadow-[0_15px_45px_-15px_rgba(0,0,0,0.3)] border border-gray-100 px-5 flex items-center gap-4">
                <div class="relative flex-shrink-0">
                    <div class="w-48 bg-gray-100 rounded-full p-1 flex items-center gap-1 relative overflow-hidden">
                        <div id="capsule-indicator" class="absolute inset-y-1 left-1 w-[calc(50%-4px)] bg-white rounded-full shadow transition-transform duration-300 ease-out"></div>
                        <button type="button" id="capsule-market" onclick="switchSearchMode('market')" class="relative z-10 flex-1 text-sm font-semibold text-gray-700 py-2 rounded-full">📦 找闲置</button>
                        <button type="button" id="capsule-tasks" onclick="switchSearchMode('tasks')" class="relative z-10 flex-1 text-sm font-semibold text-gray-500 py-2 rounded-full">🤝 找互助</button>
                    </div>
                </div>

                <form id="dual-mode-form" action="{{ route('items.index') }}" method="GET" class="flex-1 flex items-center gap-4">
                    <input type="hidden" id="dual-mode-input" name="mode" value="market">
                    <input type="text" id="dual-search-input" name="q" class="flex-1 bg-transparent border-none focus:outline-none text-lg text-gray-900 placeholder-gray-400 transition-opacity duration-300" placeholder="搜索 iPad、教材、二手好物..." autocomplete="off">
                    <button type="submit" id="dual-search-button" class="w-12 h-12 rounded-full text-white flex items-center justify-center shadow-lg shadow-blue-200 bg-blue-600 hover:scale-105 transition-all duration-300">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </form>
            </div>

            <div class="relative">
                <div class="absolute left-0 top-0 bottom-0 w-10 bg-gradient-to-r from-[#F9FAFB] to-transparent pointer-events-none"></div>
                <div class="absolute right-0 top-0 bottom-0 w-10 bg-gradient-to-l from-[#F9FAFB] to-transparent pointer-events-none"></div>
                <div id="dual-hot-tags" class="flex overflow-x-auto no-scrollbar gap-3 py-2 px-2 transition-opacity duration-300"></div>
            </div>
        </div>
    </section>

    @if(isset($recommendedProducts) && $recommendedProducts->count() > 0)
        <section class="py-12">
            <div class="max-w-6xl mx-auto mb-6 px-4 flex items-center gap-2">
                <h2 class="text-2xl font-bold text-gray-900">✨ 猜你喜欢</h2>
                <span class="text-sm text-gray-400 bg-gray-100 px-2 py-1 rounded-full">精选推荐</span>
            </div>
            <div class="flex overflow-x-auto snap-x snap-mandatory gap-6 pb-8 pt-4 px-4 no-scrollbar" style="scroll-behavior: smooth;">
                @foreach($recommendedProducts as $product)
                    <a href="{{ route('items.show', $product) }}" class="min-w-[280px] md:min-w-[320px] snap-center bg-white rounded-3xl shadow-[0_8px_30px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden group hover:shadow-[0_8px_30px_rgba(0,0,0,0.15)] transition-all duration-500 hover:-translate-y-2 animate-fade-in-up cursor-pointer">
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img src="{{ $product->image_url }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $product->title }}">
                            <div class="absolute bottom-3 left-3 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-sm font-bold text-gray-900 shadow-sm">
                                ¥{{ number_format($product->price, 2) }}
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="font-bold text-gray-900 truncate mb-1">{{ $product->title }}</h3>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <div class="w-5 h-5 rounded-full bg-gray-200 overflow-hidden">
                                    <img src="{{ $product->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($product->user->name) }}" class="w-full h-full object-cover" alt="{{ $product->user->name }}">
                                </div>
                                <span>{{ $product->user->name }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

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
                        $status = $isSample ? 'active' : ($item->status ?? 'active');
                        $isActive = $status === 'active';
                    @endphp
                    <a href="{{ $itemUrl }}" 
                       class="group block animate-fade-in-up transition-all duration-700 ease-out transform"
                       @if($isSample) onclick="return false;" style="cursor: default; opacity: 0.6;" @endif>
                        <article class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm transition-all duration-300 ease-in-out h-full flex flex-col {{ $isActive ? 'hover:shadow-2xl hover:-translate-y-2' : 'opacity-95' }}">
                            <!-- Image Area -->
                            <div class="aspect-[4/3] bg-gray-100 overflow-hidden relative">
                                <img 
                                    src="{{ $imageSrc }}" 
                                    alt="{{ $item->title }}"
                                    class="w-full h-full object-cover transition-transform duration-300 ease-in-out {{ $isActive ? 'group-hover:scale-105' : '' }} {{ $status === 'sold' ? 'grayscale' : '' }}"
                                >
                                @if($status === 'sold')
                                    <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px] flex items-center justify-center z-10 pointer-events-none">
                                        <span class="text-white font-semibold border-2 border-white px-4 py-1 rounded-full text-sm">已售出</span>
                                    </div>
                                @elseif($status === 'pending')
                                    <div class="absolute inset-0 bg-blue-900/40 backdrop-blur-[1px] flex items-center justify-center z-10 pointer-events-none">
                                        <span class="text-white font-semibold text-sm px-4 py-1 rounded-full">交易中</span>
                                    </div>
                                @endif
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
                       class="block animate-fade-in-up transition-all duration-700 ease-out transform"
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
        line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .animate-fade-in-up {
        opacity: 0;
        animation: fadeInUp 0.6s ease-out forwards;
    }
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
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
    (function () {
        const form = document.getElementById('dual-mode-form');
        const input = document.getElementById('dual-search-input');
        const button = document.getElementById('dual-search-button');
        const modeInput = document.getElementById('dual-mode-input');
        const indicator = document.getElementById('capsule-indicator');
        const marketBtn = document.getElementById('capsule-market');
        const taskBtn = document.getElementById('capsule-tasks');
        const tagsWrap = document.getElementById('dual-hot-tags');
        const blob = document.getElementById('transition-blob');
        const mainContent = document.getElementById('main-content');
        if (!form || !input || !button) return;

        const routes = {
            market: "{{ route('items.index') }}",
            tasks: "{{ route('tasks.index') }}"
        };

        const config = {
            market: {
                placeholder: '搜索 iPad、教材、二手好物...',
                buttonClasses: ['bg-blue-600', 'shadow-blue-200'],
                action: routes.market,
                tags: ['# iPad 平板', '# 考研教材', '# 折叠自行车', '# 耳机音箱', '# Kindle', '# 宿舍小物']
            },
            tasks: {
                placeholder: '搜索 代取快递、高数辅导...',
                buttonClasses: ['bg-orange-500', 'shadow-orange-200'],
                action: routes.tasks,
                tags: ['# 代取快递', '# 高数辅导', '# 搬家帮手', '# PPT 美化', '# 直播助理', '# 兼职互助']
            }
        };

        let currentMode = 'market';

        function renderTags(list) {
            if (!tagsWrap) return;
            tagsWrap.classList.add('opacity-0');
            setTimeout(() => {
                tagsWrap.innerHTML = list.map(tag => `
                    <button type="button" class="bg-gray-50 hover:bg-gray-100 rounded-full px-4 py-1.5 text-sm text-gray-600 transition-colors">${tag}</button>
                `).join('');
                tagsWrap.classList.remove('opacity-0');
            }, 150);
        }

        window.switchSearchMode = function (mode, force = false) {
            if (!config[mode] || (!force && mode === currentMode)) return;
            currentMode = mode;
            const cfg = config[mode];

            modeInput.value = mode;
            form.action = cfg.action;

            input.classList.add('opacity-0');
            setTimeout(() => {
                input.placeholder = cfg.placeholder;
                input.classList.remove('opacity-0');
            }, 150);

            indicator.style.transform = mode === 'market' ? 'translateX(0)' : 'translateX(100%)';

            marketBtn.classList.toggle('text-gray-900', mode === 'market');
            marketBtn.classList.toggle('text-gray-500', mode !== 'market');
            taskBtn.classList.toggle('text-gray-900', mode === 'tasks');
            taskBtn.classList.toggle('text-gray-500', mode !== 'tasks');

            button.classList.remove('bg-blue-600', 'bg-orange-500', 'shadow-blue-200', 'shadow-orange-200');
            button.classList.add(...cfg.buttonClasses);

            renderTags(cfg.tags);
        };

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const query = input.value.trim();
            const target = query ? `${form.action}?q=${encodeURIComponent(query)}` : form.action;

            if (blob) {
                const rect = button.getBoundingClientRect();
                const size = Math.max(window.innerWidth, window.innerHeight) * 2.5;
                blob.style.width = `${size}px`;
                blob.style.height = `${size}px`;
                blob.style.top = `${rect.top + rect.height / 2 - size / 2}px`;
                blob.style.left = `${rect.left + rect.width / 2 - size / 2}px`;

                blob.className = "fixed rounded-full pointer-events-none z-[9999] transform scale-0 opacity-0 transition-all duration-500 ease-[cubic-bezier(0.4,0,0.2,1)]";
                if (currentMode === 'market') {
                    blob.classList.add('bg-gradient-to-br', 'from-blue-500', 'to-cyan-400');
                } else {
                    blob.classList.add('bg-gradient-to-br', 'from-orange-400', 'to-yellow-300');
                }

                void blob.offsetWidth;
                blob.classList.remove('scale-0', 'opacity-0');
                blob.classList.add('scale-[300]', 'opacity-100');
            }

            if (mainContent) {
                mainContent.classList.add('scale-95', 'opacity-0', 'transition-all', 'duration-300');
            }

            setTimeout(() => {
                window.location.href = target;
            }, 350);
        });

        switchSearchMode('market', true);
    })();
</script>
@endsection
