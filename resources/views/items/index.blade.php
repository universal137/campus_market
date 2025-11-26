@extends('layouts.app')

@section('title', '二手交易 · 校园易')

@section('content')
    <div class="bg-[#F9FAFB] min-h-screen">
    <!-- Hero Section with Search & Filters -->
    <div class="bg-gradient-to-br from-gray-50 via-white to-gray-50 min-h-[400px] flex flex-col items-center justify-center px-4 py-16">
        <div class="w-full max-w-4xl">
            <!-- Page Title -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-3">二手好物广场</h1>
                <p class="text-gray-500 text-lg">按照分类、关键字筛选，快速找到心仪闲置</p>
            </div>

            <!-- Large Floating Search Bar + Primary Publish CTA -->
            <div class="flex flex-col md:flex-row items-center gap-4 mb-8">
                <form method="GET" class="flex-1 w-full">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <div class="relative max-w-2xl mx-auto md:mx-0">
                        <input 
                            type="text" 
                            id="q" 
                            name="q" 
                            value="{{ $filters['q'] }}" 
                            placeholder="搜索商品，如 iPad、计算器、教材..." 
                            class="w-full px-6 py-4 pl-14 pr-32 text-lg rounded-full border border-gray-200 bg-white shadow-lg focus:outline-none transition-shadow duration-300 ease-in-out focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            style="display: block;"
                        >
                        <svg class="absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none" 
                             width="20" 
                             height="20" 
                             fill="none" 
                             stroke="currentColor" 
                             viewBox="0 0 24 24"
                             style="max-width: 20px; max-height: 20px;"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <button 
                            type="submit" 
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 px-6 py-2 bg-blue-600 text-white rounded-full font-medium transition-all duration-200 ease-in-out hover:bg-blue-700 active:scale-95 z-10"
                        >
                            搜索
                        </button>
                    </div>
                </form>
                <button 
                    type="button"
                    onclick="openPublishModal()"
                    class="inline-flex items-center gap-2 px-6 py-4 bg-blue-600 text-white font-semibold rounded-full shadow-lg transition-transform duration-200 hover:bg-blue-700 hover:-translate-y-0.5 active:scale-95 whitespace-nowrap"
                >
                    <span class="w-5 h-5 flex items-center justify-center rounded-full bg-white/20">+</span>
                    发布闲置
                </button>
            </div>

            <!-- Horizontal Scrollable Category Pills -->
            <div class="flex gap-3 overflow-x-auto pb-4 scrollbar-hide -mx-4 px-4">
                <a 
                    href="{{ route('items.index', array_merge(request()->except('category'), ['category' => ''])) }}"
                    class="flex-shrink-0 px-6 py-2.5 rounded-full font-medium transition-all duration-200 {{ !$filters['category'] ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 transition-colors duration-200 ease-in-out hover:bg-gray-200' }}"
                >
                    全部
                </a>
                @foreach($categories as $category)
                    <a 
                        href="{{ route('items.index', array_merge(request()->except('category'), ['category' => $category->id])) }}"
                        class="flex-shrink-0 px-6 py-2.5 rounded-full font-medium transition-all duration-200 {{ $filters['category'] == $category->id ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 transition-colors duration-200 ease-in-out hover:bg-gray-200' }}"
                    >
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- CTA Banner (Replaces Quick Publish Form) -->
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl p-6 md:p-8 shadow-lg">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex-1 text-center md:text-left">
                    <h3 class="text-2xl font-bold text-white mb-2">有闲置要出售？</h3>
                    <p class="text-blue-100">快速发布你的商品，让闲置物品找到新主人</p>
                </div>
                <button 
                    type="button"
                    onclick="openPublishModal()" 
                    class="px-8 py-3 bg-white text-blue-600 font-semibold rounded-full transition-all duration-200 ease-in-out hover:bg-gray-50 active:scale-95 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                >
                    立即发布
                </button>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 shadow-sm">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Product Grid Section -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">最新商品</h2>
        
        @if($items->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8">
                @foreach($items as $item)
                    @php
                        $status = $item->status ?? 'active';
                        $isActive = $status === 'active';
                    @endphp
                    <div class="product-card-entry opacity-0 translate-y-8 transition-all duration-700 ease-out transform">
                        <article class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm transition-all duration-300 ease relative group {{ $isActive ? 'hover:-translate-y-1 hover:shadow-[0_8px_24px_rgba(0,0,0,0.1)] hover:border-slate-300' : 'opacity-95' }}">
                            <!-- Image Area -->
                            <div class="aspect-[4/3] bg-gray-100 overflow-hidden relative">
                                <a href="{{ route('items.show', $item) }}" class="block w-full h-full">
                                    <img 
                                        src="{{ $item->image_url }}" 
                                        alt="{{ $item->title }}"
                                        class="w-full h-full object-cover transition-transform duration-300 ease {{ $isActive ? 'group-hover:scale-105' : '' }} {{ $status === 'sold' ? 'grayscale' : '' }}"
                                    >
                                </a>
                                @if($status === 'sold')
                                    <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px] flex items-center justify-center z-10 pointer-events-none">
                                        <span class="text-white font-semibold border-2 border-white px-4 py-1 rounded-full text-sm">已售出</span>
                                    </div>
                                @elseif($status === 'pending')
                                    <div class="absolute inset-0 bg-blue-900/40 backdrop-blur-[1px] flex items-center justify-center z-10 pointer-events-none">
                                        <span class="text-white font-semibold text-sm px-4 py-1 rounded-full">交易中</span>
                                    </div>
                                @endif
                                
                                <!-- Heart Button (Top-Right) -->
                                @auth
                                    <button 
                                        type="button"
                                        onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist({{ $item->id }}, this);"
                                        class="absolute top-3 right-3 w-10 h-10 rounded-full bg-white/80 backdrop-blur-sm hover:bg-white shadow-sm flex items-center justify-center transition-all duration-200 active:scale-75 z-20 wishlist-heart-btn"
                                        data-item-id="{{ $item->id }}"
                                        data-liked="{{ auth()->check() && $item->isLikedBy(auth()->user()) ? 'true' : 'false' }}"
                                    >
                                        <svg 
                                            class="w-5 h-5 transition-all duration-200 heart-icon {{ auth()->check() && $item->isLikedBy(auth()->user()) ? 'text-red-500 fill-current' : 'text-gray-400 stroke-current fill-none' }}"
                                            viewBox="0 0 24 24" 
                                            fill="none" 
                                            stroke="currentColor" 
                                            stroke-width="2" 
                                            stroke-linecap="round" 
                                            stroke-linejoin="round"
                                        >
                                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                        </svg>
                                    </button>
                                @endauth
                            </div>
                            
                            <a href="{{ route('items.show', $item) }}" class="block">
                            
                                <!-- Content -->
                                <div class="p-5">
                                    <!-- Category Badge -->
                                    <span class="inline-block px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-full mb-3">
                                        {{ optional($item->category)->name ?? '未分类' }}
                                    </span>
                                    
                                    <!-- Title -->
                                    <h3 class="font-bold text-gray-900 text-lg mb-2 line-clamp-1 group-hover:text-blue-600 transition-colors">
                                        {{ $item->title }}
                                    </h3>
                                    
                                    <!-- Description (Optional - can be hidden for cleaner look) -->
                                    <p class="text-gray-500 text-sm mb-4 line-clamp-2">
                                        {{ \Illuminate\Support\Str::limit($item->description, 60) }}
                                    </p>
                                    
                                    <!-- Price -->
                                    <div class="mb-4">
                                        <span class="text-2xl font-bold text-[#FF2D55]">¥{{ number_format($item->price, 2) }}</span>
                                    </div>
                                    
                                    <!-- Footer: Seller & Location -->
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white text-xs font-semibold">
                                                {{ mb_substr($item->user->name, 0, 1) }}
                                            </div>
                                            <span class="text-xs text-gray-600 font-medium">{{ $item->user->name }}</span>
                                        </div>
                                        <span class="text-xs text-gray-400">
                                            {{ $item->deal_place ? '📍' : '📍' }}
                                        </span>
                                    </div>
                                    @if($item->deal_place)
                                        <p class="text-xs text-gray-400 mt-2 truncate">
                                            {{ $item->deal_place }}
                                        </p>
                                    @endif
                                </div>
                            </a>
                        </article>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <div class="inline-block p-6 bg-gray-100 rounded-2xl mb-4">
                    <svg class="w-16 h-16 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <p class="text-gray-500 text-lg">还没有任何商品，快来成为第一位发布者吧。</p>
            </div>
        @endif

        <!-- Pagination -->
        @if($items->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $items->links() }}
            </div>
        @endif
    </div>

    </div>

    <style>
        /* Hide scrollbar for category pills */
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        
        /* Line clamp utility */
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Heart Button Animations */
        .wishlist-heart-btn {
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .wishlist-heart-btn:hover {
            transform: scale(1.1);
        }
        .wishlist-heart-btn:active {
            transform: scale(0.75);
        }
        .wishlist-heart-btn.scale-110 {
            transform: scale(1.1);
        }
        
        /* Heart Icon Animation */
        .heart-icon {
            transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
    </style>

    <!-- Publish Product Modal -->
    <div 
        id="publishProductModal" 
        class="hidden fixed inset-0 z-[9999] items-center justify-center overflow-y-auto px-4 py-6" 
        role="dialog" 
        aria-modal="true"
    >
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closePublishModal()"></div>

        <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl p-8 transform transition-all m-auto" onclick="event.stopPropagation()">
            <button type="button" onclick="closePublishModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <h2 class="text-2xl font-bold text-gray-900 mb-6">发布闲置商品</h2>

            <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4">
                        <p class="font-semibold mb-2">还有信息需要完善：</p>
                        <ul class="list-disc pl-5 space-y-1 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

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

                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">地图定位</label>
                        <div id="map-picker" class="h-48 w-full rounded-2xl overflow-hidden z-0 border border-gray-200"></div>
                        <input type="hidden" name="lat" id="publish-lat" value="{{ old('lat') }}">
                        <input type="hidden" name="lng" id="publish-lng" value="{{ old('lng') }}">
                    </div>
                    <div>
                        <label for="deal_place" class="block text-sm font-semibold text-gray-700 mb-2">位置名称（可选）</label>
                        <input 
                            id="deal_place" 
                            name="deal_place" 
                            value="{{ old('deal_place') }}"
                            class="w-full bg-gray-50 border-0 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all"
                            placeholder="如 图书馆一楼 / 南门星巴克 / 宿舍楼下"
                        >
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closePublishModal()" class="px-6 py-2.5 rounded-full bg-gray-100 text-gray-700 font-bold hover:bg-gray-200 transition">取消</button>
                    <button type="submit" class="px-6 py-2.5 rounded-full bg-blue-600 text-white font-bold hover:bg-blue-700 transition shadow-lg">发布商品</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-8 left-1/2 transform -translate-x-1/2 z-50 pointer-events-none">
        <div id="toast" class="bg-black/80 text-white px-6 py-3 rounded-full shadow-lg opacity-0 translate-y-4 transition-all duration-300 ease-out pointer-events-auto">
            <span id="toast-message"></span>
        </div>
    </div>

    <script>
        (function() {
            const storageKey = 'marketScrollPosition';

            const saveScrollPosition = () => {
                sessionStorage.setItem(storageKey, window.scrollY.toString());
            };

            window.addEventListener('beforeunload', saveScrollPosition);

            document.addEventListener('click', function(event) {
                const target = event.target;
                if (!target) return;
                const card = target.closest('a');
                if (card && card.href) {
                    saveScrollPosition();
                }
            }, true);

            document.addEventListener('DOMContentLoaded', function () {
                const storedPosition = sessionStorage.getItem(storageKey);
                if (storedPosition !== null) {
                    setTimeout(() => {
                        window.scrollTo(0, parseFloat(storedPosition));
                    }, 120);
                }
            });
        })();

        let publishMapInstance = null;
        let publishMapMarker = null;

        function initializePublishMap() {
            if (publishMapInstance) {
                publishMapInstance.invalidateSize();
                return;
            }

            const mapElement = document.getElementById('map-picker');
            if (!mapElement || typeof L === 'undefined') {
                return;
            }

            const defaultLatLng = [39.9042, 116.4074];
            const initialLat = parseFloat(document.getElementById('publish-lat')?.value) || defaultLatLng[0];
            const initialLng = parseFloat(document.getElementById('publish-lng')?.value) || defaultLatLng[1];

            publishMapInstance = L.map(mapElement, {
                center: [initialLat, initialLng],
                zoom: 15,
                dragging: true,
                zoomControl: true
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(publishMapInstance);

            publishMapMarker = L.marker([initialLat, initialLng], {
                draggable: true
            }).addTo(publishMapInstance);

            publishMapMarker.on('dragend', updatePublishLatLng);
            publishMapInstance.on('click', function (event) {
                publishMapMarker.setLatLng(event.latlng);
                updatePublishLatLng({ target: publishMapMarker });
            });

            setTimeout(() => publishMapInstance.invalidateSize(), 150);
        }

        function updatePublishLatLng(event) {
            const latInput = document.getElementById('publish-lat');
            const lngInput = document.getElementById('publish-lng');
            if (!latInput || !lngInput) return;

            const latLng = event.target.getLatLng();
            latInput.value = latLng.lat.toFixed(8);
            lngInput.value = latLng.lng.toFixed(8);
        }

        function openPublishModal() {
            const modal = document.getElementById('publishProductModal');
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';

            setTimeout(() => {
                initializePublishMap();
                if (publishMapInstance) {
                    publishMapInstance.invalidateSize();
                }
            }, 200);
        }

        function closePublishModal() {
            const modal = document.getElementById('publishProductModal');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        function handleHeroImageSelect(event) {
            const file = event.target.files[0];
            if (!file) return;

            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                alert('请上传 JPG、PNG 或 WEBP 格式的图片');
                event.target.value = '';
                return;
            }

            const maxSize = 5 * 1024 * 1024;
            if (file.size > maxSize) {
                alert('图片大小不能超过 5MB');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const { preview, placeholder, removeBtn, dropzone } = getHeroElements();
                if (!preview || !placeholder || !removeBtn || !dropzone) return;
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('opacity-0');
                removeBtn.classList.remove('hidden');
                dropzone.classList.remove('border-dashed', 'border-gray-200');
                dropzone.classList.add('border-solid', 'border-blue-200');
            };
            reader.readAsDataURL(file);
        }

        function resetHeroImage(event) {
            event.preventDefault();
            event.stopPropagation();
            const { preview, placeholder, removeBtn, input, dropzone } = getHeroElements();
            if (!preview || !placeholder || !removeBtn || !input || !dropzone) return;
            preview.src = '';
            preview.classList.add('hidden');
            placeholder.classList.remove('opacity-0');
            removeBtn.classList.add('hidden');
            input.value = '';
            dropzone.classList.add('border-dashed', 'border-gray-200');
            dropzone.classList.remove('border-solid', 'border-blue-200');
        }

        function getHeroElements() {
            return {
                dropzone: document.getElementById('hero-dropzone'),
                preview: document.getElementById('hero-preview'),
                placeholder: document.getElementById('hero-dropzone-placeholder'),
                removeBtn: document.getElementById('hero-remove-btn'),
                input: document.getElementById('product-image-input')
            };
        }

        // Staggered Fade-in Entry Animation
        document.addEventListener('DOMContentLoaded', function() {
            const productCards = document.querySelectorAll('.product-card-entry');
            
            productCards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.remove('opacity-0', 'translate-y-8');
                    card.classList.add('opacity-100', 'translate-y-0');
                }, index * 100);
            });
        });

        /**
         * Toggle Wishlist with Optimistic UI Update
         * @param {number} productId - The item ID
         * @param {HTMLElement} btnElement - The button element that was clicked
         */
        function toggleWishlist(productId, btnElement) {
            const heartIcon = btnElement.querySelector('.heart-icon');
            const isLiked = btnElement.getAttribute('data-liked') === 'true';
            
            // 1. Optimistic Update - Immediately toggle the heart
            if (isLiked) {
                // Remove from wishlist (optimistic)
                heartIcon.classList.remove('text-red-500', 'fill-current');
                heartIcon.classList.add('text-gray-400', 'stroke-current', 'fill-none');
                btnElement.setAttribute('data-liked', 'false');
            } else {
                // Add to wishlist (optimistic)
                heartIcon.classList.remove('text-gray-400', 'stroke-current', 'fill-none');
                heartIcon.classList.add('text-red-500', 'fill-current');
                btnElement.setAttribute('data-liked', 'true');
            }
            
            // 2. Trigger Animation - Heart pump effect
            btnElement.classList.add('scale-110');
            setTimeout(() => {
                btnElement.classList.remove('scale-110');
            }, 200);
            
            // 3. Server Request
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            
            fetch(`/wishlist/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Show toast notification
                showToast(isLiked ? '已取消收藏' : '已添加到心愿单');
            })
            .catch(error => {
                console.error('Error:', error);
                // Revert optimistic update on error
                if (isLiked) {
                    heartIcon.classList.remove('text-gray-400', 'stroke-current', 'fill-none');
                    heartIcon.classList.add('text-red-500', 'fill-current');
                    btnElement.setAttribute('data-liked', 'true');
                } else {
                    heartIcon.classList.remove('text-red-500', 'fill-current');
                    heartIcon.classList.add('text-gray-400', 'stroke-current', 'fill-none');
                    btnElement.setAttribute('data-liked', 'false');
                }
                showToast('操作失败，请重试');
            });
        }

        /**
         * Show Toast Notification
         * @param {string} message - The message to display
         */
        function showToast(message) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            
            toastMessage.textContent = message;
            
            // Reset and show
            toast.classList.remove('opacity-0', 'translate-y-4');
            toast.classList.add('opacity-100', 'translate-y-0');
            
            // Hide after 2 seconds
            setTimeout(() => {
                toast.classList.remove('opacity-100', 'translate-y-0');
                toast.classList.add('opacity-0', 'translate-y-4');
            }, 2000);
        }

    </script>
@endsection
