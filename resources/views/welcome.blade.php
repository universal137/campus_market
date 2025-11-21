@extends('layouts.app')

@section('title', '校园易 - 首页总览')

@section('content')
    <section class="surface">
        <h2>🔍 想找什么？</h2>
        <p style="color:#94a3b8;margin-top:4px;">先选类型，再输入关键字，更快找到合适的闲置或技能</p>

        <form id="homepage-search-form"
              method="GET"
              action="{{ route('items.index') }}"
              data-item-url="{{ route('items.index') }}"
              data-task-url="{{ route('tasks.index') }}">
            <div class="form-grid" style="align-items:flex-end;margin-top:18px;">
                <div>
                    <label for="type">我要</label>
                    <select id="type" name="type">
                        <option value="item">找物品</option>
                        <option value="task">找服务 / 互助</option>
                    </select>
                </div>
                <div>
                    <label for="q">关键字</label>
                    <input id="q" name="q" type="text" placeholder="例如：iPad、吉他教学、考研辅导">
                </div>
                <div>
                    <button class="btn btn-primary" style="width:100%;">开始查找</button>
                </div>
            </div>
        </form>

        <div style="margin-top:22px;">
            <h3 style="margin:0 0 8px;font-size:15px;color:#0f172a;">或按分类快速浏览</h3>
            <div style="display:flex;flex-wrap:wrap;gap:10px;margin-top:6px;">
                @forelse($categories as $category)
                    <a href="{{ route('items.index', ['category' => $category->id]) }}"
                       style="text-decoration:none;color:inherit;">
                        <span class="status-pill">{{ $category->name }}</span>
                    </a>
                @empty
                    <span style="color:#94a3b8;">还没有分类，执行 `php artisan db:seed` 生成示例数据</span>
                @endforelse
            </div>
        </div>
    </section>

    <section class="surface">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;">
            <div>
                <h2>🔥 最新闲置</h2>
                <p style="color:#94a3b8;margin-top:4px;">新鲜出炉的二手好物，连同卖家昵称</p>
            </div>
            <a class="btn btn-secondary" href="{{ route('items.index') }}">查看全部</a>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;margin-top:18px;">
            @forelse($items as $item)
                <a href="{{ route('items.show', $item) }}" style="text-decoration:none;color:inherit;">
                    <article class="surface" style="padding:18px;border:1px solid #e2e8f0;box-shadow:none;cursor:pointer;transition:transform .12s ease, box-shadow .12s ease;">
                        <div style="height:120px;border-radius:14px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:13px;margin-bottom:14px;">
                            商品图片占位
                        </div>
                        <strong>{{ $item->title }}</strong>
                        <p style="color:#475569;font-size:13px;margin:8px 0 0;">¥{{ $item->price }}</p>
                        <p style="color:#475569;font-size:12px;margin:4px 0 0;">
                            交易地点：{{ $item->deal_place ?? '与卖家协商，建议线下面对面' }}
                        </p>
                        <p style="color:#94a3b8;font-size:12px;margin:4px 0 0;">
                            {{ optional($item->category)->name ?? '未分类' }} · 卖家 {{ $item->user->name }}
                        </p>
                    </article>
                </a>
            @empty
                <p style="color:#94a3b8;margin-top:12px;">暂无商品，欢迎前往“二手交易”页面发布。</p>
            @endforelse
        </div>
    </section>

    <section class="surface">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;">
            <div>
                <h2>🤝 校园能人 / 互助任务</h2>
                <p style="color:#94a3b8;margin-top:4px;">吉他教学、代取快递、考研辅导……用技能和时间互相帮助</p>
            </div>
            <a class="btn btn-secondary" href="{{ route('tasks.index') }}">我要发布互助</a>
        </div>
        <div style="display:flex;flex-direction:column;gap:18px;margin-top:18px;">
            @forelse($tasks as $task)
                <a href="{{ route('tasks.show', $task) }}" style="text-decoration:none;color:inherit;">
                    <article style="border-radius:16px;border:1px solid #e2e8f0;padding:16px 18px;cursor:pointer;display:flex;gap:14px;align-items:center;">
                        <div style="width:42px;height:42px;border-radius:999px;background:#eff6ff;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:600;flex-shrink:0;">
                            {{ mb_substr($task->user->name, 0, 1) }}
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;">
                                <div>
                                    <p style="margin:0 0 4px;font-weight:600;color:#0f172a;">{{ $task->title }}</p>
                                    <p style="margin:0;color:#64748b;font-size:12px;">
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
                            <p style="color:#475569;font-size:13px;margin:8px 0 0;">
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
