@extends('layouts.app')

@section('title', '我买到的 · 校园易')

@section('content')
    <section class="surface">
        <div style="display:flex;flex-direction:column;gap:18px;">
            <div>
                <h2>我买到的</h2>
                <p style="color:#94a3b8;margin-top:4px;">查看您的交易记录</p>
            </div>
        </div>
    </section>

    <section class="surface">
        <h3 style="margin-top:0;">交易记录</h3>
        <p style="color:#94a3b8;margin-top:4px;">您还没有任何交易记录</p>
    </section>
@endsection

