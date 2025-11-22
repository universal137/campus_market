@extends('layouts.app')

@section('title', '注册 · 校园易')

@section('content')
    <div style="max-width: 480px; margin: 60px auto; padding: 0 24px;">
        <div style="background: white; border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.08); padding: 40px;">
            <div style="text-align: center; margin-bottom: 32px;">
                <h2 style="margin: 0 0 8px; font-size: 28px; font-weight: 700; color: #0f172a;">创建账户</h2>
                <p style="margin: 0; color: #94a3b8; font-size: 15px;">填写以下信息以继续</p>
            </div>
            
            @if ($errors->any())
                <div class="error-msg" style="margin-bottom: 24px;">
                    <ul style="margin:0;padding-left:20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div style="background:#dcfce7;color:#166534;padding:12px 16px;border-radius:10px;margin-bottom:24px;">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" style="display:flex;flex-direction:column;gap:20px;">
                @csrf

                <div>
                    <label for="name">姓名 <span style="color:#ef4444;">*</span></label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        autofocus
                        placeholder="请输入您的姓名"
                    >
                </div>

                <div>
                    <label for="email">邮箱 <span style="color:#ef4444;">*</span></label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required
                        placeholder="example@email.com"
                    >
                </div>

                <div>
                    <label for="password">密码 <span style="color:#ef4444;">*</span></label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        minlength="8"
                        placeholder="至少8个字符"
                    >
                </div>

                <div>
                    <label for="password_confirmation">确认密码 <span style="color:#ef4444;">*</span></label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        required
                        placeholder="请再次输入密码"
                    >
                </div>

                <div>
                    <label for="student_id">学号</label>
                    <input 
                        type="text" 
                        id="student_id" 
                        name="student_id" 
                        value="{{ old('student_id') }}"
                        placeholder="选填：请输入学号"
                    >
                </div>

                <div>
                    <label for="phone">电话</label>
                    <input 
                        type="text" 
                        id="phone" 
                        name="phone" 
                        value="{{ old('phone') }}"
                        placeholder="选填：请输入电话号码"
                    >
                </div>

                <div style="margin-top:8px;">
                    <button type="submit" class="btn btn-primary btn-auth">
                        注册
                    </button>
                </div>

                <div style="text-align:center;margin-top:12px;">
                    <p style="color:#94a3b8;margin:0;">
                        已有账户？ 
                        <a href="{{ route('login') }}" style="color:#4F46E5;text-decoration:none;font-weight:600;">
                            立即登录
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
@endsection

