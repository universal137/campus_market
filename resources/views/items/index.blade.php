@extends('layouts.app')

@section('title', '二手交易 · 校园易')

@section('content')
    <section class="surface">
        <div style="display:flex;flex-direction:column;gap:18px;">
            <div>
                <h2>二手好物广场</h2>
                <p style="color:#94a3b8;margin-top:4px;">按照分类、关键字筛选，快速找到心仪闲置</p>
            </div>
            <form method="GET" class="form-grid" style="align-items:end;">
                <div>
                    <label for="q">关键字</label>
                    <input type="text" id="q" name="q" value="{{ $filters['q'] }}" placeholder="关键词，如 iPad / 计算器">
                </div>
                <div>
                    <label for="category">分类</label>
                    <select name="category" id="category">
                        <option value="">全部分类</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected($filters['category'] == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button class="btn btn-secondary" style="width:100%;">筛选</button>
                </div>
            </form>
        </div>
    </section>

    <section class="surface">
        @if (session('success'))
            <div class="error-msg" style="background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;">
                {{ session('success') }}
            </div>
        @endif

        <h3 style="margin-top:0;">快速发布一条二手商品</h3>
        <p style="color:#94a3b8;margin-top:4px;">暂未接入账号系统，填写联系人信息即可体验发布流程</p>

        <form method="POST" action="{{ route('items.store') }}" style="margin-top:16px;display:flex;flex-direction:column;gap:16px;">
            @csrf
            @if ($errors->any())
                <div class="error-msg">
                    <strong>请检查以下输入：</strong>
                    <ul style="margin:8px 0 0 16px;padding:0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-grid">
                <div>
                    <label for="seller_name">联系人昵称</label>
                    <input id="seller_name" name="seller_name" value="{{ old('seller_name') }}" required>
                </div>
                <div>
                    <label for="seller_email">校园邮箱</label>
                    <input type="email" id="seller_email" name="seller_email" value="{{ old('seller_email') }}" required>
                </div>
                <div>
                    <label for="category_id">商品分类</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">请选择</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="price">价格 (¥)</label>
                    <input type="number" step="0.01" min="0" id="price" name="price" value="{{ old('price') }}" required>
                </div>
            </div>

            <div>
                <label for="title">标题</label>
                <input id="title" name="title" value="{{ old('title') }}" placeholder="例如：九成新考研英语黄皮书" required>
            </div>

            <div>
                <label for="description">详情描述</label>
                <textarea id="description" name="description" placeholder="成色、使用情况、交易地点等" required>{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="align-self:flex-start;">发布商品</button>
        </form>
    </section>

    <section class="surface">
        <h3 style="margin-top:0;">最新商品</h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:18px;margin-top:18px;">
            @forelse($items as $item)
                <article style="border:1px solid #e2e8f0;border-radius:16px;padding:18px;">
                    <p class="status-pill" style="display:inline-block;margin-bottom:10px;">{{ optional($item->category)->name ?? '未分类' }}</p>
                    <h4 style="margin:0 0 6px;">{{ $item->title }}</h4>
                    <p style="color:#475569;font-size:13px;margin:0 0 12px;">{{ \Illuminate\Support\Str::limit($item->description, 80) }}</p>
                    <p style="font-weight:700;font-size:18px;color:#ef4444;margin:0 0 10px;">¥{{ $item->price }}</p>
                    <p style="color:#94a3b8;font-size:12px;margin:0;">卖家：{{ $item->user->name }}</p>
                </article>
            @empty
                <p style="color:#94a3b8;">还没有任何商品，快来成为第一位发布者吧。</p>
            @endforelse
        </div>

        <div style="margin-top:20px;">
            {{ $items->links() }}
        </div>
    </section>
@endsection

