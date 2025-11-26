@extends('layouts.app')

@section('title', '互助任务 · 校园易')

<script type="text/javascript">
    // 1. Security Config (MUST be before loading the map)
    window._AMapSecurityConfig = {
        securityJsCode: '53cd5c8cddb263d94888f1f61fe08201'
    };
</script>
<script src="https://webapi.amap.com/maps?v=2.0&key=17874d29165d98aaefcd72ca015bb493&plugin=AMap.AutoComplete,AMap.PlaceSearch,AMap.Geocoder"></script>

@section('content')
<div class="bg-[#F9FAFB] min-h-screen">
    <!-- Hero Section with Search & Action -->
    <div class="bg-gradient-to-br from-gray-50 via-white to-gray-50 min-h-[400px] flex flex-col items-center justify-center px-4 py-16">
        <div class="w-full max-w-4xl">
            <!-- Page Title -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-3">互助广场</h1>
                <p class="text-gray-500 text-lg">寻找志同道合的同学一起解决问题</p>
            </div>

            <!-- Unified Search + Action Row -->
            <div class="flex items-center gap-3 w-full max-w-4xl mx-auto mb-8 flex-col sm:flex-row">
                <form method="GET" class="flex-1 relative w-full">
                    <input 
                        type="text" 
                        id="q" 
                        name="q" 
                        value="{{ $filters['q'] }}" 
                        placeholder="搜索任务，如 代取快递、学习辅导..." 
                        class="flex-1 h-12 bg-white rounded-full shadow-sm border-0 focus:ring-2 focus:ring-blue-500 px-6 text-gray-700 w-full placeholder-gray-400"
                    >
                    <button 
                        type="submit" 
                        class="absolute right-2 top-1/2 -translate-y-1/2 h-10 w-10 bg-blue-600 rounded-full flex items-center justify-center text-white shadow-md"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </form>

                <div class="flex items-center gap-3 shrink-0 w-full sm:w-auto justify-center sm:justify-end">
                    <form method="GET" class="hidden sm:block relative">
                        <input type="hidden" name="q" value="{{ $filters['q'] }}">
                        <div class="h-12 px-6 bg-white rounded-full shadow-sm text-gray-700 font-bold flex items-center justify-center hover:bg-gray-50 cursor-pointer border-0">
                            <span>
                                {{ $filters['status'] === 'open' ? '招募中' : ($filters['status'] === 'completed' ? '已完成' : '全部状态') }}
                            </span>
                        </div>
                        <select 
                            id="status" 
                            name="status" 
                            onchange="this.form.submit()"
                            class="absolute inset-0 opacity-0 cursor-pointer"
                        >
                            <option value="">全部状态</option>
                            <option value="open" @selected($filters['status'] === 'open')>招募中</option>
                            <option value="completed" @selected($filters['status'] === 'completed')>已完成</option>
                        </select>
                    </form>

                    <button 
                        onclick="openPublishModal()"
                        class="h-12 px-6 bg-blue-600 text-white rounded-full shadow-lg font-bold flex items-center justify-center gap-2 hover:bg-blue-700 hover:-translate-y-0.5 transition-all"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        发布求助
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="max-w-6xl mx-auto px-4 py-4">
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 shadow-sm">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Task Cards Grid -->
    <div class="max-w-6xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($tasks as $task)
                @if($task->status === 'completed')
                    <div 
                        class="task-card-entry opacity-0 translate-y-8 transition-all duration-700 ease-out transform"
                    >
                        <article class="bg-gray-50 opacity-80 rounded-2xl p-6 border border-gray-100 shadow-sm transition-all duration-300 ease-in-out h-full flex flex-col relative">
                            <!-- Completed Stamp Watermark -->
                            <div class="absolute right-4 top-16 text-6xl font-black text-gray-200 -rotate-12 select-none z-0 pointer-events-none">
                                已完成
                            </div>
                            
                            <!-- Top: User Avatar + Name + Time -->
                            <div class="flex items-center gap-3 mb-4 relative z-10">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                                    {{ mb_substr($task->user->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 text-sm truncate">{{ $task->user->name }}</p>
                                    <p class="text-gray-400 text-xs">{{ $task->created_at->diffForHumans() }}</p>
                                </div>
                                <!-- Status Indicator -->
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                    <span class="text-xs text-gray-400 font-medium">已完成</span>
                                </div>
                            </div>

                            <!-- Middle: Task Title + Description -->
                            <div class="flex-1 mb-4 relative z-10">
                                <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">
                                    {{ $task->title }}
                                </h3>
                                <p class="text-gray-600 text-sm line-clamp-3 leading-relaxed">
                                    {{ $task->content }}
                                </p>
                            </div>

                            <!-- Bottom: Reward Tag + Action Button -->
                            <div class="flex items-center justify-between gap-3 pt-4 border-t border-gray-100 relative z-10">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                        🎁 {{ $task->reward }}
                                    </span>
                                </div>
                                <button 
                                    class="px-4 py-2 bg-gray-200 text-gray-400 rounded-full text-sm font-medium cursor-not-allowed"
                                    disabled
                                >
                                    任务结束
                                </button>
                            </div>
                        </article>
                    </div>
                @else
                    <a 
                        href="{{ route('tasks.show', $task) }}" 
                        class="group block task-card-entry opacity-0 translate-y-8 transition-all duration-700 ease-out transform"
                    >
                        <article class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-xl hover:border-blue-100 h-full flex flex-col">
                            <!-- Top: User Avatar + Name + Time -->
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                                    {{ mb_substr($task->user->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 text-sm truncate">{{ $task->user->name }}</p>
                                    <p class="text-gray-400 text-xs">{{ $task->created_at->diffForHumans() }}</p>
                                </div>
                                <!-- Status Indicator -->
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                    <span class="text-xs text-gray-500 font-medium">招募中</span>
                                </div>
                            </div>

                            <!-- Middle: Task Title + Description -->
                            <div class="flex-1 mb-4">
                                <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors duration-200">
                                    {{ $task->title }}
                                </h3>
                                <p class="text-gray-600 text-sm line-clamp-3 leading-relaxed">
                                    {{ $task->content }}
                                </p>
                            </div>

                            <!-- Bottom: Reward Tag + Action Button -->
                            <div class="flex items-center justify-between gap-3 pt-4 border-t border-gray-100">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                        🎁 {{ $task->reward }}
                                    </span>
                                </div>
                                <button 
                                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium transition-all duration-200 ease-in-out group-hover:bg-blue-600 group-hover:text-white opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0"
                                    onclick="event.preventDefault(); window.location.href='{{ route('tasks.show', $task) }}'"
                                >
                                    我来帮
                                </button>
                            </div>
                        </article>
                    </a>
                @endif
            @empty
                <div class="col-span-full text-center py-16">
                    <div class="inline-block p-8 bg-white rounded-2xl border border-gray-100 shadow-sm">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500 text-lg mb-2">暂无互助任务</p>
                        <p class="text-gray-400 text-sm mb-6">快来发布第一条求助吧</p>
                        <button 
                            onclick="openPublishModal()"
                            class="px-6 py-3 bg-blue-600 text-white rounded-full font-medium transition-all duration-200 ease-in-out hover:bg-blue-700 active:scale-95 shadow-md hover:shadow-lg"
                        >
                            发布求助
                        </button>
                    </div>
                </div>
            @endforelse
        </div>

        @if($tasks->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $tasks->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<!-- Publish Task Modal -->
<div 
    id="publishModal" 
    class="hidden fixed inset-0 z-[9999] items-center justify-center overflow-y-auto px-4 py-6" 
    role="dialog" 
    aria-modal="true"
>
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closePublishModal()"></div>

    <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl p-8 transform transition-all m-auto" onclick="event.stopPropagation()">
        <button type="button" onclick="closePublishModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 p-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <h2 class="text-2xl font-bold text-gray-900 mb-6">发布互助任务</h2>
        
        <form id="task-publish-form" action="{{ route('tasks.store') }}" method="POST" class="space-y-6">
            @csrf

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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="publisher_name" class="block text-sm font-medium text-gray-700 mb-2">联系人昵称</label>
                    <input 
                        id="publisher_name" 
                        name="publisher_name" 
                        value="{{ old('publisher_name') }}" 
                        required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        placeholder="请输入您的昵称"
                    >
                </div>
                <div>
                    <label for="publisher_email" class="block text-sm font-medium text-gray-700 mb-2">校园邮箱</label>
                    <input 
                        type="email" 
                        id="publisher_email" 
                        name="publisher_email" 
                        value="{{ old('publisher_email') }}" 
                        required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        placeholder="example@campus.edu"
                    >
                </div>
            </div>

            <div>
                <label for="reward" class="block text-sm font-medium text-gray-700 mb-2">奖励（可选）</label>
                <input 
                    id="reward" 
                    name="reward" 
                    placeholder="如 10 元奶茶/校园币" 
                    value="{{ old('reward') }}"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                >
            </div>

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">标题</label>
                <input 
                    id="title" 
                    name="title" 
                    value="{{ old('title') }}" 
                    required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                    placeholder="例如：需要代取快递"
                >
            </div>

            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">任务详情</label>
                <textarea 
                    id="content" 
                    name="content" 
                    required 
                    rows="5"
                    placeholder="任务背景、时间地点、注意事项等"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"
                >{{ old('content') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">任务地点</label>
                <div class="flex gap-2">
                    <input 
                        id="location-input"
                        type="text"
                        placeholder="输入地点 (如: 兰州大学 图书馆)"
                        class="flex-1 px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                    >
                    <button 
                        type="button"
                        id="location-search-btn"
                        class="px-5 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition"
                    >
                        搜索
                    </button>
                </div>
                <div id="task-map" class="h-72 w-full rounded-2xl mt-2 overflow-hidden border border-gray-200"></div>
                <input type="hidden" name="lat" id="task-lat" value="{{ old('lat') }}">
                <input type="hidden" name="lng" id="task-lng" value="{{ old('lng') }}">
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="closePublishModal()" class="px-6 py-2.5 rounded-full bg-gray-100 text-gray-700 font-bold hover:bg-gray-200 transition">取消</button>
                <button type="submit" id="task-submit-btn" class="px-6 py-2.5 rounded-full bg-blue-600 text-white font-bold hover:bg-blue-700 transition shadow-lg">发布任务</button>
            </div>
        </form>
    </div>
</div>

<script>
    let taskMapInstance = null;
    let currentMarker = null;
    let taskAutoComplete = null;
    let taskPlaceSearch = null;
    let taskGeocoder = null;

    function initializeTaskMap() {
        const mapElement = document.getElementById('task-map');
        if (!mapElement || typeof AMap === 'undefined') {
            console.warn('AMap SDK 未加载，无法初始化地图');
            return;
        }

        const latInput = document.getElementById('task-lat');
        const lngInput = document.getElementById('task-lng');
        const defaultLat = parseFloat(latInput?.value) || 36.061089;
        const defaultLng = parseFloat(lngInput?.value) || 103.834304;
        const initialCenter = [defaultLng, defaultLat];

        if (!taskMapInstance) {
            taskMapInstance = new AMap.Map(mapElement, {
                zoom: 17,
                center: initialCenter,
                viewMode: '3D',
                resizeEnable: true
            });

            currentMarker = new AMap.Marker({
                position: initialCenter,
                draggable: true,
                cursor: 'move'
            });
            currentMarker.setMap(taskMapInstance);

            currentMarker.on('dragend', function (event) {
                updateTaskLatLng(event.lnglat, true);
            });

            taskMapInstance.on('click', function (event) {
                currentMarker.setPosition(event.lnglat);
                updateTaskLatLng(event.lnglat, true);
            });

            taskAutoComplete = new AMap.AutoComplete({
                input: 'location-input',
                city: '全国',
                citylimit: false
            });

            taskGeocoder = new AMap.Geocoder({
                city: '全国'
            });

            taskPlaceSearch = new AMap.PlaceSearch({
                city: '全国',
                citylimit: false
            });

            bindTaskSearchEvents();
        } else {
            taskMapInstance.setZoomAndCenter(17, initialCenter);
            currentMarker?.setPosition(initialCenter);
            taskMapInstance.resize();
            updateTaskLatLng({ lng: initialCenter[0], lat: initialCenter[1] }, false);
        }
    }

    function bindTaskSearchEvents() {
        const searchButton = document.getElementById('location-search-btn');
        const locationInput = document.getElementById('location-input');
        if (!searchButton || !locationInput || searchButton.dataset.bound === 'true') {
            return;
        }

        const runSearch = () => {
            const keyword = locationInput.value.trim();
            if (!keyword) {
                alert('请输入要搜索的地点');
                return;
            }
            if (!taskPlaceSearch) {
                alert('地图初始化中，请稍后重试');
                return;
            }

            searchButton.disabled = true;
            const originalText = searchButton.textContent;
            searchButton.textContent = '搜索中...';

            taskPlaceSearch.search(keyword, function (status, result) {
                searchButton.disabled = false;
                searchButton.textContent = originalText;

                if (status === 'complete' && result.poiList && result.poiList.pois.length) {
                    const poi = result.poiList.pois[0];
                    if (poi.location) {
                        focusOnLngLat(poi.location);
                    } else if (taskGeocoder) {
                        taskGeocoder.getLocation(poi.address || keyword, function (geoStatus, geoResult) {
                            if (geoStatus === 'complete' && geoResult.geocodes.length) {
                                focusOnLngLat(geoResult.geocodes[0].location);
                            } else {
                                alert('未找到匹配的位置，请尝试更精确的描述');
                            }
                        });
                    }
                } else {
                    alert('未找到匹配的位置，请尝试更精确的描述');
                }
            });
        };

        searchButton.addEventListener('click', runSearch);
        locationInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                runSearch();
            }
        });

        if (taskAutoComplete) {
            taskAutoComplete.on('select', function (event) {
                if (event.poi && event.poi.location) {
                    focusOnLngLat(event.poi.location);
                } else if (event.poi && event.poi.name) {
                    locationInput.value = event.poi.name;
                    runSearch();
                }
            });
        }

        searchButton.dataset.bound = 'true';
    }

    function focusOnLngLat(lnglat) {
        if (!lnglat || !taskMapInstance || !currentMarker) return;
        currentMarker.setPosition(lnglat);
        taskMapInstance.setZoomAndCenter(18, lnglat);
        updateTaskLatLng(lnglat, true);
    }

    function updateTaskLatLng(lnglat, shouldReverse = false) {
        const latInput = document.getElementById('task-lat');
        const lngInput = document.getElementById('task-lng');
        const locationInput = document.getElementById('location-input');
        if (!latInput || !lngInput || !lnglat) return;

        const latValue = typeof lnglat.getLat === 'function' ? lnglat.getLat() : lnglat.lat;
        const lngValue = typeof lnglat.getLng === 'function' ? lnglat.getLng() : lnglat.lng;

        latInput.value = Number(latValue || 0).toFixed(8);
        lngInput.value = Number(lngValue || 0).toFixed(8);

        if (shouldReverse && taskGeocoder) {
            taskGeocoder.getAddress([lngValue, latValue], function(status, result) {
                if (status === 'complete' && result.regeocode && locationInput) {
                    locationInput.value = result.regeocode.formattedAddress;
                }
            });
        }
    }

    function openPublishModal() {
        const modal = document.getElementById('publishModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        setTimeout(() => {
            initializeTaskMap();
            if (taskMapInstance) {
                taskMapInstance.resize();
            }
        }, 200);
    }

    function closePublishModal() {
        const modal = document.getElementById('publishModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        const taskCards = document.querySelectorAll('.task-card-entry');
        
        taskCards.forEach((card, index) => {
            setTimeout(() => {
                card.classList.remove('opacity-0', 'translate-y-8');
                card.classList.add('opacity-100', 'translate-y-0');
            }, index * 100);
        });

        // AJAX form submission with fullscreen overlay
        const taskPublishForm = document.getElementById('task-publish-form');
        const taskSubmitBtn = document.getElementById('task-submit-btn');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const loadingState = document.getElementById('loadingState');
        const successState = document.getElementById('successState');
        
        if (taskPublishForm && taskSubmitBtn && loadingOverlay) {
            taskPublishForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                
                // Immediately disable the button
                taskSubmitBtn.disabled = true;
                taskSubmitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                
                // Show fullscreen loading overlay
                showLoadingOverlay();
                
                // Prepare form data
                const formData = new FormData(taskPublishForm);
                
                // Submit via AJAX
                fetch(taskPublishForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData,
                })
                .then(async (response) => {
                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('Task publish failed:', errorText);
                        throw new Error(errorText || '发布失败');
                    }
                    return response.json();
                })
                .then((data) => {
                    // Show success animation
                    showSuccessAnimation();
                    
                    // Redirect after 1.5 seconds
                    setTimeout(() => {
                        window.location.href = data.redirect || '{{ route("tasks.index") }}';
                    }, 1500);
                })
                .catch((error) => {
                    // Hide overlay on error
                    hideLoadingOverlay();
                    
                    // Re-enable button
                    taskSubmitBtn.disabled = false;
                    taskSubmitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                    taskSubmitBtn.innerHTML = '发布任务';
                    
                    // Show error message
                    let errorMessage = '发布失败，请稍后重试';
                    if (error.errors) {
                        const firstError = Object.values(error.errors)[0];
                        errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
                    } else if (error.message) {
                        errorMessage = error.message;
                    }
                    
                    alert(errorMessage);
                });
            });
        }
        
        function showLoadingOverlay() {
            if (loadingOverlay) {
                loadingOverlay.classList.remove('hidden');
                loadingOverlay.classList.add('flex');
                loadingState.classList.remove('hidden');
                successState.classList.add('hidden');
            }
        }
        
        function showSuccessAnimation() {
            if (loadingOverlay && loadingState && successState) {
                loadingState.classList.add('hidden');
                successState.classList.remove('hidden');
                successState.classList.remove('scale-90');
                successState.classList.add('scale-100');
            }
        }
        
        function hideLoadingOverlay() {
            if (loadingOverlay) {
                loadingOverlay.classList.add('hidden');
                loadingOverlay.classList.remove('flex');
            }
        }
    });
</script>

<!-- Fullscreen Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 z-[10000] bg-white/80 backdrop-blur-md hidden flex-col items-center justify-center transition-opacity duration-300">
    <div id="loadingState" class="text-center">
        <svg class="animate-spin h-16 w-16 text-blue-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <h3 class="text-xl font-bold text-gray-800">正在发布...</h3>
    </div>

    <div id="successState" class="hidden text-center transform scale-90 transition-all duration-500">
        <div class="h-20 w-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg shadow-green-200">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-800">发布成功!</h3>
    </div>
</div>
@endsection
