<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '校园易 - 校园二手与互助平台')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('head')
</head>
<body class="bg-gray-100">
    <header class="site-header">
        <div class="site-header__inner">
            <a href="{{ url('/') }}" class="site-header__logo">
                校园易
                <span class="site-header__tagline">校园二手与互助平台</span>
            </a>
            <nav class="site-header__nav" aria-label="主导航">
                <a href="{{ url('/') }}">概览</a>
                <a href="{{ route('items.index') }}">二手</a>
                <a href="{{ route('tasks.index') }}">互助</a>
            </nav>
            <div class="site-header__actions">
                <div class="user-identity">
                    @auth
                        <div class="user-identity__auth">
                            <button
                                type="button"
                                class="user-identity__avatar-btn"
                                id="user-menu-btn"
                                aria-expanded="false"
                                aria-haspopup="true"
                                aria-label="打开用户菜单"
                            >
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
                                @else
                                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode(auth()->user()->name) }}" alt="{{ auth()->user()->name }}">
                                @endif
                            </button>
                            <div class="user-dropdown" id="user-dropdown" style="display: none;">
                                <a href="{{ route('user.profile') }}" class="user-dropdown__profile-link">
                                    <div style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; transition: all 0.2s ease;">
                                        <div style="flex-shrink: 0;">
                                            @if(auth()->user()->avatar)
                                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(255,255,255,0.2);">
                                            @else
                                                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode(auth()->user()->name) }}" alt="{{ auth()->user()->name }}" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(255,255,255,0.2);">
                                            @endif
                                        </div>
                                        <div style="flex: 1; min-width: 0;">
                                            <p style="margin: 0; font-size: 16px; font-weight: 600; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                                                {{ auth()->user()->name }}
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.5; transition: transform 0.2s ease;">
                                                    <polyline points="9 18 15 12 9 6"/>
                                                </svg>
                                            </p>
                                            <p style="margin: 4px 0 0; font-size: 13px; color: #64748b; display: flex; align-items: center; gap: 6px;">
                                                @if(auth()->user()->student_id)
                                                    <span style="display: inline-flex; align-items: center; gap: 4px; background: #dcfce7; color: #166534; padding: 2px 6px; border-radius: 4px; font-size: 11px; font-weight: 600;">
                                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                                            <polyline points="22 4 12 14.01 9 11.01"/>
                                                        </svg>
                                                        已认证学生
                                                    </span>
                                                @else
                                                    <span style="display: inline-flex; align-items: center; gap: 4px; background: #fef3c7; color: #92400e; padding: 2px 6px; border-radius: 4px; font-size: 11px; font-weight: 600;">
                                                        待认证
                                                    </span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </a>
                                <div class="pt-3 space-y-2">
                                    <a href="/user/published" class="user-dropdown__item">
                                        <span class="w-5 h-5 text-gray-500 flex items-center justify-center">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h11l5 5v11H4z"/><path d="M9 9h4"/><path d="M9 13h6"/></svg>
                                        </span>
                                        <span class="flex-1">
                                            <span class="text-sm font-medium text-gray-800 block">我发布的</span>
                                            <span class="text-xs text-gray-500 block">管理闲置</span>
                                        </span>
                                    </a>
                                    <a href="/user/orders" class="user-dropdown__item">
                                        <span class="w-5 h-5 text-gray-500 flex items-center justify-center">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6h15l-1.5 9H7.5z"/><path d="m6 6-2-3"/><circle cx="9" cy="20" r="1"/><circle cx="17" cy="20" r="1"/></svg>
                                        </span>
                                        <span class="flex-1">
                                            <span class="text-sm font-medium text-gray-800 block">我买到的</span>
                                            <span class="text-xs text-gray-500 block">查看交易记录</span>
                                        </span>
                                    </a>
                                    <a href="{{ route('user.collection') }}" class="user-dropdown__item">
                                        <span class="w-5 h-5 text-gray-500 flex items-center justify-center">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c0 4-7 7-7 7s-7-3-7-7a4 4 0 0 1 4-4c1.2 0 2.4.8 3 2 .6-1.2 1.8-2 3-2a4 4 0 0 1 4 4Z"/></svg>
                                        </span>
                                        <span class="flex-1">
                                            <span class="text-sm font-medium text-gray-800 block">我的收藏</span>
                                            <span class="text-xs text-gray-500 block">收藏夹</span>
                                        </span>
                                    </a>
                                    <a href="/user/notifications" class="user-dropdown__item">
                                        <span class="w-5 h-5 text-gray-500 flex items-center justify-center">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M18 16v-5a6 6 0 1 0-12 0v5l-1.5 3h15z"/><path d="M10 20a2 2 0 0 0 4 0"/></svg>
                                        </span>
                                        <span class="flex-1">
                                            <span class="text-sm font-medium text-gray-800 block">消息通知</span>
                                            <span class="text-xs text-gray-500 block">未读提醒</span>
                                        </span>
                                    </a>
                                </div>
                                <div class="mt-3 border-t border-white/30 pt-3 space-y-2">
                                    <a href="/settings" class="user-dropdown__item">
                                        <span class="w-5 h-5 text-gray-500 flex items-center justify-center">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9c0 .69.4 1.31 1.02 1.58.96.42 1.62 1.37 1.62 2.42s-.66 2-1.62 2.42A1.65 1.65 0 0 0 19.4 15Z"/></svg>
                                        </span>
                                        <span class="flex-1 text-sm font-medium text-gray-800">设置</span>
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="user-dropdown__item user-dropdown__item--logout" style="width: 100%; text-align: left;">
                                            <span class="w-5 h-5 text-gray-500 flex items-center justify-center">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                            </span>
                                            <span class="flex-1 text-sm font-medium text-gray-700">退出登录</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endauth
                    @guest
                        <a href="{{ route('login') }}" class="user-identity__guest">登录 / 注册</a>
                    @endguest
                </div>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        © {{ date('Y') }} 校园易 · 校园二手与互助平台（演示版）
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
