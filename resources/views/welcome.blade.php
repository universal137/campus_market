@extends('layouts.app')

@section('title', '校园易 - 首页总览')

@section('content')
    <section class="surface surface--frosted surface--hero anim-fade-up" style="--delay: 0.28s;">
        <h2 class="section-title">🔍 想找什么？</h2>
        <p class="section-subtitle">先选类型，再输入关键字，更快找到合适的闲置或技能</p>

        <form id="homepage-search-form"
              method="GET"
              action="{{ route('items.index') }}"
              data-item-url="{{ route('items.index') }}"
              data-task-url="{{ route('tasks.index') }}">
            <div class="search-combo">
                <div class="search-combo__field">
                    <span class="search-combo__label">我要</span>
                    <select id="type" name="type" aria-label="我要搜索的类型">
                        <option value="item">找物品</option>
                        <option value="task">找服务 / 互助</option>
                    </select>
                </div>
                <div class="search-combo__divider" aria-hidden="true"></div>
                <div class="search-combo__field">
                    <span class="search-combo__label">关键字</span>
                    <input id="q"
                           name="q"
                           type="text"
                           placeholder="例如：iPad、吉他教学、考研辅导"
                           aria-label="搜索关键字">
                </div>
                <button class="search-combo__action" type="submit" aria-label="搜索">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"></circle>
                        <line x1="16.65" y1="16.65" x2="21" y2="21"></line>
                    </svg>
                </button>
            </div>
        </form>

        @php
            $hotKeywords = ['考研资料', '闲置自行车', '吉他谱', '四六级口语', '复试筹划'];
        @endphp
        <div class="hot-ticker anim-fade-up" style="--delay: 0.38s;">
            <span class="hot-ticker__label">热搜</span>
            <div class="hot-ticker__marquee" aria-live="polite">
                <div class="hot-ticker__track">
                    @foreach(array_merge($hotKeywords, $hotKeywords) as $keyword)
                        <span class="hot-ticker__item">热搜：{{ $keyword }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        <div style="margin-top:24px;">
            <h3 style="margin:0 0 8px;font-size:15px;color:#0f172a;font-weight:600;">或按分类快速浏览</h3>
            <div class="pill-collection">
                @forelse($categories as $category)
                    <a href="{{ route('items.index', ['category' => $category->id]) }}">
                        <span class="category-pill">{{ $category->name }}</span>
                    </a>
                @empty
                    <span style="color:#94a3b8;">还没有分类，执行 `php artisan db:seed` 生成示例数据</span>
                @endforelse
            </div>
        </div>
    </section>

    <section class="surface anim-fade-up" style="--delay: 0.45s;">
        <div class="section-header">
            <div>
                <h2 class="section-title">🔥 最新闲置</h2>
                <p class="section-subtitle">新鲜出炉的二手好物，连同卖家昵称</p>
            </div>
            <a class="btn btn-secondary" href="{{ route('items.index') }}">查看全部</a>
        </div>
        <div class="card-grid">
            @forelse($items as $item)
                @php
                    $delay = number_format(0.55 + $loop->index * 0.08, 2);
                    // 检查是否是示例数据（普通对象）还是真实数据（Eloquent模型）
                    $isSample = !($item instanceof \App\Models\Item);
                    $itemUrl = $isSample ? '#' : route('items.show', $item);
                @endphp
                <a class="card-link anim-fade-up" style="--delay: {{ $delay }}s;@if($isSample) cursor: default; opacity: 0.8;@endif" href="{{ $itemUrl }}" @if($isSample) onclick="return false;" @endif>
                    <article class="card card--clickable card--product">
                        <div class="card__media">
                            <img src="https://picsum.photos/300/200?random={{ $item->id }}"
                                 alt="最新闲置：{{ $item->title }}">
                        </div>
                        <div class="card__body">
                            <p class="card__title">{{ $item->title }}</p>
                            <p class="card__price">¥{{ $item->price }}</p>
                        </div>
                        <div class="card__meta">
                            <p class="meta-text">
                                交易地点：{{ $item->deal_place ?? '与卖家协商，建议线下面对面' }}
                            </p>
                            <p class="meta-text">
                                {{ optional($item->category)->name ?? '未分类' }} · 卖家 {{ $item->user->name }}
                            </p>
                        </div>
                    </article>
                </a>
            @empty
                <p style="color:#94a3b8;margin-top:12px;">暂无商品，欢迎前往“二手交易”页面发布。</p>
            @endforelse
        </div>
    </section>

    <section class="surface anim-fade-up" style="--delay: 0.8s;">
        <div class="section-header">
            <div>
                <h2 class="section-title">🤝 校园能人 / 互助任务</h2>
                <p class="section-subtitle">吉他教学、代取快递、考研辅导……用技能和时间互相帮助</p>
            </div>
            <a class="btn btn-secondary" href="{{ route('tasks.index') }}">我要发布互助</a>
        </div>
        <div class="task-list">
            @forelse($tasks as $task)
                @php
                    // 检查是否是示例数据（普通对象）还是真实数据（Eloquent模型）
                    $isSample = !($task instanceof \App\Models\Task);
                    $taskUrl = $isSample ? '#' : route('tasks.show', $task);
                @endphp
                <a href="{{ $taskUrl }}" style="text-decoration:none;color:inherit;@if($isSample) cursor: default; opacity: 0.8;@endif" @if($isSample) onclick="return false;" @endif>
                    <article class="task-card card--clickable">
                        <div class="task-card__avatar">
                            {{ mb_substr($task->user->name, 0, 1) }}
                        </div>
                        <div class="task-card__body">
                            <div class="task-card__heading">
                                <div>
                                    <p class="task-card__title">{{ $task->title }}</p>
                                    <p class="meta-text" style="margin:0;">
                                        发布人：{{ $task->user->name }} · 奖励：{{ $task->reward }}
                                    </p>
                                </div>
                                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;">
                                    <span class="status-pill {{ $task->status === 'completed' ? 'status-pill--danger' : 'status-pill--success' }}">
                                        {{ $task->status === 'completed' ? '已完成' : '招募中' }}
                                    </span>
                                    <span class="btn btn-secondary" style="padding:6px 10px;font-size:12px;display:inline-block;">查看详情</span>
                                </div>
                            </div>
                            <p style="color:#475569;font-size:14px;margin:10px 0 0;line-height:1.5;">
                                {{ \Illuminate\Support\Str::limit($task->content, 60) }}
                            </p>
                        </div>
                    </article>
                </a>
            @empty
                <p style="color:#94a3b8;">暂无互助任务，前往“互助任务”页面试着发布一个吧。</p>
            @endforelse
        </div>
    </section>
@endsection

@push('head')
    <script>
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
        });
    </script>
@endpush
