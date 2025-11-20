@extends('layouts.app')

@section('title', '校园易 - 首页总览')

@section('content')
    <section class="surface">
        <h2>📂 热门分类</h2>
        <p style="color:#94a3b8;margin-top:4px;">看看同学们最近都在发布哪些类型的商品</p>
        <div style="display:flex;flex-wrap:wrap;gap:10px;margin-top:18px;">
            @forelse($categories as $category)
                <span class="status-pill">{{ $category->name }}</span>
            @empty
                <span style="color:#94a3b8;">还没有分类，执行 `php artisan db:seed` 生成示例数据</span>
            @endforelse
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
                <article class="surface" style="padding:18px;border:1px solid #e2e8f0;box-shadow:none;">
                    <div style="height:120px;border-radius:14px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:13px;margin-bottom:14px;">
                        商品图片占位
                    </div>
                    <strong>{{ $item->title }}</strong>
                    <p style="color:#475569;font-size:13px;margin:8px 0 0;">¥{{ $item->price }}</p>
                    <p style="color:#94a3b8;font-size:12px;margin:4px 0 0;">
                        {{ optional($item->category)->name ?? '未分类' }} · 卖家 {{ $item->user->name }}
                    </p>
                </article>
            @empty
                <p style="color:#94a3b8;margin-top:12px;">暂无商品，欢迎前往“二手交易”页面发布。</p>
            @endforelse
        </div>
    </section>

    <section class="surface">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;">
            <div>
                <h2>🤝 互助广场</h2>
                <p style="color:#94a3b8;margin-top:4px;">代取快递、课程辅导、跑腿……随时发布协助需求</p>
            </div>
            <a class="btn btn-secondary" href="{{ route('tasks.index') }}">我要帮忙</a>
        </div>
        <div style="display:flex;flex-direction:column;gap:18px;margin-top:18px;">
            @forelse($tasks as $task)
                <article style="border-bottom:1px solid #e2e8f0;padding-bottom:14px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;">
                        <strong>{{ $task->title }}</strong>
                        <span class="status-pill" style="background:#dcfce7;color:#15803d;">奖励 {{ $task->reward }}</span>
                    </div>
                    <p style="color:#475569;font-size:14px;margin:8px 0;">
                        {{ \Illuminate\Support\Str::limit($task->content, 80) }}
                    </p>
                    <p style="color:#94a3b8;font-size:12px;">发布人：{{ $task->user->name }} ｜ 状态：{{ $task->status === 'completed' ? '已完成' : '招募中' }}</p>
                </article>
            @empty
                <p style="color:#94a3b8;">暂无互助任务，前往“互助任务”页面试着发布一个吧。</p>
            @endforelse
        </div>
    </section>
@endsection
