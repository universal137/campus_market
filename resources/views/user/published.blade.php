@extends('layouts.app')

@section('title', '管理我的发布 · 校园易')

@section('content')
<div class="bg-[#F9FAFB] min-h-screen">
    <!-- Header Section -->
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">管理我的发布</h1>
                    <p class="text-gray-500 text-base">管理您在校园出售的闲置物品和互助任务</p>
                </div>
                <div class="flex gap-3">
                <button 
                    type="button"
                    onclick="openProductModal()"
                    class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-full transition-all duration-200 ease-in-out hover:bg-blue-700 active:scale-95 shadow-md hover:shadow-lg whitespace-nowrap"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    发布新商品
                </button>
                    <button 
                        type="button"
                        onclick="openTaskModal()"
                        class="inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white font-medium rounded-full transition-all duration-200 ease-in-out hover:bg-green-700 active:scale-95 shadow-md hover:shadow-lg whitespace-nowrap"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        发布新任务
                    </button>
                </div>
            </div>

            <!-- Tab Switcher -->
            <div class="mt-6 flex gap-2">
                <button 
                    id="tab-products"
                    onclick="switchTab('products')"
                    class="px-6 py-2.5 rounded-full font-medium transition-all duration-200 bg-blue-600 text-white shadow-md"
                >
                    闲置商品
                </button>
                <button 
                    id="tab-tasks"
                    onclick="switchTab('tasks')"
                    class="px-6 py-2.5 rounded-full font-medium transition-all duration-200 bg-gray-100 text-gray-600 hover:bg-gray-200"
                >
                    互助任务
                </button>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Products Tab -->
        <div id="view-products">
        @if(isset($products) && $products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8">
                @foreach($products as $index => $product)
                    <div 
                        id="product-card-{{ $product->id }}"
                        class="product-card-entry opacity-0 translate-y-8 transition-all duration-700 ease-out transform"
                        style="animation-delay: {{ $index * 50 }}ms;"
                    >
                        <article class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm transition-all duration-300 ease hover:shadow-lg hover:-translate-y-1 relative group">
                            <!-- Image Area -->
                            <div class="aspect-square bg-gray-100 overflow-hidden relative">
                                <a href="{{ route('items.show', $product) }}" class="block w-full h-full">
                                    <img 
                                        src="{{ $product->image_url }}" 
                                        alt="{{ $product->title }}"
                                        class="w-full h-full object-cover transition-transform duration-300 ease group-hover:scale-105"
                                    >
                                </a>
                                
                                <!-- Status Badge (Top-Left) -->
                                <div class="absolute top-3 left-3 z-10">
                                    @if($product->status === 'sold' || $product->status === '已售出' || $product->status === 'sold_out')
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-gray-500 text-white shadow-sm">
                                            已售出
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-green-500 text-white shadow-sm">
                                            在售
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Info Area -->
                            <div class="p-4">
                                <h3 class="text-base font-semibold text-gray-900 mb-2 line-clamp-2 min-h-[2.5rem]">
                                    <a href="{{ route('items.show', $product) }}" class="hover:text-blue-600 transition-colors">
                                        {{ $product->title }}
                                    </a>
                                </h3>
                                <p class="text-xl font-bold text-red-500 mb-0">
                                    ¥{{ number_format($product->price, 2) }}
                                </p>
                            </div>
                            
                            <!-- Management Actions (Bottom Border) -->
                            <div class="border-t border-gray-100 flex divide-x divide-gray-100">
                                <!-- Edit Button -->
                                <a 
                                    href="{{ route('items.edit', $product) }}"
                                    class="flex-1 flex items-center justify-center gap-2 py-3 px-4 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors duration-200"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    编辑
                                </a>
                                
                                <!-- Delete Button -->
                                <button 
                                    type="button"
                                    onclick="askDelete('product', {{ $product->id }})"
                                    class="flex-1 flex items-center justify-center gap-2 py-3 px-4 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors duration-200"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    删除
                                </button>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        @else
                <!-- Empty State for Products -->
            <div class="text-center py-20">
                <div class="inline-block p-12 bg-white rounded-3xl border border-gray-100 shadow-sm mb-6">
                    <div class="text-8xl mb-6">📦</div>
                    <p class="text-gray-500 text-xl font-medium mb-2">还没有发布任何商品</p>
                    <p class="text-gray-400 text-sm mb-8">发布第一件商品，让闲置物品找到新主人</p>
                    <a 
                        href="{{ route('items.index') }}#publish-form"
                        class="inline-block px-8 py-3.5 bg-blue-600 text-white rounded-full font-medium transition-all duration-200 ease-in-out hover:bg-blue-700 active:scale-95 shadow-md hover:shadow-lg"
                    >
                        发布第一件商品
                    </a>
                </div>
            </div>
        @endif
        </div>

        <!-- Tasks Tab -->
        <div id="view-tasks" class="hidden">
            @if(isset($tasks) && $tasks->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($tasks as $index => $task)
                        <div 
                            id="task-card-{{ $task->id }}"
                            class="task-card-entry opacity-0 translate-y-8 transition-all duration-700 ease-out transform"
                            data-task-id="{{ $task->id }}"
                            style="animation-delay: {{ $index * 50 }}ms;"
                        >
                            <article class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm transition-all duration-500 ease hover:shadow-lg h-full flex flex-col relative">
                                <!-- Hidden Stamp Watermark -->
                                <div 
                                    id="stamp-{{ $task->id }}" 
                                    class="hidden absolute right-4 top-10 border-4 border-gray-300 text-gray-300 font-black text-4xl px-4 py-2 rounded-lg -rotate-12 select-none z-10 opacity-0 transform scale-150 transition-all duration-300"
                                >
                                    已完成
                                </div>

                                <!-- Task Header -->
                                <div class="p-6 flex-1">
                                    <!-- Status Badge -->
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-2">
                                            @if($task->status === 'open' || $task->status === 'active')
                                                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                                <span class="text-xs text-gray-500 font-medium" id="task-status-{{ $task->id }}">招募中</span>
                                            @else
                                                <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                                <span class="text-xs text-gray-400 font-medium" id="task-status-{{ $task->id }}">已完成</span>
                                            @endif
                                        </div>
                                        <span class="text-xs text-gray-400">{{ $task->created_at->diffForHumans() }}</span>
                                    </div>

                                    <!-- Task Title -->
                                    <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                                        <a href="{{ route('tasks.show', $task) }}" class="hover:text-blue-600 transition-colors">
                                            {{ $task->title }}
                                        </a>
                                    </h3>

                                    <!-- Task Content -->
                                    <p class="text-gray-600 text-sm line-clamp-3 leading-relaxed mb-4">
                                        {{ $task->content }}
                                    </p>

                                    <!-- Reward Badge -->
                                    @if($task->reward)
                                        <div class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                            🎁 {{ $task->reward }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Management Footer -->
                                <div class="border-t border-gray-100 bg-gray-50 px-6 py-4">
                                    <div class="flex items-center justify-between gap-3">
                                        <!-- View Button -->
                                        <a 
                                            href="{{ route('tasks.show', $task) }}"
                                            class="flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            查看
                                        </a>

                                        <div class="flex items-center gap-3">
                                            <!-- Mark Done Button (Only show if active) -->
                                            @if($task->status === 'open' || $task->status === 'active')
                                                <button 
                                                    type="button"
                                                    id="complete-btn-{{ $task->id }}"
                                                    onclick="askConfirmation({{ $task->id }}, this)"
                                                    class="flex items-center gap-2 text-sm font-medium text-green-600 hover:text-green-700 transition-colors"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    结束招募
                                                </button>
                                            @endif

                                            <!-- Delete Button -->
                                            <button 
                                                type="button"
                                                onclick="askDelete('task', {{ $task->id }})"
                                                class="flex items-center gap-2 text-sm font-medium text-red-600 hover:text-red-700 transition-colors"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                删除
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State for Tasks -->
                <div class="text-center py-20">
                    <div class="inline-block p-12 bg-white rounded-3xl border border-gray-100 shadow-sm mb-6">
                        <div class="text-8xl mb-6">🤝</div>
                        <p class="text-gray-500 text-xl font-medium mb-2">还没有发布任何互助任务</p>
                        <p class="text-gray-400 text-sm mb-8">发布第一个任务，寻找志同道合的同学</p>
                        <a 
                            href="{{ route('tasks.index') }}"
                            class="inline-block px-8 py-3.5 bg-green-600 text-white rounded-full font-medium transition-all duration-200 ease-in-out hover:bg-green-700 active:scale-95 shadow-md hover:shadow-lg"
                        >
                            发布第一个任务
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@php
    $allCategories = \App\Models\Category::orderBy('name')->get();
@endphp

<!-- Product Publish Modal -->
<div id="productModal" class="fixed inset-0 z-[9999] hidden items-center justify-center overflow-y-auto px-4 py-6">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeProductModal()"></div>
    <div class="relative w-full max-w-3xl bg-white rounded-3xl shadow-2xl p-8 transform transition-all m-auto" onclick="event.stopPropagation()">
        <button type="button" onclick="closeProductModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 p-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">发布闲置商品</h2>
        <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">商品实拍图</label>
                <div id="product-dropzone" class="relative h-64 rounded-3xl border-2 border-dashed border-gray-200 bg-gray-50 flex items-center justify-center text-center cursor-pointer transition-all duration-300 hover:border-blue-400 hover:bg-white" onclick="document.getElementById('product-image-input').click()">
                    <div id="product-dropzone-placeholder" class="flex flex-col items-center gap-3">
                        <div class="w-16 h-16 rounded-2xl bg-white shadow-inner flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h1.5a2 2 0 001.8-1.1l.7-1.4A2 2 0 0110.7 3h2.6a2 2 0 011.7.5l1.5 1.4A2 2 0 0017.2 6H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-800 font-semibold">上传实拍图</p>
                            <p class="text-sm text-gray-400">支持 JPG / PNG / WEBP · 建议 4:3</p>
                        </div>
                    </div>
                    <img id="product-preview" src="" alt="商品图片预览" class="absolute inset-0 w-full h-full object-cover rounded-3xl hidden">
                    <button type="button" id="product-remove-btn" class="hidden absolute top-4 right-4 bg-black/60 text-white rounded-full p-2 hover:bg-black/80 transition" onclick="resetProductImage(event)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <input type="file" id="product-image-input" name="image" accept="image/jpeg,image/jpg,image/png,image/webp" class="hidden" onchange="handleProductImageSelect(event)">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">联系人昵称</label>
                    <input name="seller_name" value="{{ old('seller_name') }}" required class="w-full bg-gray-50 border-0 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all" placeholder="如 小向日葵">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">校园邮箱</label>
                    <input type="email" name="seller_email" value="{{ old('seller_email') }}" required class="w-full bg-gray-50 border-0 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all" placeholder="example@campus.edu">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">商品分类</label>
                    <select name="category_id" required class="w-full bg-gray-50 border-0 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                        <option value="">请选择分类</option>
                        @foreach($allCategories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">标价 (¥)</label>
                    <input type="number" step="0.01" min="0" name="price" value="{{ old('price') }}" required class="w-full bg-gray-50 border-0 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all" placeholder="例如 120">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">商品标题</label>
                <input name="title" value="{{ old('title') }}" required class="w-full bg-gray-50 border-0 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all" placeholder="例：九成新 iPad Pro 11 寸">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">商品描述</label>
                <textarea name="description" rows="4" required class="w-full bg-gray-50 border-0 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all" placeholder="介绍成色、配件、交易方式等">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">交易地点</label>
                <input name="deal_place" value="{{ old('deal_place') }}" class="w-full bg-gray-50 border-0 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all" placeholder="如：图书馆东门">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">地图定位</label>
                <div id="product-map" class="h-72 w-full rounded-2xl border border-gray-200 overflow-hidden"></div>
                <input type="hidden" name="lat" id="product-lat" value="{{ old('lat') }}">
                <input type="hidden" name="lng" id="product-lng" value="{{ old('lng') }}">
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeProductModal()" class="px-6 py-3 rounded-full bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition">取消</button>
                <button type="submit" class="px-8 py-3 rounded-full bg-blue-600 text-white font-semibold hover:bg-blue-700 transition shadow-lg shadow-blue-200">发布商品</button>
            </div>
        </form>
    </div>
</div>

<!-- Task Publish Modal -->
<div id="taskModal" class="fixed inset-0 z-[9999] hidden items-center justify-center overflow-y-auto px-4 py-6">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeTaskModal()"></div>
    <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl p-8 transform transition-all m-auto" onclick="event.stopPropagation()">
        <button type="button" onclick="closeTaskModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 p-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">发布互助任务</h2>
        <form id="task-modal-form" action="{{ route('tasks.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">联系人昵称</label>
                    <input name="publisher_name" value="{{ old('publisher_name') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="请输入您的昵称">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">校园邮箱</label>
                    <input type="email" name="publisher_email" value="{{ old('publisher_email') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="example@campus.edu">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">奖励（可选）</label>
                <input name="reward" value="{{ old('reward') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="如 10 元奶茶/校园币">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">任务标题</label>
                <input name="title" value="{{ old('title') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="例如：需要代取快递">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">任务详情</label>
                <textarea name="content" rows="5" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none" placeholder="任务背景、时间地点、注意事项等">{{ old('content') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">任务地点</label>
                <div class="flex gap-2">
                    <input id="task-location-input" type="text" placeholder="输入地点 (如: 图书馆 正门)" class="flex-1 px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    <button type="button" id="task-location-search" class="px-5 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">搜索</button>
                </div>
                <div id="task-map" class="h-72 w-full rounded-2xl mt-2 overflow-hidden border border-gray-200"></div>
                <input type="hidden" name="lat" id="task-lat" value="{{ old('lat') }}">
                <input type="hidden" name="lng" id="task-lng" value="{{ old('lng') }}">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeTaskModal()" class="px-6 py-3 rounded-full bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition">取消</button>
                <button type="submit" class="px-8 py-3 rounded-full bg-blue-600 text-white font-semibold hover:bg-blue-700 transition shadow-lg">发布任务</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Form (Hidden) -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    let productMapInstance = null;
    let productMapMarker = null;
    let taskModalMapInstance = null;
    let taskModalMarker = null;
    let deleteType = null;
    let deleteId = null;
    /**
     * Switch between Products and Tasks tabs
     */
    function switchTab(tab) {
        const productsTab = document.getElementById('tab-products');
        const tasksTab = document.getElementById('tab-tasks');
        const productsView = document.getElementById('view-products');
        const tasksView = document.getElementById('view-tasks');

        if (tab === 'products') {
            // Activate products tab
            productsTab.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
            productsTab.classList.add('bg-blue-600', 'text-white', 'shadow-md');
            
            tasksTab.classList.remove('bg-blue-600', 'text-white', 'shadow-md');
            tasksTab.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');

            // Show products view
            productsView.classList.remove('hidden');
            tasksView.classList.add('hidden');
        } else {
            // Activate tasks tab
            tasksTab.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
            tasksTab.classList.add('bg-blue-600', 'text-white', 'shadow-md');
            
            productsTab.classList.remove('bg-blue-600', 'text-white', 'shadow-md');
            productsTab.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');

            // Show tasks view
            tasksView.classList.remove('hidden');
            productsView.classList.add('hidden');
        }
    }

    function askDelete(type, id) {
        deleteType = type;
        deleteId = id;
        const modal = document.getElementById('deleteModal');
        const modalCard = modal.querySelector('.delete-modal-card');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modalCard.classList.remove('scale-95', 'opacity-0');
            modalCard.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const modalCard = modal.querySelector('.delete-modal-card');
        modalCard.classList.remove('scale-100', 'opacity-100');
        modalCard.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 180);
    }

    function confirmDelete() {
        if (!deleteType || !deleteId) {
            return;
        }
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                         document.querySelector('input[name="_token"]')?.value;
        const endpoint = deleteType === 'product' ? `/items/${deleteId}` : `/tasks/${deleteId}`;
        const card = document.getElementById(`${deleteType}-card-${deleteId}`);

        fetch(endpoint, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => { throw new Error(text || '删除失败'); });
            }
            return response.json();
        })
        .then(() => {
            closeDeleteModal();
            if (card) {
                card.style.transition = 'transform 0.5s ease, opacity 0.5s ease, margin 0.5s ease';
                card.style.transform = 'scale(0.9) translateY(-20px)';
                card.style.opacity = '0';
                card.style.marginBottom = '-100px';
                setTimeout(() => {
                    card.remove();
                    const gridSelector = deleteType === 'product' ? '#view-products .grid' : '#view-tasks .grid';
                    const grid = document.querySelector(gridSelector);
                    if (grid && grid.children.length === 0) {
                        location.reload();
                    }
                }, 500);
            }
        })
        .catch(error => {
            console.error('Delete failed:', error);
            alert('删除失败，请稍后重试');
            closeDeleteModal();
        })
        .finally(() => {
            deleteType = null;
            deleteId = null;
        });
    }

    // Global variables for confirmation modal
    let targetTaskId = null;
    let targetBtn = null;

    /**
     * Show confirmation modal
     */
    function askConfirmation(id, btn) {
        targetTaskId = id;
        targetBtn = btn;

        const modal = document.getElementById('confirmModal');
        const modalCard = modal.querySelector('.modal-card');
        
        // Show modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Trigger animation
        setTimeout(() => {
            modalCard.classList.remove('scale-95', 'opacity-0');
            modalCard.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    /**
     * Confirm action and proceed
     */
    function confirmAction() {
        if (targetTaskId && targetBtn) {
            closeConfirmModal();
            markTaskCompleted(targetTaskId, targetBtn);
        }
    }

    /**
     * Close confirmation modal
     */
    function closeConfirmModal() {
        const modal = document.getElementById('confirmModal');
        const modalCard = modal.querySelector('.modal-card');
        
        // Reverse animation
        modalCard.classList.remove('scale-100', 'opacity-100');
        modalCard.classList.add('scale-95', 'opacity-0');
        
        // Hide modal after animation
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            targetTaskId = null;
            targetBtn = null;
        }, 200);
    }

    /**
     * Mark Task as Completed with Stamp Animation
     */
    function markTaskCompleted(taskId, btnElement) {
        // Step 1: Show Loading Spinner on button
        const originalContent = btnElement.innerHTML;
        btnElement.disabled = true;
        btnElement.innerHTML = `
            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            处理中...
        `;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                         document.querySelector('input[name="_token"]')?.value;

        // Step 2: AJAX request to backend
        const formData = new FormData();
        formData.append('_method', 'PATCH');
        formData.append('_token', csrfToken);

        fetch(`/tasks/${taskId}/complete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('操作失败');
        })
        .then(data => {
            if (data.status === 'success') {
                // Step 3A: The Stamp Animation
                const stamp = document.getElementById(`stamp-${taskId}`);
                const card = document.getElementById(`task-card-${taskId}`);
                
                if (stamp && card) {
                    // Remove hidden class and trigger stamp animation
                    stamp.classList.remove('hidden');
                    stamp.classList.add('animate-stamp');
                    
                    // Step 3B: The Fade - Add grayscale and opacity to card
                    setTimeout(() => {
                        card.classList.add('opacity-75', 'grayscale');
                        card.querySelector('article').classList.remove('bg-white');
                        card.querySelector('article').classList.add('bg-gray-50');
                    }, 200);
                }

                // Step 3C: The UI Update
                // Update status badge
                const statusBadge = document.getElementById(`task-status-${taskId}`);
                const statusDot = statusBadge?.previousElementSibling;
                
                if (statusBadge && statusDot) {
                    statusDot.classList.remove('bg-green-500', 'animate-pulse');
                    statusDot.classList.add('bg-gray-400');
                    statusBadge.textContent = '已完成';
                    statusBadge.classList.remove('text-gray-500');
                    statusBadge.classList.add('text-gray-400');
                }

                // Hide "结束招募" button
                if (btnElement) {
                    btnElement.style.display = 'none';
                }
            }
        })
        .catch(error => {
            console.error('Error marking task as completed:', error);
            alert('操作失败，请重试');
            
            // Reset button state on error
            btnElement.disabled = false;
            btnElement.innerHTML = originalContent;
        });
    }

    // Staggered fade-in animation on page load
    document.addEventListener('DOMContentLoaded', function() {
        const productCards = document.querySelectorAll('.product-card-entry');
        productCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 50);
        });

        const taskCards = document.querySelectorAll('.task-card-entry');
        taskCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 50);
        });
    });

    function openProductModal() {
        const modal = document.getElementById('productModal');
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            initializeProductMap();
            if (productMapInstance) {
                productMapInstance.invalidateSize();
            }
        }, 150);
    }

    function closeProductModal() {
        const modal = document.getElementById('productModal');
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    function initializeProductMap() {
        const mapElement = document.getElementById('product-map');
        if (!mapElement || typeof L === 'undefined') {
            return;
        }
        const latInput = document.getElementById('product-lat');
        const lngInput = document.getElementById('product-lng');
        const defaultLatLng = [39.9042, 116.4074];
        const initialLat = parseFloat(latInput?.value) || defaultLatLng[0];
        const initialLng = parseFloat(lngInput?.value) || defaultLatLng[1];

        if (productMapInstance) {
            productMapInstance.setView([initialLat, initialLng], 15);
            productMapMarker.setLatLng([initialLat, initialLng]);
            setTimeout(() => productMapInstance.invalidateSize(), 150);
            return;
        }

        productMapInstance = L.map(mapElement, {
            center: [initialLat, initialLng],
            zoom: 15,
            dragging: true,
            zoomControl: true
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(productMapInstance);

        productMapMarker = L.marker([initialLat, initialLng], { draggable: true }).addTo(productMapInstance);
        productMapMarker.on('dragend', () => updateProductLatLng(productMapMarker.getLatLng()));
        productMapInstance.on('click', (event) => {
            productMapMarker.setLatLng(event.latlng);
            updateProductLatLng(event.latlng);
        });

        setTimeout(() => productMapInstance.invalidateSize(), 150);
    }

    function updateProductLatLng(latLng) {
        const latInput = document.getElementById('product-lat');
        const lngInput = document.getElementById('product-lng');
        if (!latInput || !lngInput || !latLng) return;
        latInput.value = latLng.lat.toFixed(8);
        lngInput.value = latLng.lng.toFixed(8);
    }

    function handleProductImageSelect(event) {
        const file = event.target.files[0];
        if (!file) return;
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            alert('请上传 JPG、PNG 或 WEBP 格式的图片');
            event.target.value = '';
            return;
        }
        const maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
            alert('图片大小不能超过 5MB');
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            const { dropzone, preview, placeholder, removeBtn } = getProductHeroElements();
            if (!dropzone || !preview || !placeholder || !removeBtn) return;
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('opacity-0');
            removeBtn.classList.remove('hidden');
            dropzone.classList.remove('border-dashed', 'border-gray-200');
            dropzone.classList.add('border-blue-200');
        };
        reader.readAsDataURL(file);
    }

    function resetProductImage(event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        const { dropzone, preview, placeholder, removeBtn, input } = getProductHeroElements();
        if (!dropzone || !preview || !placeholder || !removeBtn || !input) return;
        preview.src = '';
        preview.classList.add('hidden');
        placeholder.classList.remove('opacity-0');
        removeBtn.classList.add('hidden');
        input.value = '';
        dropzone.classList.add('border-dashed', 'border-gray-200');
        dropzone.classList.remove('border-blue-200');
    }

    function getProductHeroElements() {
        return {
            dropzone: document.getElementById('product-dropzone'),
            preview: document.getElementById('product-preview'),
            placeholder: document.getElementById('product-dropzone-placeholder'),
            removeBtn: document.getElementById('product-remove-btn'),
            input: document.getElementById('product-image-input')
        };
    }

    function openTaskModal() {
        const modal = document.getElementById('taskModal');
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            initializeTaskModalMap();
            if (taskModalMapInstance) {
                taskModalMapInstance.invalidateSize();
            }
        }, 150);
    }

    function closeTaskModal() {
        const modal = document.getElementById('taskModal');
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    function initializeTaskModalMap() {
        const mapElement = document.getElementById('task-map');
        if (!mapElement || typeof L === 'undefined') {
            return;
        }
        const latInput = document.getElementById('task-lat');
        const lngInput = document.getElementById('task-lng');
        const defaultLatLng = [36.061089, 103.834304];
        const initialLat = parseFloat(latInput?.value) || defaultLatLng[0];
        const initialLng = parseFloat(lngInput?.value) || defaultLatLng[1];

        if (taskModalMapInstance) {
            taskModalMapInstance.setView([initialLat, initialLng], 15);
            taskModalMarker.setLatLng([initialLat, initialLng]);
            setTimeout(() => taskModalMapInstance.invalidateSize(), 150);
        } else {
            taskModalMapInstance = L.map(mapElement, {
                center: [initialLat, initialLng],
                zoom: 15,
                zoomControl: true,
                scrollWheelZoom: true
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(taskModalMapInstance);

            taskModalMarker = L.marker([initialLat, initialLng], { draggable: true }).addTo(taskModalMapInstance);
            taskModalMarker.on('dragend', (event) => {
                const latLng = event.target.getLatLng();
                latInput.value = latLng.lat.toFixed(8);
                lngInput.value = latLng.lng.toFixed(8);
            });

            taskModalMapInstance.on('click', (event) => {
                taskModalMarker.setLatLng(event.latlng);
                latInput.value = event.latlng.lat.toFixed(8);
                lngInput.value = event.latlng.lng.toFixed(8);
            });
        }

        const searchButton = document.getElementById('task-location-search');
        const locationInput = document.getElementById('task-location-input');
        if (searchButton && locationInput && !searchButton.dataset.bound) {
            const performSearch = async () => {
                const query = locationInput.value.trim();
                if (!query) {
                    alert('请输入要搜索的地点');
                    return;
                }
                searchButton.disabled = true;
                const originalText = searchButton.textContent;
                searchButton.textContent = '搜索中...';
                try {
                    const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`, {
                        headers: {
                            'Accept-Language': 'zh-CN'
                        }
                    });
                    const data = await response.json();
                    if (Array.isArray(data) && data.length > 0) {
                        const { lat, lon } = data[0];
                        const target = [parseFloat(lat), parseFloat(lon)];
                        taskModalMapInstance.flyTo(target, 18, { duration: 1.2 });
                        taskModalMarker.setLatLng(target);
                        latInput.value = target[0].toFixed(8);
                        lngInput.value = target[1].toFixed(8);
                    } else {
                        alert('未找到匹配的位置，请尝试更精确的描述');
                    }
                } catch (error) {
                    console.error('Nominatim search failed:', error);
                    alert('搜索失败，请稍后重试');
                } finally {
                    searchButton.disabled = false;
                    searchButton.textContent = originalText;
                }
            };

            searchButton.addEventListener('click', performSearch);
            locationInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    performSearch();
                }
            });
            searchButton.dataset.bound = 'true';
        }
    }
