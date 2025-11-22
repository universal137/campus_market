@extends('layouts.app')

@section('title', '我的收藏 · 校园易')

@section('content')
    <section class="surface">
        <div style="display:flex;flex-direction:column;gap:18px;">
            <div>
                <h2>我的收藏</h2>
                <p style="color:#94a3b8;margin-top:4px;">查看您收藏的商品和求助</p>
            </div>
        </div>
    </section>

    <section class="surface">
        <h3 style="margin-top:0;">收藏夹</h3>
        <p style="color:#94a3b8;margin-top:4px;">您还没有收藏任何内容</p>
    </section>
@endsection

