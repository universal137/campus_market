@extends('layouts.app')

@section('title', '个人资料 · 校园易')

@section('content')
<div style="max-width: 1200px; margin: 60px auto; padding: 0 24px;">
    @if (session('success'))
        <div id="success-message" class="success-message" style="background:#dcfce7;color:#166534;padding:16px 20px;border-radius:12px;margin-bottom:24px;box-shadow:0 4px 12px rgba(34,197,94,0.15);display:flex;align-items:center;gap:10px;font-weight:500;position:relative;overflow:hidden;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div style="background:#fee2e2;color:#991b1b;padding:12px 16px;border-radius:10px;margin-bottom:24px;">
            <ul style="margin:0;padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 32px; align-items: start;" class="profile-layout">
        <!-- Left Column: Identity Card -->
        <div style="background: white; border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.08); padding: 40px; position: sticky; top: 100px;">
            <div style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                <!-- Avatar -->
                <div style="position: relative; margin-bottom: 24px;">
                    <img 
                        src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode(auth()->user()->name) }}"
                        alt="{{ auth()->user()->name }}"
                        style="width: 120px; height: 120px; border-radius: 50%; border: 4px solid #F5F5F7; object-fit: cover;"
                        onerror="this.src='https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode(auth()->user()->name) }}'"
                    >
                </div>

                <!-- Name -->
                <h2 style="margin: 0 0 12px; font-size: 24px; font-weight: 700; color: #0f172a;">
                    {{ auth()->user()->name ?: '同学' }}
                </h2>

                <!-- Verified Badge -->
                <div style="display: inline-flex; align-items: center; gap: 6px; background: #dcfce7; color: #166534; padding: 6px 16px; border-radius: 999px; margin-bottom: 24px; font-size: 13px; font-weight: 600;">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    <span>已认证学生</span>
                </div>

                <!-- Stats -->
                <div style="width: 100%; display: flex; flex-direction: column; gap: 16px; margin-top: 8px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #F5F5F7;">
                        <span style="color: #64748b; font-size: 14px;">已售出</span>
                        <span style="color: #0f172a; font-size: 18px; font-weight: 700;">0</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #F5F5F7;">
                        <span style="color: #64748b; font-size: 14px;">已购买</span>
                        <span style="color: #0f172a; font-size: 18px; font-weight: 700;">0</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0;">
                        <span style="color: #64748b; font-size: 14px;">信誉评分</span>
                        <span style="color: #0f172a; font-size: 18px; font-weight: 700;">5.0 ⭐</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Edit Form -->
        <div style="background: white; border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.08); padding: 40px;">
            <div style="margin-bottom: 32px;">
                <h2 style="margin: 0 0 8px; font-size: 28px; font-weight: 700; color: #0f172a;">编辑资料</h2>
                <p style="margin: 0; color: #94a3b8; font-size: 15px;">更新您的个人信息</p>
            </div>

            <form method="POST" action="{{ route('user.profile.update') }}" style="display: flex; flex-direction: column; gap: 24px;">
                @csrf
                @method('PUT')

                <!-- Nickname -->
                <div>
                    <label for="name" style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a; font-size: 14px;">
                        昵称 <span style="color:#ef4444;">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', auth()->user()->name) }}"
                        required
                        placeholder="请输入您的昵称"
                        style="width: 100%; padding: 14px 16px; background: #F5F5F7; border: none; border-radius: 12px; font-size: 15px; color: #0f172a; transition: all 0.2s ease; outline: none; box-sizing: border-box;"
                        onfocus="this.style.background='white'; this.style.border='2px solid #4F46E5';"
                        onblur="this.style.background='#F5F5F7'; this.style.border='none';"
                    >
                </div>

                <!-- Student ID (Read-only) -->
                <div>
                    <label for="student_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a; font-size: 14px;">
                        学号
                    </label>
                    <input 
                        type="text" 
                        id="student_id" 
                        value="{{ auth()->user()->student_id ?: '未设置' }}"
                        readonly
                        style="width: 100%; padding: 14px 16px; background: #F5F5F7; border: none; border-radius: 12px; font-size: 15px; color: #94a3b8; cursor: not-allowed; box-sizing: border-box;"
                    >
                    <p style="margin: 8px 0 0; color: #94a3b8; font-size: 13px;">学号不可修改</p>
                </div>

                <!-- Contact Method -->
                <div>
                    <label for="phone" style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a; font-size: 14px;">
                        联系方式
                    </label>
                    <input 
                        type="text" 
                        id="phone" 
                        name="phone" 
                        value="{{ old('phone', auth()->user()->phone) }}"
                        placeholder="手机号或微信号"
                        style="width: 100%; padding: 14px 16px; background: #F5F5F7; border: none; border-radius: 12px; font-size: 15px; color: #0f172a; transition: all 0.2s ease; outline: none; box-sizing: border-box;"
                        onfocus="this.style.background='white'; this.style.border='2px solid #4F46E5';"
                        onblur="this.style.background='#F5F5F7'; this.style.border='none';"
                    >
                </div>

                <!-- Email (Read-only) -->
                <div>
                    <label for="email" style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a; font-size: 14px;">
                        邮箱
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        value="{{ auth()->user()->email }}"
                        readonly
                        style="width: 100%; padding: 14px 16px; background: #F5F5F7; border: none; border-radius: 12px; font-size: 15px; color: #94a3b8; cursor: not-allowed; box-sizing: border-box;"
                    >
                    <p style="margin: 8px 0 0; color: #94a3b8; font-size: 13px;">邮箱不可修改</p>
                </div>

                <!-- Save Button -->
                <div style="display: flex; justify-content: flex-end; margin-top: 8px;">
                    <button 
                        type="submit" 
                        style="min-width: 140px; padding: 12px 24px; background: #4F46E5; color: white; border: none; border-radius: 12px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.2s ease;"
                        onmouseover="this.style.background='#4338CA';"
                        onmouseout="this.style.background='#4F46E5';"
                    >
                        保存修改
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @media (max-width: 968px) {
        .profile-layout {
            grid-template-columns: 1fr !important;
        }
        .profile-layout > div:first-child {
            position: static !important;
        }
    }

    /* 登录成功消息动画 */
    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes slideOutUp {
        from {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
        to {
            opacity: 0;
            transform: translateY(-30px) scale(0.9);
        }
    }

    .success-message {
        animation: slideInDown 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    .success-message.fade-out {
        animation: slideOutUp 0.4s cubic-bezier(0.55, 0.055, 0.675, 0.19) forwards;
    }

    .success-message::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, #22c55e, #16a34a);
        transform: scaleX(0);
        transform-origin: left;
        animation: progressBar 2.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    @keyframes progressBar {
        to {
            transform: scaleX(1);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const successMessage = document.getElementById('success-message');
        
        if (successMessage) {
            // 2.5秒后开始淡出动画
            setTimeout(function() {
                successMessage.classList.add('fade-out');
                
                // 动画结束后移除元素
                setTimeout(function() {
                    successMessage.style.display = 'none';
                }, 400); // 与 fade-out 动画时长一致
            }, 2500); // 显示2.5秒后开始消失
        }
    });
</script>
@endsection
