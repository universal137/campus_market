@extends('layouts.app')

@section('title', '互助任务 · 校园易')

@section('content')
<div class="bg-[#F9FAFB] min-h-screen">
    <!-- Hero Section with Search & Action -->
    <div class="bg-gradient-to-br from-gray-50 via-white to-gray-50 min-h-[400px] flex flex-col items-center justify-center px-4 py-16">
        <div class="w-full max-w-4xl">
            <!-- Page Title -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-3">互助广场</h1>
                <p class="text-gray-500 text-lg">寻找志同道合的同学一起解决问题</p>
            </div>

            <!-- Search Bar & New Request Button -->
            <div class="flex flex-col sm:flex-row gap-4 items-center justify-center mb-8">
                <!-- Floating Search Bar -->
                <form method="GET" class="flex-1 max-w-2xl w-full">
                    <div class="relative">
                        <input 
                            type="text" 
                            id="q" 
                            name="q" 
                            value="{{ $filters['q'] }}" 
                            placeholder="搜索任务，如 代取快递、学习辅导..." 
                            class="w-full px-6 py-4 pl-14 pr-20 text-lg rounded-full border border-gray-200 bg-white shadow-lg focus:outline-none transition-shadow duration-300 ease-in-out focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <svg class="absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none" 
                             width="20" 
                             height="20" 
                             fill="none" 
                             stroke="currentColor" 
                             viewBox="0 0 24 24"
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

                <!-- Status Filter (Hidden in search bar, shown as separate dropdown) -->
                <form method="GET" class="hidden sm:block">
                    <input type="hidden" name="q" value="{{ $filters['q'] }}">
                    <select 
                        id="status" 
                        name="status" 
                        onchange="this.form.submit()"
                        class="px-6 py-4 text-lg rounded-full border border-gray-200 bg-white shadow-lg focus:outline-none transition-shadow duration-300 ease-in-out focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none cursor-pointer pr-10"
                    >
                        <option value="">全部状态</option>
                        <option value="open" @selected($filters['status'] === 'open')>招募中</option>
                        <option value="completed" @selected($filters['status'] === 'completed')>已完成</option>
                    </select>
                </form>

                <!-- New Request Button -->
                <button 
                    onclick="openPublishModal()"
                    class="px-8 py-4 bg-blue-600 text-white text-lg font-semibold rounded-full transition-all duration-200 ease-in-out hover:bg-blue-700 active:scale-95 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 whitespace-nowrap"
                >
                    发布求助
                </button>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="max-w-6xl mx-auto px-4 py-4">
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 shadow-sm">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Task Cards Grid -->
    <div class="max-w-6xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($tasks as $task)
                <a 
                    href="{{ route('tasks.show', $task) }}" 
                    class="group block task-card-entry opacity-0 translate-y-8 transition-all duration-700 ease-out transform"
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
                            <!-- Status Indicator -->
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
            @empty
                <div class="col-span-full text-center py-16">
                    <div class="inline-block p-8 bg-white rounded-2xl border border-gray-100 shadow-sm">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500 text-lg mb-2">暂无互助任务</p>
                        <p class="text-gray-400 text-sm mb-6">快来发布第一条求助吧</p>
                        <button 
                            onclick="openPublishModal()"
                            class="px-6 py-3 bg-blue-600 text-white rounded-full font-medium transition-all duration-200 ease-in-out hover:bg-blue-700 active:scale-95 shadow-md hover:shadow-lg"
                        >
                            发布求助
                        </button>
                    </div>
                </div>
            @endforelse
        </div>

        @if($tasks->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $tasks->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Publish Task Modal -->
<div id="publishModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4" onclick="closePublishModal(event)">
    <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between rounded-t-3xl">
            <div>
                <h3 class="text-2xl font-bold text-gray-900">发布互助任务</h3>
                <p class="text-gray-500 text-sm mt-1">填写基础信息即可创建任务</p>
            </div>
            <button 
                onclick="closePublishModal()"
                class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors duration-200"
            >
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('tasks.store') }}" class="p-6 md:p-8 space-y-6">
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
                    <label for="publisher_name" class="block text-sm font-medium text-gray-700 mb-2">联系人昵称</label>
                    <input 
                        id="publisher_name" 
                        name="publisher_name" 
                        value="{{ old('publisher_name') }}" 
                        required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        placeholder="请输入您的昵称"
                    >
                </div>
                <div>
                    <label for="publisher_email" class="block text-sm font-medium text-gray-700 mb-2">校园邮箱</label>
                    <input 
                        type="email" 
                        id="publisher_email" 
                        name="publisher_email" 
                        value="{{ old('publisher_email') }}" 
                        required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        placeholder="example@campus.edu"
                    >
                </div>
            </div>

            <div>
                <label for="reward" class="block text-sm font-medium text-gray-700 mb-2">奖励（可选）</label>
                <input 
                    id="reward" 
                    name="reward" 
                    placeholder="如 10 元奶茶/校园币" 
                    value="{{ old('reward') }}"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                >
            </div>

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">标题</label>
                <input 
                    id="title" 
                    name="title" 
                    value="{{ old('title') }}" 
                    required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                    placeholder="例如：需要代取快递"
                >
            </div>

            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">任务详情</label>
                <textarea 
                    id="content" 
                    name="content" 
                    required 
                    rows="5"
                    placeholder="任务背景、时间地点、注意事项等"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"
                >{{ old('content') }}</textarea>
            </div>

            <div class="flex gap-4 pt-4">
                <button 
                    type="button"
                    onclick="closePublishModal()"
                    class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 rounded-full font-medium transition-all duration-200 ease-in-out hover:bg-gray-200 active:scale-95"
                >
                    取消
                </button>
                <button 
                    type="submit" 
                    class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-full font-medium transition-all duration-200 ease-in-out hover:bg-blue-700 active:scale-95 shadow-md hover:shadow-lg"
                >
                    发布任务
                </button>
            </div>
        </form>
    </div>
</div>

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
</style>

<script>
    // Modal Functions
    function openPublishModal() {
        document.getElementById('publishModal').classList.remove('hidden');
        document.getElementById('publishModal').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closePublishModal(event) {
        if (event && event.target !== event.currentTarget) return;
        document.getElementById('publishModal').classList.add('hidden');
        document.getElementById('publishModal').classList.remove('flex');
        document.body.style.overflow = '';
    }

    // Staggered Fade-in Entry Animation
    document.addEventListener('DOMContentLoaded', function() {
        const taskCards = document.querySelectorAll('.task-card-entry');
        
        taskCards.forEach((card, index) => {
            setTimeout(() => {
                // Remove initial invisible state
                card.classList.remove('opacity-0', 'translate-y-8');
                // Add visible state
                card.classList.add('opacity-100', 'translate-y-0');
            }, index * 100); // 100ms delay between each card
        });
    });
</script>
@endsection