</script>

<style>
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

    /* Stamp Animation Keyframes */
    @keyframes stamp-bounce {
        0% { 
            transform: scale(2) rotate(-12deg); 
            opacity: 0; 
        }
        50% { 
            transform: scale(0.9) rotate(-12deg); 
            opacity: 1; 
        }
        100% { 
            transform: scale(1) rotate(-12deg); 
            opacity: 1; 
        }
    }

    .animate-stamp {
        animation: stamp-bounce 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    /* Grayscale filter for completed tasks */
    .grayscale {
        filter: grayscale(0.3);
    }
</style>

<!-- Custom Apple-style Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 z-[9999] flex items-center justify-center hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" onclick="closeConfirmModal()"></div>
    
    <!-- Modal Card -->
    <div class="modal-card relative bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm transform transition-all scale-95 opacity-0">
        <!-- Icon -->
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 rounded-full bg-orange-100 flex items-center justify-center">
                <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Title -->
        <h3 class="text-xl font-bold text-gray-900 text-center mb-2">
            确认结束招募?
        </h3>

        <!-- Description -->
        <p class="text-gray-600 text-sm text-center mb-6">
            任务将被标记为已完成，不再接受新的申请。
        </p>

        <!-- Buttons -->
        <div class="flex gap-3">
            <button 
                type="button"
                onclick="closeConfirmModal()"
                class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-colors"
            >
                取消
            </button>
            <button 
                type="button"
                onclick="confirmAction()"
                class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition-colors"
            >
                确认结束
            </button>
        </div>
    </div>
</div>

<!-- Custom Delete Modal -->
<div id="deleteModal" class="fixed inset-0 z-[10000] hidden items-center justify-center">
    <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    <div class="delete-modal-card relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 transform transition-all scale-95 opacity-0">
        <div class="w-16 h-16 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M5.455 19h13.09a2 2 0 001.9-2.632l-4.545-12.12A2 2 0 0014.045 3H9.955a2 2 0 00-1.854 1.248L3.556 16.368A2 2 0 005.455 19z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 text-center mb-2">确认删除?</h3>
        <p class="text-center text-gray-500 mb-6">删除后将无法恢复，确认继续操作吗？</p>
        <div class="flex gap-3">
            <button 
                type="button"
                onclick="closeDeleteModal()"
                class="flex-1 px-4 py-3 rounded-2xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition"
            >
                取消
            </button>
            <button 
                type="button"
                onclick="confirmDelete()"
                class="flex-1 px-4 py-3 rounded-2xl bg-red-500 text-white font-semibold shadow-md hover:bg-red-600 transition"
            >
                确认删除
            </button>
        </div>
    </div>
</div>
@endsection
