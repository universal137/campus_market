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

            <form method="POST" action="{{ route('user.profile.update') }}" style="display: flex; flex-direction: column; gap: 32px;">
                @csrf
                @method('PUT')

                <!-- Group 1: Basic Info -->
                <div class="form-group">
                    <div style="margin-bottom: 20px;">
                        <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            基本信息
                        </h3>
                        <p style="margin: 4px 0 0; color: #94a3b8; font-size: 13px;">您的个人基础信息</p>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 20px;">
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
                                class="form-input"
                            >
                        </div>

                        <!-- Bio -->
                        <div>
                            <label for="bio" style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a; font-size: 14px;">
                                个人简介
                            </label>
                            <textarea 
                                id="bio" 
                                name="bio" 
                                rows="4"
                                placeholder="介绍一下自己吧..."
                                class="form-input"
                                style="resize: vertical; min-height: 100px; font-family: inherit;"
                            >{{ old('bio', auth()->user()->bio) }}</textarea>
                            <p style="margin: 6px 0 0; color: #94a3b8; font-size: 12px;">最多500个字符</p>
                        </div>
                    </div>
                </div>

                <!-- Group 2: Campus Identity -->
                <div class="form-group">
                    <div style="margin-bottom: 20px;">
                        <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/>
                            </svg>
                            校园身份
                        </h3>
                        <p style="margin: 4px 0 0; color: #94a3b8; font-size: 13px;">完善您的学生身份信息</p>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 20px;">
                        <!-- School -->
                        <div>
                            <label for="school" style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a; font-size: 14px;">
                                学校
                            </label>
                            <input 
                                type="text" 
                                id="school" 
                                name="school" 
                                value="{{ old('school', auth()->user()->school) }}"
                                placeholder="例如：北京大学"
                                class="form-input"
                            >
                        </div>

                        <!-- Major -->
                        <div>
                            <label for="major" style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a; font-size: 14px;">
                                专业
                            </label>
                            <input 
                                type="text" 
                                id="major" 
                                name="major" 
                                value="{{ old('major', auth()->user()->major) }}"
                                placeholder="例如：计算机科学"
                                class="form-input"
                            >
                        </div>

                        <!-- Student ID with Verification Badge -->
                        <div>
                            <label for="student_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a; font-size: 14px;">
                                学号
                                @if(auth()->user()->student_id)
                                    <span style="display: inline-flex; align-items: center; gap: 4px; margin-left: 8px; background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 6px; font-size: 11px; font-weight: 600;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                            <polyline points="22 4 12 14.01 9 11.01"/>
                                        </svg>
                                        已认证
                                    </span>
                                @endif
                            </label>
                            @if(auth()->user()->student_id)
                                <div style="position: relative;">
                                    <input 
                                        type="text" 
                                        id="student_id" 
                                        value="{{ auth()->user()->student_id }}"
                                        readonly
                                        style="width: 100%; padding: 14px 16px; background: #F5F5F7; border: none; border-radius: 12px; font-size: 15px; color: #64748b; cursor: not-allowed; box-sizing: border-box;"
                                    >
                                </div>
                                <p style="margin: 6px 0 0; color: #94a3b8; font-size: 12px;">已认证的学号不可修改，如需修改请联系管理员</p>
                            @else
                                <input 
                                    type="text" 
                                    id="student_id" 
                                    name="student_id" 
                                    value="{{ old('student_id') }}"
                                    placeholder="请输入您的学号"
                                    class="form-input"
                                >
                                <p style="margin: 6px 0 0; color: #94a3b8; font-size: 12px;">提交后将进入认证流程</p>
                            @endif
                        </div>

                        <!-- Enrollment Year -->
                        <div>
                            <label for="enrollment_year" style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a; font-size: 14px;">
                                入学年份
                            </label>
                            <input 
                                type="number" 
                                id="enrollment_year" 
                                name="enrollment_year" 
                                value="{{ old('enrollment_year', auth()->user()->enrollment_year) }}"
                                placeholder="例如：2022"
                                min="1900"
                                max="{{ date('Y') + 10 }}"
                                class="form-input"
                            >
                        </div>
                    </div>
                </div>

                <!-- Contact & Email Section -->
                <div class="form-group">
                    <div style="margin-bottom: 20px;">
                        <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                            联系方式
                        </h3>
                        <p style="margin: 4px 0 0; color: #94a3b8; font-size: 13px;">您的联系方式信息</p>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 20px;">
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
                                class="form-input"
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
                            <p style="margin: 6px 0 0; color: #94a3b8; font-size: 12px;">邮箱不可修改</p>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div style="display: flex; justify-content: flex-end; margin-top: 8px; padding-top: 24px; border-top: 1px solid #F5F5F7;">
                    <button 
                        type="submit" 
                        class="submit-btn"
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

    /* Form Groups */
    .form-group {
        padding: 24px;
        background: #FAFAFA;
        border-radius: 16px;
        border: 1px solid #F0F0F0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .form-group:hover {
        border-color: #E5E5E5;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    /* Form Inputs */
    .form-input {
        width: 100%;
        padding: 14px 16px;
        background: #F5F5F7;
        border: none;
        border-radius: 12px;
        font-size: 15px;
        color: #0f172a;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        outline: none;
        box-sizing: border-box;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }

    .form-input:focus {
        background: white;
        border: 2px solid #4F46E5;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        transform: translateY(-1px);
    }

    .form-input::placeholder {
        color: #94a3b8;
    }

    /* Submit Button */
    .submit-btn {
        min-width: 140px;
        padding: 14px 32px;
        background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        position: relative;
        overflow: hidden;
    }

    .submit-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
    }

    .submit-btn:hover::before {
        left: 100%;
    }

    .submit-btn:active {
        transform: translateY(0);
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
