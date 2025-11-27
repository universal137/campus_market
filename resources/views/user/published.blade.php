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
                    <a
                        href="{{ route('items.index', ['trigger' => 'publish']) }}"
                        class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-full transition-all duration-200 ease-in-out hover:bg-blue-700 active:scale-95 shadow-md hover:shadow-lg whitespace-nowrap"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        发布新商品
                    </a>
                    <a
                        href="{{ route('tasks.index', ['trigger' => 'publish']) }}"
                        class="inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white font-medium rounded-full transition-all duration-200 ease-in-out hover:bg-green-700 active:scale-95 shadow-md hover:shadow-lg whitespace-nowrap"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        发布新任务
                    </a>
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
                                <a href="{{ route('products.show', $product) }}" class="block w-full h-full">
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
                                    <a href="{{ route('products.show', $product) }}" class="hover:text-blue-600 transition-colors">
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
                                    onclick="askDelete(&quot;product&quot;, {{ $product->id }})"
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
                                                onclick="askDelete(&quot;task&quot;, {{ $task->id }})"
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

<script>
    let deleteType = null;
    let deleteId = null;
    let targetTaskId = null;
    let targetBtn = null;

    function switchTab(tab) {
        const productsTab = document.getElementById('tab-products');
        const tasksTab = document.getElementById('tab-tasks');
        const productsView = document.getElementById('view-products');
        const tasksView = document.getElementById('view-tasks');

        if (tab === 'products') {
            productsTab.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
            productsTab.classList.add('bg-blue-600', 'text-white', 'shadow-md');

            tasksTab.classList.remove('bg-blue-600', 'text-white', 'shadow-md');
            tasksTab.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');

            productsView.classList.remove('hidden');
            tasksView.classList.add('hidden');
        } else {
            tasksTab.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
            tasksTab.classList.add('bg-blue-600', 'text-white', 'shadow-md');

            productsTab.classList.remove('bg-blue-600', 'text-white', 'shadow-md');
            productsTab.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');

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

    function askConfirmation(id, btn) {
        targetTaskId = id;
        targetBtn = btn;

        const modal = document.getElementById('confirmModal');
        const modalCard = modal.querySelector('.modal-card');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modalCard.classList.remove('scale-95', 'opacity-0');
            modalCard.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function confirmAction() {
        if (targetTaskId && targetBtn) {
            closeConfirmModal();
            markTaskCompleted(targetTaskId, targetBtn);
        }
    }

    function closeConfirmModal() {
        const modal = document.getElementById('confirmModal');
        const modalCard = modal.querySelector('.modal-card');
        modalCard.classList.remove('scale-100', 'opacity-100');
        modalCard.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            targetTaskId = null;
            targetBtn = null;
        }, 200);
    }

    function markTaskCompleted(taskId, btnElement) {
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
                const stamp = document.getElementById(`stamp-${taskId}`);
                const card = document.getElementById(`task-card-${taskId}`);

                if (stamp && card) {
                    stamp.classList.remove('hidden');
                    stamp.classList.add('animate-stamp');
                    setTimeout(() => {
                        card.classList.add('opacity-75', 'grayscale');
                        card.querySelector('article').classList.remove('bg-white');
                        card.querySelector('article').classList.add('bg-gray-50');
                    }, 200);
                }

                const statusBadge = document.getElementById(`task-status-${taskId}`);
                const statusDot = statusBadge?.previousElementSibling;

                if (statusBadge && statusDot) {
                    statusDot.classList.remove('bg-green-500', 'animate-pulse');
                    statusDot.classList.add('bg-gray-400');
                    statusBadge.textContent = '已完成';
                    statusBadge.classList.remove('text-gray-500');
                    statusBadge.classList.add('text-gray-400');
                }

                if (btnElement) {
                    btnElement.style.display = 'none';
                }
            }
        })
        .catch(error => {
            console.error('Error marking task as completed:', error);
            alert('操作失败，请重试');
            btnElement.disabled = false;
            btnElement.innerHTML = originalContent;
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
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
</script>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        line-clamp: 2;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        line-clamp: 3;
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
@endsection
