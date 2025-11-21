@extends('layouts.app')

@section('title', $item->title . ' - 闲置详情')

@section('content')
    <section class="surface">
        <a href="{{ route('items.index') }}" style="font-size:13px;color:#64748b;text-decoration:none;">
            ← 返回全部闲置
        </a>
        <div style="display:flex;flex-wrap:wrap;gap:32px;margin-top:18px;">
            <!-- 左侧：图片/宣传图 -->
            <div style="flex:1 1 260px;min-width:240px;">
                <div style="height:240px;border-radius:18px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:13px;margin-bottom:14px;">
                    商品图片占位（可在后续接入上传功能）
                </div>
            </div>

            <!-- 右侧：关键信息 + 卖家信息 + 操作按钮 -->
            <div style="flex:2 1 320px;min-width:260px;display:flex;flex-direction:column;gap:16px;">
                <div>
                    <h1 style="font-size:22px;font-weight:600;margin:0 0 10px;">
                        {{ $item->title }}
                    </h1>
                    <p style="font-size:22px;font-weight:700;color:#ef4444;margin:0 0 6px;">
                        ¥{{ $item->price }}
                    </p>
                    <p style="color:#64748b;font-size:13px;margin:0 0 10px;">
                        分类：{{ optional($item->category)->name ?? '未分类' }}
                        ｜ 状态：{{ $item->status === 'on_sale' ? '在售中' : '已售出' }}
                    </p>
                    @if ($item->deal_place)
                        <p style="color:#475569;font-size:13px;margin:0 0 10px;">
                            建议交易地点：{{ $item->deal_place }}
                        </p>
                    @else
                        <p style="color:#475569;font-size:13px;margin:0 0 10px;">
                            交易地点待与卖家协商，建议选择校园公共区域当面验货。
                        </p>
                    @endif
                    <span class="status-pill" style="margin-bottom:4px;display:inline-block;">
                        校园闲置 · 面对面交易更安心
                    </span>
                </div>

                <!-- 卖家信息区 -->
                <div style="border:1px solid #e2e8f0;border-radius:14px;padding:12px 14px;display:flex;align-items:center;gap:12px;">
                    <div style="width:40px;height:40px;border-radius:999px;background:#eff6ff;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-weight:600;">
                        {{ mb_substr($item->user->name, 0, 1) }}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p style="margin:0 0 4px;font-weight:600;font-size:14px;color:#0f172a;">
                            {{ $item->user->name }}
                            <span style="font-size:11px;color:#16a34a;border-radius:999px;background:#dcfce7;padding:2px 8px;margin-left:6px;">
                                已认证学生（示例）
                            </span>
                        </p>
                        <p style="margin:0;color:#94a3b8;font-size:12px;">
                            校园信誉：⭐ 4.9 · 23 次成功交易（示例文案）
                        </p>
                    </div>
                </div>

                <!-- 商品描述 -->
                <div style="color:#334155;font-size:14px;line-height:1.8;white-space:pre-line;">
                    {{ $item->description }}
                </div>

                <!-- 底部操作栏（可做成悬浮） -->
                <div style="margin-top:4px;display:flex;flex-wrap:wrap;gap:10px;">
                    <button type="button" class="btn btn-primary" style="flex:1 1 160px;min-width:140px;">
                        我想要 / 立即沟通（占位）
                    </button>
                    <button type="button" class="btn btn-secondary" style="flex:1 1 130px;min-width:120px;">
                        加入心愿单（占位）
                    </button>
                </div>
            </div>
        </div>
    </section>
@endsection


