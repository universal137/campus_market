@extends('layouts.app')

@section('title', '消息中心 · 校园易')

@section('content')
<div class="bg-[#F9FAFB] min-h-screen py-10 px-4">
    <div class="max-w-4xl mx-auto space-y-8">
        <header class="text-center space-y-3">
            <p class="text-sm uppercase tracking-[0.35em] text-gray-400">MESSAGE HUB</p>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">消息中心</h1>
            <p class="text-gray-500">私信与系统提醒一站式查看</p>
        </header>

        <div class="flex justify-center">
            <div class="inline-flex bg-gray-100 rounded-full p-1 shadow-inner relative" role="tablist">
                <div id="tab-indicator" class="absolute top-1 bottom-1 left-1 bg-white rounded-full shadow transition-all duration-300 ease-out" style="width:calc(50% - 8px); transform: translateX(0);"></div>
                <button type="button" class="tab-trigger relative z-10 px-8 py-2 text-sm font-semibold text-gray-900 transition-colors" data-target="view-chats" aria-selected="true">
                    私信聊天
                </button>
                <button type="button" class="tab-trigger relative z-10 px-8 py-2 text-sm font-semibold text-gray-500 transition-colors" data-target="view-system" aria-selected="false">
                    系统通知
                </button>
            </div>
        </div>

        <section id="view-chats" class="tab-panel space-y-4">
            @forelse($conversations as $index => $chat)
                @php
                    $isSender = $chat->sender_id === auth()->id();
                    $otherPerson = $isSender ? $chat->receiver : $chat->sender;
                    $avatarUrl = $otherPerson->avatar_url ?? null;
                    $initial = mb_substr($otherPerson->name ?? 'U', 0, 1);
                    $lastMessage = $chat->messages->first();
                    $lastMessagePreview = \Illuminate\Support\Str::limit($lastMessage->body ?? '开始对话吧～', 60);
                    $lastActiveAt = $lastMessage->created_at ?? $chat->updated_at;
                    $isUnread = $lastMessage && $lastMessage->user_id !== auth()->id() && !($lastMessage->is_read ?? false);
                @endphp
                <a href="{{ route('chat.show', $chat->id) }}"
                   class="bg-white p-4 rounded-2xl border border-gray-100 mb-3 flex items-center gap-4 hover:shadow-md transition-all cursor-pointer">
                    <div class="flex-shrink-0">
                        @if($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="{{ $otherPerson->name }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-lg">
                                {{ $initial }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0 space-y-1.5">
                        <div class="flex items-center flex-wrap gap-2">
                            <h3 class="text-gray-900 font-semibold truncate">{{ $otherPerson->name ?? '匿名用户' }}</h3>
                            @if($chat->product)
                                <span class="inline-flex items-center text-xs font-medium text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">
                                    咨询：{{ \Illuminate\Support\Str::limit($chat->product->title, 18) }}
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500 truncate">{{ $lastMessagePreview }}</p>
                    </div>
                    <div class="text-right flex flex-col items-end gap-2">
                        <span class="text-xs text-gray-400 whitespace-nowrap">{{ optional($lastActiveAt)->diffForHumans() ?? '刚刚' }}</span>
                        @if($isUnread)
                            <span class="inline-flex w-2.5 h-2.5 rounded-full bg-red-500"></span>
                        @endif
                    </div>
                </a>
            @empty
                <div class="bg-white border border-gray-100 rounded-3xl p-10 text-center shadow-sm">
                    <svg class="w-20 h-20 mx-auto text-gray-200 mb-6" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M16 24h32M16 34h20M16 44h12" stroke-linecap="round"/>
                        <path d="M12 8h40a4 4 0 0 1 4 4v36a4 4 0 0 1-4 4H26l-10 10v-10H12a4 4 0 0 1-4-4V12a4 4 0 0 1 4-4z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">还没有收到私信</h3>
                    <p class="text-gray-500 text-sm">去逛逛吧，说不定就有同学来找你啦！</p>
                </div>
            @endforelse
        </section>

        <section id="view-system" class="tab-panel space-y-4 hidden">
            @if($notifications->isEmpty())
                <div class="bg-white border border-gray-100 rounded-3xl p-10 text-center shadow-sm">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V4a1 1 0 10-2 0v1.083A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">暂无系统通知</h3>
                    <p class="text-gray-500 text-sm">有新的订单或评价会立即通知您。</p>
                </div>
            @else
                @foreach($notifications as $index => $notification)
                    @php
                        $title = $notification->data['title'] ?? '系统提醒';
                        $message = $notification->data['message'] ?? ($notification->data['body'] ?? '您有新的通知。');
                        $typeIcon = $notification->data['type'] ?? 'order';
                        $iconColor = $typeIcon === 'review' ? 'bg-yellow-100 text-yellow-600' : 'bg-green-100 text-green-600';
                        $iconSvg = $typeIcon === 'review'
                            ? '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>'
                            : '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h18v2H3V3zm2 4h14v13a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7zm4 2v9h6V9H9z"/></svg>';
                    @endphp
                    <article class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-start gap-4 opacity-0 translate-y-8 animate-fade-in-up" style="animation-delay: {{ $index * 80 }}ms;">
                        <div class="{{ $iconColor }} w-10 h-10 rounded-full flex items-center justify-center">
                            {!! $iconSvg !!}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between gap-3">
                                <h3 class="text-gray-900 font-semibold">{{ $title }}</h3>
                                <span class="text-xs text-gray-400 whitespace-nowrap">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">{{ $message }}</p>
                        </div>
                    </article>
                @endforeach
            @endif
        </section>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const triggers = document.querySelectorAll('.tab-trigger');
        const panels = document.querySelectorAll('.tab-panel');
        const indicator = document.getElementById('tab-indicator');

        triggers.forEach((trigger, index) => {
            trigger.addEventListener('click', () => {
                triggers.forEach(btn => btn.classList.remove('text-gray-900'));
                triggers.forEach(btn => btn.classList.add('text-gray-500'));
                trigger.classList.add('text-gray-900');
                trigger.classList.remove('text-gray-500');

                panels.forEach(panel => panel.classList.add('hidden'));
                const target = document.getElementById(trigger.dataset.target);
                target.classList.remove('hidden');

                indicator.style.transform = `translateX(${index * 100}%)`;
            });
        });
    });
</script>
@endpush
@endsection

