@extends('layouts.app')

@section('title', '消息通知 · 校园易')

@section('content')
    <section class="surface">
        <div style="display:flex;flex-direction:column;gap:18px;">
            <div>
                <h2>消息通知</h2>
                <p style="color:#94a3b8;margin-top:4px;">查看您的未读提醒和通知</p>
            </div>
        </div>
    </section>

    <section class="surface">
        <h3 style="margin-top:0;">通知列表</h3>
        <p style="color:#94a3b8;margin-top:4px;">暂无新通知</p>
    </section>
@endsection

