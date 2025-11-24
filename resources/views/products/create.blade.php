@extends('layouts.app')

@section('title', '发布闲置 · 校园易')

@section('content')
<div class="bg-[#F9FAFB] min-h-screen py-12 px-4">
    <div class="max-w-2xl mx-auto animate-fade-in-up">
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 md:p-10">
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-gray-900 mb-3">发布闲置</h1>
                <p class="text-gray-500 text-base">填写详情，快速回血</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-8">
                    <p class="font-semibold mb-2">还有信息需要完善：</p>
                    <ul class="list-disc pl-5 space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Hero Uploader -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">商品实拍图</label>
                    <div 
                        id="hero-dropzone"
                        class="relative h-64 rounded-3xl border-2 border-dashed border-gray-200 bg-gray-50 flex items-center justify-center text-center cursor-pointer transition-all duration-300 hover:border-blue-400 hover:bg-white"
                        onclick="document.getElementById('product-image-input').click()"
                    >
                        <div id="hero-dropzone-placeholder" class="flex flex-col items-center gap-3">
                            <div class="w-16 h-16 rounded-2xl bg-white shadow-inner flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h1.5a2 2 0 001.8-1.1l.7-1.4A2 2 0 0110.7 3h2.6a2 2 0 011.7.5l1.5 1.4A2 2 0 0017.2 6H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-gray-800 font-semibold">上传实拍图</p>
                                <p class="text-sm text-gray-400">支持 JPG / PNG / WEBP · 建议 4:3</p>
                            </div>
                        </div>
                        <img id="hero-preview" src="" alt="商品图片预览" class="absolute inset-0 w-full h-full object-cover rounded-3xl hidden">
                        <button 
                            type="button" 
                            id="hero-remove-btn"
                            class="hidden absolute top-4 right-4 bg-black/60 text-white rounded-full p-2 hover:bg-black/80 transition"
                            onclick="resetHeroImage(event)"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <input 
                        type="file" 
                        id="product-image-input" 
                        name="image" 
                        accept="image/jpeg,image/jpg,image/png,image/webp"
                        class="hidden"
                        onchange="handleHeroImageSelect(event)"
                    >
                    @error('image')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="seller_name" class="block text-sm font-semibold text-gray-700 mb-2">联系人昵称</label>
                        <input 
                            id="seller_name" 
                            name="seller_name" 
                            value="{{ old('seller_name') }}" 
                            required
                            class="w-full bg-gray-50 border-0 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all"
                            placeholder="如 小向日葵"
                        >
                    </div>
                    <div>
                        <label for="seller_email" class="block text-sm font-semibold text-gray-700 mb-2">校园邮箱</label>
                        <input 
                            type="email" 
                            id="seller_email" 
                            name="seller_email" 
                            value="{{ old('seller_email') }}" 
                            required
                            class="w-full bg-gray-50 border-0 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all"
                            placeholder="example@campus.edu"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">商品分类</label>
                        <select 
                            id="category_id" 
                            name="category_id" 
                            required
                            class="w-full bg-gray-50 border-0 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all"
                        >
                            <option value="">请选择分类</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">标价 (¥)</label>
                        <input 
                            type="number" 
                            step="0.01" 
                            min="0" 
                            id="price" 
                            name="price" 
                            value="{{ old('price') }}" 
                            required
                            class="w-full bg-gray-50 border-0 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all"
                            placeholder="例如 120"
                        >
                    </div>
                </div>

                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">标题</label>
                    <input 
                        id="title" 
                        name="title" 
                        value="{{ old('title') }}" 
                        required
                        class="w-full bg-gray-50 border-0 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all"
                        placeholder="九成新 iPad Pro 11 寸"
                    >
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">详情描述</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="5"
                        required
                        class="w-full bg-gray-50 border-0 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all resize-none"
                        placeholder="说明成色、购买时间、附送配件、交易注意事项等"
                    >{{ old('description') }}</textarea>
                </div>

                <div>
                    <label for="deal_place" class="block text-sm font-semibold text-gray-700 mb-2">交易地点（可选）</label>
                    <input 
                        id="deal_place" 
                        name="deal_place" 
                        value="{{ old('deal_place') }}"
                        class="w-full bg-gray-50 border-0 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all"
                        placeholder="如 图书馆一楼 / 南门星巴克 / 宿舍楼下"
                    >
                </div>

                <button 
                    type="submit"
                    class="w-full py-4 bg-blue-600 text-white text-lg font-semibold rounded-2xl shadow-lg hover:bg-blue-700 hover:-translate-y-0.5 active:scale-95 transition-all duration-200"
                >
                    发布商品
                </button>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out forwards;
}
</style>

<script>
    const heroDropzone = document.getElementById('hero-dropzone');
    const heroPreview = document.getElementById('hero-preview');
    const heroPlaceholder = document.getElementById('hero-dropzone-placeholder');
    const heroRemoveBtn = document.getElementById('hero-remove-btn');
    const heroInput = document.getElementById('product-image-input');

    function handleHeroImageSelect(event) {
        const file = event.target.files[0];
        if (!file) {
            return;
        }

        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            alert('请上传 JPG、PNG 或 WEBP 格式的图片');
            heroInput.value = '';
            return;
        }

        const maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
            alert('图片大小不能超过 5MB');
            heroInput.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            heroPreview.src = e.target.result;
            heroPreview.classList.remove('hidden');
            heroPlaceholder.classList.add('opacity-0');
            heroRemoveBtn.classList.remove('hidden');
            heroDropzone.classList.remove('border-dashed', 'border-gray-200');
            heroDropzone.classList.add('border-solid', 'border-blue-200');
        };
        reader.readAsDataURL(file);
    }

    function resetHeroImage(event) {
        event.preventDefault();
        event.stopPropagation();
        heroPreview.src = '';
        heroPreview.classList.add('hidden');
        heroPlaceholder.classList.remove('opacity-0');
        heroRemoveBtn.classList.add('hidden');
        heroInput.value = '';
        heroDropzone.classList.add('border-dashed', 'border-gray-200');
        heroDropzone.classList.remove('border-solid', 'border-blue-200');
    }
</script>
@endsection

