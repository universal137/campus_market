@extends('layouts.app')

@section('title', $task->title . ' - 互助任务详情')

@section('content')
    <section class="surface">
        <a href="{{ route('tasks.index') }}" style="font-size:13px;color:#64748b;text-decoration:none;">
            ← 返回互助广场
        </a>

        <div style="margin-top:18px;display:flex;flex-direction:column;gap:16px;">
            <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;">
                <div>
                    <h1 style="font-size:22px;font-weight:600;margin:0 0 8px;">{{ $task->title }}</h1>
                    <p style="color:#94a3b8;font-size:13px;margin:0;">
                        发布人：{{ $task->user->name }}
                    </p>
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px;">
                    <span class="status-pill" style="{{ $task->status === 'completed' ? 'background:#fee2e2;color:#b91c1c;' : 'background:#dcfce7;color:#14532d;' }}">
                        {{ $task->status === 'completed' ? '已完成' : '招募中' }}
                    </span>
                    <span class="status-pill" style="background:#ecfeff;color:#155e75;">
                        奖励：{{ $task->reward }}
                    </span>
                </div>
            </div>

            <div style="margin-top:4px;color:#334155;font-size:14px;line-height:1.8;white-space:pre-line;">
                {{ $task->content }}
            </div>
        </div>
    </section>
@endsection


