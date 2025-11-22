@extends('layouts.app')

@section('title', '我发布的 · 校园易')

@section('content')
    <section class="surface">
        <div style="display:flex;flex-direction:column;gap:18px;">
            <div>
                <h2>我发布的</h2>
                <p style="color:#94a3b8;margin-top:4px;">管理您发布的闲置商品</p>
            </div>
        </div>
    </section>

    <section class="surface">
        <h3 style="margin-top:0;">我的商品</h3>
        <p style="color:#94a3b8;margin-top:4px;">您还没有发布任何商品</p>
    </section>
@endsection

