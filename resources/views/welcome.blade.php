<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>校园二手与互助平台 - 测试版</title>
    <style>
        body { font-family: sans-serif; max-width: 1000px; margin: 0 auto; padding: 20px; background: #f4f4f4; }
        h1 { text-align: center; color: #333; }
        .section { background: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .tag { display: inline-block; background: #e0e0e0; padding: 5px 10px; border-radius: 15px; margin: 5px; font-size: 14px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; }
        .card { border: 1px solid #ddd; padding: 10px; border-radius: 5px; background: #fff; }
        .price { color: #e74c3c; font-weight: bold; font-size: 18px; }
        .user { font-size: 12px; color: #666; margin-top: 5px; }
        .task-item { border-bottom: 1px solid #eee; padding: 10px 0; }
        .reward { color: #27ae60; font-weight: bold; float: right; }
    </style>
</head>
<body>

    <h1>🎓 校园二手与互助平台 (开发测试版)</h1>

    <div class="section">
        <h3>📂 商品分类</h3>
        <div>
            @foreach($categories as $category)
                <span class="tag">{{ $category->name }}</span>
            @endforeach
        </div>
    </div>

    <div class="section">
        <h3>🔥 最新闲置</h3>
        <div class="grid">
            @foreach($items as $item)
                <div class="card">
                    <div style="height:100px; background:#eee; display:flex; align-items:center; justify-content:center; color:#aaa;">
                        商品图
                    </div>
                    <h4>{{ $item->title }}</h4>
                    <div class="price">¥{{ $item->price }}</div>
                    <div class="user">卖家: {{ $item->user->name }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="section">
        <h3>🤝 互助广场</h3>
        @foreach($tasks as $task)
            <div class="task-item">
                <span class="reward">💰 {{ $task->reward }}</span>
                <strong>{{ $task->title }}</strong>
                <br>
                <small style="color:#888">{{ \Illuminate\Support\Str::limit($task->content, 50) }}</small>
                <div class="user">发布人: {{ $task->user->name }}</div>
            </div>
        @endforeach
    </div>

</body>
</html>
