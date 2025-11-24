@extends('layouts.app')

@section('title', '编辑商品 · 校园易')

@section('content')
<div class="bg-[#F9FAFB] min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('user.published') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                返回我的发布
            </a>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">编辑商品</h1>
            <p class="text-gray-500">更新您的商品信息</p>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 md:p-10">
            <form method="POST" action="{{ route('items.update', $item) }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4">
                        <strong class="font-semibold">请检查以下输入：</strong>
                        <ul class="mt-2 ml-4 list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">商品分类</label>
                        <select 
                            id="category_id" 
                            name="category_id" 
                            required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        >
                            <option value="">请选择</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $item->category_id) == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">价格 (¥)</label>
                        <input 
                            type="number" 
                            step="0.01" 
                            min="0" 
                            id="price" 
                            name="price" 
                            value="{{ old('price', $item->price) }}" 
                            required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        >
                    </div>
                </div>

                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">标题</label>
                    <input 
                        id="title" 
                        name="title" 
                        value="{{ old('title', $item->title) }}" 
                        placeholder="例如：九成新考研英语黄皮书" 
                        required
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                    >
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">详情描述</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        placeholder="成色、使用情况、打包赠品等" 
                        required
                        rows="4"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"
                    >{{ old('description', $item->description) }}</textarea>
                </div>

                <div>
                    <label for="deal_place" class="block text-sm font-semibold text-gray-700 mb-2">交易地点（可选）</label>
                    <input 
                        id="deal_place" 
                        name="deal_place" 
                        value="{{ old('deal_place', $item->deal_place) }}"
                        placeholder="例如：东门奶茶店 / 图书馆一楼大厅 / 宿舍楼下公共区域"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                    >
                    <p class="text-gray-500 text-sm mt-2">建议选择人流量较大的校园公共区域，优先当面当场验货交易。</p>
                </div>

                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">商品状态</label>
                    <select 
                        id="status" 
                        name="status" 
                        required
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                    >
                        <option value="on_sale" @selected(old('status', $item->status) === 'on_sale')>在售</option>
                        <option value="sold" @selected(old('status', $item->status) === 'sold')>已售出</option>
                    </select>
                </div>

                <div class="flex gap-4 pt-4">
                    <button 
                        type="submit" 
                        class="flex-1 px-8 py-3 bg-blue-600 text-white font-semibold rounded-full transition-all duration-200 ease-in-out hover:bg-blue-700 active:scale-95 shadow-md hover:shadow-lg"
                    >
                        保存更改
                    </button>
                    <a 
                        href="{{ route('user.published') }}"
                        class="px-8 py-3 bg-gray-100 text-gray-700 font-semibold rounded-full transition-all duration-200 ease-in-out hover:bg-gray-200 active:scale-95"
                    >
                        取消
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

