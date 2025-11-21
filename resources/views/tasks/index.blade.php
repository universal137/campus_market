@extends('layouts.app')

@section('title', '互助任务 · 校园易')

@section('content')
    <section class="surface">
        <div style="display:flex;flex-direction:column;gap:18px;">
            <div>
                <h2>互助广场</h2>
                <p style="color:#94a3b8;margin-top:4px;">寻找志同道合的同学一起解决问题</p>
            </div>
            <form method="GET" class="form-grid" style="align-items:end;">
                <div>
                    <label for="q">关键字</label>
                    <input type="text" id="q" name="q" value="{{ $filters['q'] }}" placeholder="例如：代取快递">
                </div>
                <div>
                    <label for="status">状态</label>
                    <select id="status" name="status">
                        <option value="">全部</option>
                        <option value="open" @selected($filters['status'] === 'open')>招募中</option>
                        <option value="completed" @selected($filters['status'] === 'completed')>已完成</option>
                    </select>
                </div>
                <div>
                    <button class="btn btn-secondary" style="width:100%;">筛选</button>
                </div>
            </form>
        </div>
    </section>

    <section class="surface">
        @if (session('success'))
            <div class="error-msg" style="background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;">
                {{ session('success') }}
            </div>
        @endif

        <h3 style="margin-top:0;">发布互助任务</h3>
        <p style="color:#94a3b8;margin-top:4px;">填写基础信息即可创建任务，稍后可在数据库中更新状态</p>

        <form method="POST" action="{{ route('tasks.store') }}" style="margin-top:16px;display:flex;flex-direction:column;gap:16px;">
            @csrf
            @if ($errors->any())
                <div class="error-msg">
                    <strong>请检查以下输入：</strong>
                    <ul style="margin:8px 0 0 16px;padding:0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-grid">
                <div>
                    <label for="publisher_name">联系人昵称</label>
                    <input id="publisher_name" name="publisher_name" value="{{ old('publisher_name') }}" required>
                </div>
                <div>
                    <label for="publisher_email">校园邮箱</label>
                    <input type="email" id="publisher_email" name="publisher_email" value="{{ old('publisher_email') }}" required>
                </div>
                <div>
                    <label for="reward">奖励（可选）</label>
                    <input id="reward" name="reward" placeholder="如 10 元奶茶/校园币" value="{{ old('reward') }}">
                </div>
            </div>

            <div>
                <label for="title">标题</label>
                <input id="title" name="title" value="{{ old('title') }}" required>
            </div>

            <div>
                <label for="content">任务详情</label>
                <textarea id="content" name="content" required placeholder="任务背景、时间地点、注意事项等">{{ old('content') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="align-self:flex-start;">发布任务</button>
        </form>
    </section>

    <section class="surface">
        <h3 style="margin-top:0;">最新互助需求</h3>
        <div style="display:flex;flex-direction:column;gap:16px;margin-top:18px;">
            @forelse($tasks as $task)
                <a href="{{ route('tasks.show', $task) }}" style="text-decoration:none;color:inherit;">
                    <article style="border:1px solid #e2e8f0;border-radius:14px;padding:18px;cursor:pointer;">
                        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;">
                            <div>
                                <h4 style="margin:0 0 6px;">{{ $task->title }}</h4>
                                <p style="color:#94a3b8;font-size:12px;margin:0;">发布人：{{ $task->user->name }}</p>
                            </div>
                            <span class="status-pill {{ $task->status === 'completed' ? 'status-pill--danger' : 'status-pill--success' }}">
                                {{ $task->status === 'completed' ? '已完成' : '招募中' }}
                            </span>
                        </div>
                        <p style="color:#475569;font-size:14px;margin:12px 0 8px;">{{ $task->content }}</p>
                        <p style="color:#0f172a;font-weight:600;margin:0;">奖励：{{ $task->reward }}</p>
                    </article>
                </a>
            @empty
                <p style="color:#94a3b8;">暂无互助任务，快来发布第一条。</p>
            @endforelse
        </div>

        <div style="margin-top:20px;">
            {{ $tasks->links('pagination::simple-tailwind') }}
        </div>
    </section>
@endsection

