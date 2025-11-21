@extends('layouts.app')

@section('title', $task->title . ' - 互助任务详情')

@section('content')
    <section class="surface">
        <a href="{{ route('tasks.index') }}" style="font-size:13px;color:#64748b;text-decoration:none;">
            ← 返回互助广场
        </a>

        <div style="margin-top:18px;display:flex;flex-wrap:wrap;gap:28px;">
            <!-- 左侧：任务内容 / 服务说明 -->
            <div style="flex:2 1 320px;min-width:260px;display:flex;flex-direction:column;gap:16px;">
                <div>
                    <h1 style="font-size:22px;font-weight:600;margin:0 0 10px;">{{ $task->title }}</h1>
                    <p style="color:#64748b;font-size:13px;margin:0 0 10px;">
                        服务类型：校园互助 / 时间、地点等可在消息中进一步确认
                    </p>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;">
                        <span class="status-pill {{ $task->status === 'completed' ? 'status-pill--danger' : 'status-pill--success' }}">
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

            <!-- 右侧：发布人信息 + 操作按钮 -->
            <div style="flex:1 1 260px;min-width:240px;display:flex;flex-direction:column;gap:14px;">
                <div style="border:1px solid #e2e8f0;border-radius:14px;padding:14px;display:flex;gap:12px;align-items:center;">
                    <div style="width:44px;height:44px;border-radius:999px;background:#fef3c7;color:#b45309;display:flex;align-items:center;justify-content:center;font-weight:600;font-size:18px;">
                        {{ mb_substr($task->user->name, 0, 1) }}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p style="margin:0 0 4px;font-weight:600;font-size:14px;color:#0f172a;">
                            {{ $task->user->name }}
                            <span style="font-size:11px;color:#16a34a;border-radius:999px;background:#dcfce7;padding:2px 8px;margin-left:6px;">
                                已认证学生（示例）
                            </span>
                        </p>
                        <p style="margin:0;color:#94a3b8;font-size:12px;">
                            校园信誉：⭐ 4.8 · 15 次互助完成（示例）
                        </p>
                    </div>
                </div>

                <div style="border-radius:12px;background:#eff6ff;color:#1d4ed8;padding:10px 12px;font-size:12px;line-height:1.6;">
                    温馨提示：线下互助请选择人流较多、熟悉的地点见面，注意保护个人隐私，勿轻信转账链接。
                </div>

                <div style="margin-top:4px;display:flex;flex-wrap:wrap;gap:10px;">
                    <button type="button" class="btn btn-primary" style="flex:1 1 150px;min-width:130px;">
                        我来帮忙 / 立即沟通（占位）
                    </button>
                    <button type="button" class="btn btn-secondary" style="flex:1 1 130px;min-width:120px;">
                        加入心愿单（占位）
                    </button>
                </div>
            </div>
        </div>
    </section>
@endsection


