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
            background: #f5f7fb;
        }
        header {
            background: #1f4ed8;
            color: white;
            padding: 24px 16px 32px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }
        header h1 {
            margin: 0 0 8px;
            font-size: 28px;
        }
        header p {
            margin: 0;
            opacity: 0.85;
        }
        nav {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-top: 18px;
            flex-wrap: wrap;
        }
        nav a {
            color: white;
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 999px;
            padding: 6px 18px;
            text-decoration: none;
            font-size: 14px;
        }
        nav a:hover,
        nav a:focus {
            background: rgba(255,255,255,0.2);
        }
        main {
            max-width: 1100px;
            margin: -40px auto 60px;
            padding: 0 16px;
        }
        .surface {
            background: white;
            border-radius: 18px;
            padding: 28px;
            margin-bottom: 24px;
            box-shadow: 0 12px 32px rgba(15,23,42,0.08);
        }
        footer {
            text-align: center;
            padding: 30px 20px 60px;
            color: #94a3b8;
            font-size: 13px;
        }
        .btn {
            border: none;
            border-radius: 10px;
            padding: 10px 16px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-primary {
            background: #2563eb;
            color: white;
        }
        .btn-secondary {
            background: #e2e8f0;
            color: #334155;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
        }
        label {
            display: block;
            font-size: 13px;
            color: #475569;
            margin-bottom: 6px;
        }
        input[type="text"],
        input[type="email"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            border-radius: 10px;
            border: 1px solid #cbd5f5;
            padding: 10px 12px;
            font-size: 14px;
            box-sizing: border-box;
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
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 12px;
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
        @media (max-width: 640px) {
            header h1 { font-size: 22px; }
            .surface { padding: 20px; }
        }
    </style>
    @stack('head')
</head>
<body>
    <header>
        <h1>校园易 · 校园二手与互助平台</h1>
        <p>连接校园闲置、技能与互助，让资源循环更轻松</p>
        <nav>
            <a href="{{ url('/') }}">概览</a>
            <a href="{{ route('items.index') }}">二手交易</a>
            <a href="{{ route('tasks.index') }}">互助任务</a>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        © {{ date('Y') }} 校园易 · 校园二手与互助平台（演示版）
    </footer>
</body>
</html>

