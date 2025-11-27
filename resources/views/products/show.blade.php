@extends('layouts.app')

@section('title', $product->title . ' - 商品详情')

@section('content')
    @php
        $galleryImages = collect($product->gallery_urls ?? [$product->image_url]);
    @endphp

    <section class="surface">
        <a
            href="{{ url()->previous() }}"
            class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 transition"
        >
            ← 返回上一页
        </a>

        <div class="mt-6 flex flex-wrap gap-8">
            <div class="flex-1 min-w-[260px]">
                <div class="relative w-full aspect-[4/3] rounded-3xl overflow-hidden group shadow-lg bg-slate-100">
                    <div
                        id="product-gallery"
                        class="flex overflow-x-auto snap-x snap-mandatory no-scrollbar w-full h-full cursor-grab active:cursor-grabbing select-none"
                    >
                        @foreach($galleryImages as $index => $image)
                            <figure class="w-full h-full flex-shrink-0 snap-center" data-gallery-slide="{{ $index }}">
                                <img
                                    src="{{ $image }}"
                                    alt="{{ $product->title }} 图片 {{ $index + 1 }}"
                                    class="w-full h-full object-cover rounded-3xl pointer-events-none transition-transform duration-700 group-hover:scale-105"
                                    draggable="false"
                                    ondragstart="return false"
                                >
                            </figure>
                        @endforeach
                    </div>

                    @if($galleryImages->count() > 1)
                        <div class="pointer-events-none absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2">
                            @foreach($galleryImages as $index => $image)
                                <span class="gallery-dot {{ $index === 0 ? 'is-active' : '' }}" data-gallery-dot="{{ $index }}"></span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            @php
                $isOwner = auth()->check() && auth()->id() === $product->user_id;
                $isLiked = auth()->check() && $product->isLikedBy(auth()->user());
            @endphp

            <div class="flex-[1.5] min-w-[280px] space-y-5">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">{{ $product->title }}</h1>
                    <p class="text-2xl font-semibold text-rose-500 mb-3">¥{{ $product->price }}</p>
                    <p class="text-sm text-slate-500 mb-2">
                        分类：{{ optional($product->category)->name ?? '未分类' }}
                        ｜ 状态：{{ $product->status === 'on_sale' ? '在售中' : '已售出' }}
                    </p>
                    <p class="text-sm text-slate-500">
                        {{ $product->deal_place ?: '交易地点待与卖家协商，建议选择校园公共区域。' }}
                    </p>
                </div>

                <div class="rounded-3xl border border-slate-100 shadow-sm p-4 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-semibold text-lg">
                        {{ mb_substr($product->user->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-900 truncate">{{ $product->user->name }}</p>
                        <p class="text-xs text-slate-500 truncate">
                            {{ $product->user->email }}
                        </p>
                    </div>
                </div>

                <div class="text-slate-700 leading-7 whitespace-pre-line">
                    {{ $product->description }}
                </div>

                @if($product->latitude && $product->longitude)
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2 text-gray-900 font-semibold">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c-1.105 0-2-.9-2-2 0-1.105.895-2 2-2s2 .895 2 2-.895 2-2 2zm0-9C8.134 2 5 5.134 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.866-3.134-7-7-7z"/>
                                </svg>
                                交易地点
                            </div>
                            @if($product->deal_place)
                                <span class="text-sm text-gray-500">{{ $product->deal_place }}</span>
                            @endif
                        </div>
                        <div id="static-map" class="h-60 w-full rounded-2xl overflow-hidden"></div>
                    </div>
                @endif

                <div class="mt-8 flex items-center gap-4 pt-6 border-t border-gray-50">
                    @if(!$isOwner)
                        <button onclick="startChat({{ $product->user_id }}, {{ $product->id }}, 'product', this)"
                            class="flex-1 bg-blue-600 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 hover:-translate-y-0.5 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>
                            我想要 / 立即沟通
                        </button>
                    @else
                        <button type="button"
                            class="flex-1 bg-gray-100 text-gray-500 font-bold py-3.5 rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 11c-1.105 0-2-.9-2-2 0-1.105.895-2 2-2s2 .895 2 2-.895 2-2 2zm0-9C8.134 2 5 5.134 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.866-3.134-7-7-7z">
                                </path>
                            </svg>
                            这是您发布的商品
                        </button>
                    @endif

                    @auth
                        <button onclick="toggleProductWishlist({{ $product->id }}, this)"
                            class="px-8 py-3.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-all active:scale-95 flex items-center gap-2 {{ $isLiked ? 'text-red-500' : '' }}"
                            data-liked="{{ $isLiked ? 'true' : 'false' }}">
                            <svg class="w-6 h-6 {{ $isLiked ? 'fill-current text-red-500' : 'fill-none text-gray-500' }}"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                            <span>{{ $isLiked ? '已在心愿单' : '加入心愿单' }}</span>
                        </button>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-8 py-3.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-all active:scale-95 flex items-center gap-2">
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                            <span>登录后收藏</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .gallery-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(15, 23, 42, 0.2);
            opacity: 0.5;
        }
        .gallery-dot.is-active {
            width: 28px;
            opacity: 1;
            background: rgba(255, 255, 255, 0.95);
        }
    </style>
@endsection

@push('scripts')
    <script src="https://webapi.amap.com/loader.js"></script>
    <script>
        (function () {
            const gallery = document.getElementById('product-gallery');
            if (!gallery) return;

            const dots = Array.from(document.querySelectorAll('[data-gallery-dot]'));
            let isDown = false;
            let startX;
            let scrollLeft;
            let velX = 0;
            let momentumID;
            let autoPlayID;

            const enableSnap = () => gallery.style.scrollSnapType = 'x mandatory';
            const disableSnap = () => gallery.style.scrollSnapType = 'none';

            const startAutoPlay = () => {
                stopAutoPlay();
                autoPlayID = setInterval(() => {
                    const atEnd = gallery.scrollLeft + gallery.offsetWidth >= gallery.scrollWidth - 1;
                    if (atEnd) {
                        gallery.scrollTo({ left: 0, behavior: 'smooth' });
                    } else {
                        gallery.scrollBy({ left: gallery.offsetWidth, behavior: 'smooth' });
                    }
                }, 3500);
            };

            const stopAutoPlay = () => {
                if (autoPlayID) {
                    clearInterval(autoPlayID);
                    autoPlayID = null;
                }
            };

            const updateDots = () => {
                const activeIndex = Math.round(gallery.scrollLeft / gallery.offsetWidth);
                dots.forEach((dot, index) => {
                    dot.classList.toggle('is-active', index === activeIndex);
                });
            };

            const beginMomentum = () => {
                cancelAnimationFrame(momentumID);

                const momentumLoop = () => {
                    if (Math.abs(velX) < 0.5) {
                        velX = 0;
                        enableSnap();
                        return;
                    }

                    gallery.scrollLeft += velX;
                    velX *= 0.95;
                    momentumID = requestAnimationFrame(momentumLoop);
                };

                momentumLoop();
            };

            gallery.addEventListener('mousedown', (event) => {
                isDown = true;
                stopAutoPlay();
                cancelAnimationFrame(momentumID);
                disableSnap();
                gallery.classList.add('cursor-grabbing');
                startX = event.pageX - gallery.offsetLeft;
                scrollLeft = gallery.scrollLeft;
            });

            gallery.addEventListener('mouseleave', () => {
                if (!isDown) return;
                isDown = false;
                gallery.classList.remove('cursor-grabbing');
                beginMomentum();
                startAutoPlay();
            });

            gallery.addEventListener('mouseup', () => {
                if (!isDown) return;
                isDown = false;
                gallery.classList.remove('cursor-grabbing');
                beginMomentum();
                startAutoPlay();
            });

            gallery.addEventListener('mousemove', (event) => {
                if (!isDown) return;
                event.preventDefault();
                const x = event.pageX - gallery.offsetLeft;
                const walk = (x - startX);
                const prevScroll = gallery.scrollLeft;
                gallery.scrollLeft = scrollLeft - walk;
                velX = gallery.scrollLeft - prevScroll;
            });

            gallery.addEventListener('scroll', () => requestAnimationFrame(updateDots));
            window.addEventListener('resize', updateDots);

            enableSnap();
            updateDots();
            startAutoPlay();
        })();

        @if($product->latitude && $product->longitude)
            window._AMapSecurityConfig = {
                securityJsCode: '53cd5c8cddb263d94888f1f61fe08201'
            };

            AMapLoader.load({
                key: '17874d29165d98aaefcd72ca015bb493',
                version: '2.0',
            }).then((AMap) => {
                const map = new AMap.Map('static-map', {
                    viewMode: '2D',
                    zoom: 15,
                    center: [{{ $product->longitude }}, {{ $product->latitude }}],
                });

                const marker = new AMap.Marker({
                    position: [{{ $product->longitude }}, {{ $product->latitude }}],
                    icon: 'https://webapi.amap.com/theme/v1.3/markers/n/mark_b.png',
                });

                map.add(marker);
            }).catch((error) => {
                console.error('Failed to load AMap:', error);
            });
        @endif

        function toggleProductWishlist(productId, button) {
            if (!button) return;
            const heartIcon = button.querySelector('svg');
            const label = button.querySelector('span');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const isLiked = button.getAttribute('data-liked') === 'true';

            const setState = (liked) => {
                button.setAttribute('data-liked', liked ? 'true' : 'false');
                if (liked) {
                    button.classList.add('text-red-500');
                    heartIcon.classList.remove('text-gray-500', 'fill-none');
                    heartIcon.classList.add('text-red-500', 'fill-current');
                    label.textContent = '已在心愿单';
                } else {
                    button.classList.remove('text-red-500');
                    heartIcon.classList.remove('text-red-500', 'fill-current');
                    heartIcon.classList.add('text-gray-500', 'fill-none');
                    label.textContent = '加入心愿单';
                }
            };

            setState(!isLiked);

            fetch(`/wishlist/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Request failed');
                }
            })
            .catch(() => setState(isLiked));
        }

        async function startChat(receiverId, itemId, type, buttonElement) {
            if (!receiverId || !itemId || !type) {
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const originalText = buttonElement.textContent;

            buttonElement.disabled = true;
            buttonElement.classList.add('opacity-70', 'cursor-wait');
            buttonElement.textContent = '正在连接...';

            try {
                const payload = { receiver_id: receiverId };
                if (type === 'product') {
                    payload.product_id = itemId;
                } else if (type === 'task') {
                    payload.task_id = itemId;
                }

                const response = await fetch('/conversations/start', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (!response.ok || data.status !== 'success' || !data.redirect_url) {
                    throw new Error(data.message || '无法连接到卖家');
                }

                window.location.href = data.redirect_url;
            } catch (error) {
                console.error(error);
                buttonElement.disabled = false;
                buttonElement.classList.remove('opacity-70', 'cursor-wait');
                buttonElement.textContent = originalText;
                alert('连接失败，请稍后重试');
            }
        }
    </script>
@endpush

