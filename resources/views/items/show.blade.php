@extends('layouts.app')

@section('title', $item->title . ' - 闲置详情')

@section('content')
    <section class="surface">
        <a href="{{ route('items.index') }}" style="font-size:13px;color:#64748b;text-decoration:none;">
            ← 返回全部闲置
        </a>
        <div style="display:flex;flex-wrap:wrap;gap:32px;margin-top:18px;">
            <div style="flex:1 1 260px;min-width:240px;">
                <div style="height:220px;border-radius:18px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:13px;margin-bottom:14px;">
                    商品图片占位（可在后续接入上传功能）
                </div>
            </div>
            <div style="flex:2 1 300px;min-width:260px;">
                <h1 style="font-size:22px;font-weight:600;margin:0 0 10px;">{{ $item->title }}</h1>
                <p style="font-size:20px;font-weight:700;color:#0f172a;margin:0 0 8px;">
                    ¥{{ $item->price }}
                </p>
                <p style="color:#64748b;font-size:13px;margin:0 0 14px;">
                    分类：{{ optional($item->category)->name ?? '未分类' }} ｜ 卖家：{{ $item->user->name }}
                </p>
                <span class="status-pill" style="margin-bottom:14px;display:inline-block;">
                    {{ $item->status === 'on_sale' ? '在售中' : '已下架' }}
                </span>
                <div style="margin-top:8px;color:#334155;font-size:14px;line-height:1.7;white-space:pre-line;">
                    {{ $item->description }}
                </div>
            </div>
        </div>
    </section>
@endsection


