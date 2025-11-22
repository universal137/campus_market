<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '校园易 - 校园二手与互助平台')</title>
    <style>
        :root {
            color-scheme: light;
            font-family: "Segoe UI", "PingFang SC", "Microsoft YaHei", sans-serif;
        }
        body {
            margin: 0;
            background: #f5f5f7;
            color: #1d1d1f;
            font-weight: 500;
        }
        [v-cloak] {
            display: none;
        }
        header.site-header {
            display: flex;
            justify-content: center;
            width: 100%;
            background: transparent;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .site-header__inner {
            width: 100%;
            max-width: 1200px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }
        .site-header__actions {
            display: flex;
            align-items: center;
            gap: 18px;
        }
        .site-header__actions .telemetry-pill {
            margin-right: 6px;
        }
        .site-header__logo {
            font-size: 2.5rem;
            font-weight: 900;
            letter-spacing: -0.04em;
            color: #000;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            line-height: 1;
            display: inline-flex;
            align-items: center;
        }
        .site-header__tagline {
            font-size: 0.95rem;
            color: rgba(28, 28, 30, 0.65);
            margin-left: 12px;
            font-weight: 600;
            letter-spacing: 0.02em;
        }
        .site-header__nav {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
            justify-content: center;
            flex-wrap: wrap;
        }
        .site-header__nav a {
            color: #1d1d1f;
            border-radius: 999px;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            border: 1px solid rgba(0, 0, 0, 0.08);
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            transition: border-color 0.2s ease, background 0.2s ease, color 0.2s ease;
        }
        .site-header__nav a:hover,
        .site-header__nav a:focus {
            background: rgba(255, 255, 255, 0.9);
            border-color: rgba(0, 0, 0, 0.25);
        }
        header.site-header .site-header__logo {
            transition: inherit;
        }
        .site-header__weather {
            flex-shrink: 0;
        }
        .user-identity {
            position: relative;
            flex-shrink: 0;
        }
        .user-identity__guest {
            border: none;
            border-radius: 999px;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            background: #111;
            color: white;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.2s ease;
            box-shadow: 0 20px 40px -22px rgba(0, 0, 0, 0.45);
        }
        .user-identity__guest:hover,
        .user-identity__guest:focus-visible {
            background: #1f1f1f;
            transform: translateY(-1px) scale(1.01);
            outline: none;
        }
        .user-identity__auth {
            position: relative;
            display: flex;
            align-items: center;
        }
        .user-identity__avatar-btn {
            border: none;
            background: transparent;
            padding: 0;
            cursor: pointer;
            border-radius: 999px;
            width: 48px;
            height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 18px 42px -25px rgba(15, 23, 42, 0.75);
            transition: transform 0.25s ease;
        }
        .user-identity__avatar-btn:focus-visible {
            outline: 2px solid rgba(15, 23, 42, 0.25);
            outline-offset: 4px;
        }
        .user-identity__avatar-btn:hover {
            transform: translateY(-2px) scale(1.03);
        }
        .user-identity__avatar-btn img {
            width: 40px;
            height: 40px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.8);
        }
        .user-dropdown {
            position: absolute;
            top: calc(100% + 14px);
            right: 0;
            min-width: 220px;
            border-radius: 20px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(26px);
            -webkit-backdrop-filter: blur(26px);
            box-shadow: 0 30px 70px -35px rgba(15, 23, 42, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .user-dropdown__item {
            width: 100%;
            border: none;
            background: transparent;
            border-radius: 14px;
            padding: 10px 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #0f172a;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease, color 0.2s ease;
        }
        .user-dropdown__item:hover,
        .user-dropdown__item:focus-visible {
            background: rgba(15, 23, 42, 0.06);
            color: #0f5af2;
            outline: none;
        }
        .user-dropdown__icon {
            width: 32px;
            height: 32px;
            border-radius: 12px;
            background: rgba(15, 23, 42, 0.08);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .user-dropdown svg {
            width: 1.25rem;
            height: 1.25rem;
            min-width: 1.25rem;
        }
        .w-5 {
            width: 1.25rem !important;
        }
        .h-5 {
            height: 1.25rem !important;
        }
        .min-w-\[1\.25rem\] {
            min-width: 1.25rem !important;
        }
        .user-dropdown__icon svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
        }
        .user-dropdown__label {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }
        .user-dropdown__badge {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #ef4444;
            display: inline-block;
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.8);
        }
        .user-dropdown-enter-active,
        .user-dropdown-leave-active {
            transition: opacity 0.18s ease, transform 0.18s cubic-bezier(0.32, 0.72, 0, 1);
            transform-origin: top right;
        }
        .user-dropdown-enter-from,
        .user-dropdown-leave-to {
            opacity: 0;
            transform: translateY(-10px) scale(0.94);
        }
        .user-dropdown-enter-to,
        .user-dropdown-leave-from {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
        .telemetry-pill {
            display: flex;
            align-items: center;
            gap: 16px;
            border-radius: 999px;
            padding: 14px 22px;
            background: rgba(255, 255, 255, 0.65);
            border: 1px solid rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            box-shadow: 0 35px 70px -50px rgba(15, 23, 42, 0.55);
            color: #1d1d1f;
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
        }
        .telemetry-pill__icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(17, 17, 17, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f39c12;
        }
        .telemetry-pill__meta {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }
        .telemetry-pill__temp {
            font-size: 15px;
            font-weight: 700;
            color: #111;
        }
        .telemetry-pill__date {
            font-size: 12px;
            color: rgba(17, 17, 17, 0.55);
        }
        main {
            max-width: 1200px;
            margin: 0 auto 80px;
            padding: 128px 24px 24px;
            position: relative;
            z-index: 0;
        }
        .surface {
            background: #fff;
            border-radius: 28px;
            padding: 36px;
            margin-bottom: 32px;
            box-shadow: 0 25px 65px -40px rgba(15, 23, 42, 0.35);
            border: 1px solid rgba(148, 163, 184, 0.25);
        }
        .surface--frosted {
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 40px 80px -50px rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .surface--hero {
            padding: 48px;
            overflow: hidden;
        }
        .section-title {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
        }
        .section-subtitle {
            color: #94a3b8;
            font-size: 15px;
            margin: 8px 0 0;
        }
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 24px;
            margin-top: 24px;
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        .card {
            background: transparent;
            border-radius: 0;
            padding: 0;
            border: none;
            box-shadow: none;
            transition: transform 0.18s ease;
        }
        .card--product {
            display: flex;
            flex-direction: column;
            gap: 16px;
            height: 100%;
        }
        .card--clickable {
            cursor: pointer;
        }
        .card--clickable:hover {
            transform: translateY(-5px);
            box-shadow: 0 22px 40px -18px rgba(15, 23, 42, 0.35);
        }
        .card__media {
            height: 200px;
            border-radius: 26px;
            background: #fefefe;
            overflow: hidden;
            position: relative;
            cursor: pointer;
        }
        .card__media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .card--product:hover .card__media img {
            transform: scale(1.06);
        }
        .card__title {
            font-size: 16px;
            font-weight: 700;
            color: #000;
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .card__price {
            margin: 8px 0 0;
            font-weight: 700;
            font-size: 20px;
            color: #0f5af2;
        }
        .card__meta {
            margin-top: auto;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .meta-text {
            font-size: 13px;
            color: rgba(15, 23, 42, 0.45);
            margin: 0;
        }
        .muted {
            color: #94a3b8;
        }
        .task-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 24px;
        }
        .task-card {
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            padding: 22px 26px;
            display: flex;
            gap: 16px;
            align-items: center;
            background: white;
            box-shadow: 0 12px 30px -16px rgba(15, 23, 42, 0.18);
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }
        .task-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px -22px rgba(15, 23, 42, 0.35);
        }
        .task-card__avatar {
            width: 52px;
            height: 52px;
            border-radius: 999px;
            background: #eef2ff;
            color: #4338ca;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 700;
            flex-shrink: 0;
        }
        .task-card__body {
            flex: 1;
            min-width: 0;
        }
        .task-card__heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .task-card__title {
            margin: 0 0 6px;
            font-size: 17px;
            font-weight: 600;
            color: #0f172a;
        }
        .pill-collection {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 8px;
        }
        .card-link {
            text-decoration: none;
            color: inherit;
            height: 100%;
            cursor: pointer;
        }
        @media (min-width: 1024px) {
            .card-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }
        footer {
            text-align: center;
            padding: 40px 20px 60px;
            color: #94a3b8;
            font-size: 14px;
        }
        .btn {
            border: none;
            border-radius: 999px;
            padding: 12px 22px;
            font-weight: 600;
            cursor: pointer;
            font-size: 15px;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: white;
            box-shadow: 0 12px 30px -12px rgba(37, 99, 235, 0.7);
        }
        .btn-secondary {
            background: #eef2ff;
            color: #4338ca;
            box-shadow: 0 10px 20px -15px rgba(79, 70, 229, 0.8);
        }
        .btn:hover,
        .btn:focus {
            transform: translateY(-1px);
            box-shadow: 0 14px 30px -14px rgba(15, 23, 42, 0.35);
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
        }
        .search-combo {
            margin-top: 28px;
            background: rgba(255, 255, 255, 0.58);
            border-radius: 26px;
            padding: 18px 18px 18px 26px;
            display: flex;
            align-items: center;
            gap: 18px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 45px 90px -60px rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            flex-wrap: wrap;
        }
        .search-combo__field {
            flex: 1;
            min-width: 180px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .search-combo__label {
            font-size: 12px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #94a3b8;
        }
        .search-combo select,
        .search-combo input {
            border: none;
            border-radius: 0;
            padding: 0;
            font-size: 16px;
            font-weight: 600;
            background: transparent;
            color: #0f172a;
            min-height: 32px;
        }
        .search-combo select {
            appearance: none;
            -webkit-appearance: none;
        }
        .search-combo select:focus,
        .search-combo input:focus {
            outline: none;
            box-shadow: none;
        }
        .search-combo__divider {
            width: 1px;
            height: 48px;
            background: rgba(148, 163, 184, 0.35);
        }
        .search-combo__action {
            width: 56px;
            height: 56px;
            border-radius: 999px;
            border: none;
            background: #111;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 18px 32px -18px rgba(0, 0, 0, 0.55);
            cursor: pointer;
            flex-shrink: 0;
            transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.2s ease;
        }
        .search-combo__action:hover,
        .search-combo__action:focus {
            transform: translateY(-2px);
            box-shadow: 0 26px 45px -20px rgba(14, 165, 233, 0.75);
            outline: none;
        }
        .search-combo__action svg {
            width: 22px;
            height: 22px;
        }
        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
            letter-spacing: 0.02em;
        }
        input[type="text"],
        input[type="email"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            border-radius: 16px;
            border: 1px solid #c7d2fe;
            padding: 14px 16px;
            font-size: 15px;
            box-sizing: border-box;
            background: #fff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        input:focus,
        textarea:focus,
        select:focus {
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 8px 20px -12px rgba(59, 130, 246, 0.8);
        }
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        .error-msg {
            background: #fee2e2;
            color: #b91c1c;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 18px;
        }
        .status-pill {
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            background: #dbeafe;
            color: #1d4ed8;
        }
        .status-pill--success {
            background: #dcfce7;
            color: #14532d;
        }
        .status-pill--danger {
            background: #fee2e2;
            color: #b91c1c;
        }
        .category-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: 999px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid rgba(59, 130, 246, 0.18);
            transition: background 0.2s ease, color 0.2s ease, transform 0.15s ease, box-shadow 0.15s ease;
        }
        .category-pill:hover {
            background: #dbeafe;
            color: #1e3a8a;
            box-shadow: 0 8px 20px -12px rgba(59, 130, 246, 0.6);
            transform: translateY(-2px);
        }
        .pill-collection a {
            text-decoration: none;
            color: inherit;
        }
        .hot-ticker {
            margin-top: 28px;
            border-radius: 18px;
            padding: 12px 20px;
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            font-size: 14px;
            color: #1d1d1f;
            display: flex;
            align-items: center;
            gap: 14px;
            overflow: hidden;
        }
        .hot-ticker__label {
            font-weight: 700;
            letter-spacing: 0.1em;
            font-size: 12px;
            color: rgba(17, 17, 17, 0.6);
        }
        .hot-ticker__marquee {
            flex: 1;
            overflow: hidden;
        }
        .hot-ticker__track {
            display: flex;
            gap: 36px;
            animation: ticker 14s linear infinite;
        }
        .hot-ticker__item {
            color: rgba(17, 17, 17, 0.85);
            font-weight: 600;
            white-space: nowrap;
        }
        .card__body {
            padding-top: 12px;
        }
        .card-grid .card-link {
            display: block;
        }
        .card-grid .card-link:hover .card__title {
            color: #0f5af2;
        }
        @keyframes ticker {
            from { transform: translateX(0); }
            to { transform: translateX(-50%); }
        }
        .anim-fade-up {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeUp 0.9s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
            animation-delay: var(--delay, 0s);
        }
        @keyframes fadeUp {
            to { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 768px) {
            header.site-header {
                padding: 0 20px;
            }
            header.site-header.is-scrolled {
                padding: 0 18px;
            }
            .site-header__inner {
                flex-wrap: wrap;
                gap: 16px;
            }
            .site-header__nav {
                order: 3;
                width: 100%;
                justify-content: flex-start;
            }
            .site-header__weather,
            .site-header__logo {
                width: 100%;
            }
        }
        @media (max-width: 640px) {
            .search-combo {
                border-radius: 24px;
                padding: 18px;
            }
            .search-combo__divider {
                display: none;
            }
            .search-combo__action {
                width: 100%;
                border-radius: 18px;
                height: 52px;
            }
            header.site-header {
                height: auto;
            }
            .site-header__logo {
                font-size: 2rem;
            }
            .telemetry-pill {
                width: 100%;
                justify-content: space-between;
            }
            .surface { padding: 24px; }
            main { padding: 0 16px; }
        }
    </style>
    @stack('head')
</head>
<body>
    <div id="layout-app" v-cloak>
    <header
        class="site-header anim-fade-up fixed top-0 left-0 w-full z-[999] overflow-visible transition-all duration-300 ease-in-out flex items-center justify-between px-8"
        :class="isScrolled ? 'bg-white/70 backdrop-blur-xl shadow-sm h-16' : 'bg-transparent h-24'"
    >
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
                <div class="telemetry-pill site-header__weather">
                    <div class="telemetry-pill__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="5"/>
                            <path d="M12 2v2M12 20v2M4.93 4.93l1.42 1.42M17.65 17.65l1.42 1.42M2 12h2M20 12h2M4.93 19.07l1.42-1.42M17.65 6.35l1.42-1.42"/>
                        </svg>
                    </div>
                    <div class="telemetry-pill__meta">
                        <span class="telemetry-pill__temp">23°C · 晴</span>
                        <span class="telemetry-pill__date">11月21日 周五 · 校园微风 2 级</span>
                    </div>
                </div>
                <div id="user-identity" class="user-identity" ref="userIdentityEl">
                    <template v-if="!isLoggedIn">
                        <button type="button" class="user-identity__guest">登录 / 注册</button>
                    </template>
                    <template v-else>
                        <div class="user-identity__auth" @mouseenter="openMenu" @mouseleave="closeMenu">
                            <button
                                type="button"
                                class="user-identity__avatar-btn"
                                @click.stop="toggleMenu"
                                :aria-expanded="menuOpen ? 'true' : 'false'"
                                aria-haspopup="true"
                                aria-label="打开用户菜单"
                            >
                                <img src="https://i.pravatar.cc/150?img=3" alt="用户头像">
                            </button>
                            <Transition
                                enter-active-class="user-dropdown-enter-active"
                                leave-active-class="user-dropdown-leave-active"
                                enter-from-class="user-dropdown-enter-from"
                                enter-to-class="user-dropdown-enter-to"
                                leave-from-class="user-dropdown-enter-to"
                                leave-to-class="user-dropdown-enter-from"
                            >
                                <div
                                    v-if="menuOpen"
                                    class="user-dropdown absolute top-full right-0 mt-2 z-[1000] bg-white/90 backdrop-blur-xl rounded-2xl border border-white/20 shadow-2xl p-4 min-w-[240px] text-sm font-medium text-gray-700"
                                    role="menu"
                                >
                                    <div class="pb-3 border-b border-white/40 text-gray-800">
                                        <p class="text-lg font-semibold">同学小明</p>
                                        <p class="mt-1 text-sm text-gray-500">信誉极好 · 已认证学生</p>
                                    </div>
                                    <div class="pt-3 space-y-2">
                                        <div class="flex items-center gap-3 p-3 rounded-2xl cursor-pointer hover:bg-white/70 transition-colors" role="menuitem">
                                            <span class="w-5 h-5 text-gray-500 flex items-center justify-center" aria-hidden="true">
                                                <svg viewBox="0 0 24 24" fill="none" class="w-5 h-5 min-w-[1.25rem]" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M4 4h11l5 5v11H4z"/>
                                                    <path d="M9 9h4"/>
                                                    <path d="M9 13h6"/>
                                                </svg>
                                            </span>
                                            <span class="flex-1">
                                                <span class="text-sm font-medium text-gray-800 block">我发布的</span>
                                                <span class="text-xs text-gray-500 block">管理闲置</span>
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-3 p-3 rounded-2xl cursor-pointer hover:bg-white/70 transition-colors" role="menuitem">
                                            <span class="w-5 h-5 text-gray-500 flex items-center justify-center" aria-hidden="true">
                                                <svg viewBox="0 0 24 24" fill="none" class="w-5 h-5 min-w-[1.25rem]" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M6 6h15l-1.5 9H7.5z"/>
                                                    <path d="m6 6-2-3"/>
                                                    <circle cx="9" cy="20" r="1"/>
                                                    <circle cx="17" cy="20" r="1"/>
                                                </svg>
                                            </span>
                                            <span class="flex-1">
                                                <span class="text-sm font-medium text-gray-800 block">我买到的</span>
                                                <span class="text-xs text-gray-500 block">查看交易记录</span>
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-3 p-3 rounded-2xl cursor-pointer hover:bg-white/70 transition-colors" role="menuitem">
                                            <span class="w-5 h-5 text-gray-500 flex items-center justify-center" aria-hidden="true">
                                                <svg viewBox="0 0 24 24" fill="none" class="w-5 h-5 min-w-[1.25rem]" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M19 14c0 4-7 7-7 7s-7-3-7-7a4 4 0 0 1 4-4c1.2 0 2.4.8 3 2 .6-1.2 1.8-2 3-2a4 4 0 0 1 4 4Z"/>
                                                </svg>
                                            </span>
                                            <span class="flex-1">
                                                <span class="text-sm font-medium text-gray-800 block">我的收藏</span>
                                                <span class="text-xs text-gray-500 block">收藏夹</span>
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-3 p-3 rounded-2xl cursor-pointer hover:bg-white/70 transition-colors" role="menuitem">
                                            <span class="w-5 h-5 text-gray-500 flex items-center justify-center" aria-hidden="true">
                                                <svg viewBox="0 0 24 24" fill="none" class="w-5 h-5 min-w-[1.25rem]" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M18 16v-5a6 6 0 1 0-12 0v5l-1.5 3h15z"/>
                                                    <path d="M10 20a2 2 0 0 0 4 0"/>
                                                </svg>
                                            </span>
                                            <span class="flex-1">
                                                <span class="text-sm font-medium text-gray-800 block">消息通知</span>
                                                <span class="text-xs text-gray-500 block">未读提醒</span>
                                            </span>
                                            <span class="ml-auto w-2 h-2 rounded-full bg-red-500"></span>
                                        </div>
                                    </div>
                                    <div class="mt-3 border-t border-white/30 pt-3 space-y-2">
                                        <div class="flex items-center gap-3 p-3 rounded-2xl cursor-pointer hover:bg-white/70 transition-colors" role="menuitem">
                                            <span class="w-5 h-5 text-gray-500 flex items-center justify-center" aria-hidden="true">
                                                <svg viewBox="0 0 24 24" fill="none" class="w-5 h-5 min-w-[1.25rem]" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="12" cy="12" r="3"/>
                                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9c0 .69.4 1.31 1.02 1.58.96.42 1.62 1.37 1.62 2.42s-.66 2-1.62 2.42A1.65 1.65 0 0 0 19.4 15Z"/>
                                                </svg>
                                            </span>
                                            <span class="flex-1 text-sm font-medium text-gray-800">设置</span>
                                        </div>
                                        <div class="flex items-center gap-3 p-3 rounded-2xl cursor-pointer hover:bg-red-50 transition-colors text-gray-700 hover:text-red-500" role="menuitem">
                                            <span class="w-5 h-5 text-gray-500 flex items-center justify-center" aria-hidden="true">
                                                <svg viewBox="0 0 24 24" fill="none" class="w-5 h-5 min-w-[1.25rem]" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                                    <polyline points="16 17 21 12 16 7"/>
                                                    <line x1="21" y1="12" x2="9" y2="12"/>
                                                </svg>
                                            </span>
                                            <span class="flex-1 text-sm font-medium">退出登录</span>
                                        </div>
                                    </div>
                                </div>
                            </Transition>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </header>

    <main class="pt-32 relative z-0">
        @yield('content')
    </main>

    <footer>
        © {{ date('Y') }} 校园易 · 校园二手与互助平台（演示版）
    </footer>
    </div>
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (!window.Vue) {
                return;
            }

            const { createApp, ref, onMounted, onUnmounted } = Vue;

            createApp({
                setup() {
                    const isLoggedIn = ref(true);
                    const menuOpen = ref(false);
                    const isScrolled = ref(false);
                    const userIdentityEl = ref(null);

                    const openMenu = () => {
                        menuOpen.value = true;
                    };

                    const closeMenu = () => {
                        menuOpen.value = false;
                    };

                    const toggleMenu = () => {
                        menuOpen.value = !menuOpen.value;
                    };

                    const handleOutsideClick = (event) => {
                        if (!userIdentityEl.value) {
                            return;
                        }
                        if (!userIdentityEl.value.contains(event.target)) {
                            menuOpen.value = false;
                        }
                    };

                    const handleScroll = () => {
                        isScrolled.value = window.scrollY > 0;
                    };

                    onMounted(() => {
                        document.addEventListener('click', handleOutsideClick);
                        window.addEventListener('scroll', handleScroll, { passive: true });
                        handleScroll();
                    });

                    onUnmounted(() => {
                        document.removeEventListener('click', handleOutsideClick);
                        window.removeEventListener('scroll', handleScroll);
                    });

                    return {
                        isLoggedIn,
                        menuOpen,
                        isScrolled,
                        userIdentityEl,
                        openMenu,
                        closeMenu,
                        toggleMenu
                    };
                }
            }).mount('#layout-app');
        });
    </script>
</body>
</html>

