@extends('layouts.app')

@section('title', '管理我的发布 · 校园易')

@section('content')
<div class="bg-[#F9FAFB] min-h-screen">
    <!-- Header Section -->
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">管理我的发布</h1>
                    <p class="text-gray-500 text-base">管理您在校园出售的闲置物品</p>
                </div>
                <a 
                    href="{{ route('items.index') }}#publish-form"
                    class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-full transition-all duration-200 ease-in-out hover:bg-blue-700 active:scale-95 shadow-md hover:shadow-lg whitespace-nowrap"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    发布新商品
                </a>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        @if(isset($products) && $products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8">
                @foreach($products as $index => $product)
                    <div 
                        class="product-card-entry opacity-0 translate-y-8 transition-all duration-700 ease-out transform"
                        style="animation-delay: {{ $index * 50 }}ms;"
                    >
                        <article class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm transition-all duration-300 ease hover:shadow-lg hover:-translate-y-1 relative group">
                            <!-- Image Area -->
                            <div class="aspect-square bg-gray-100 overflow-hidden relative">
                                <a href="{{ route('items.show', $product) }}" class="block w-full h-full">
                                    <img 
                                        src="{{ $product->image_url }}" 
                                        alt="{{ $product->title }}"
                                        class="w-full h-full object-cover transition-transform duration-300 ease group-hover:scale-105"
                                    >
                                </a>
                                
                                <!-- Status Badge (Top-Left) -->
                                <div class="absolute top-3 left-3 z-10">
                                    @if($product->status === 'sold' || $product->status === '已售出' || $product->status === 'sold_out')
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-gray-500 text-white shadow-sm">
                                            已售出
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-green-500 text-white shadow-sm">
                                            在售
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Info Area -->
                            <div class="p-4">
                                <h3 class="text-base font-semibold text-gray-900 mb-2 line-clamp-2 min-h-[2.5rem]">
                                    <a href="{{ route('items.show', $product) }}" class="hover:text-blue-600 transition-colors">
                                        {{ $product->title }}
                                    </a>
                                </h3>
                                <p class="text-xl font-bold text-red-500 mb-0">
                                    ¥{{ number_format($product->price, 2) }}
                                </p>
                            </div>
                            
                            <!-- Management Actions (Bottom Border) -->
                            <div class="border-t border-gray-100 flex divide-x divide-gray-100">
                                <!-- Edit Button -->
                                <a 
                                    href="{{ route('items.edit', $product) }}"
                                    class="flex-1 flex items-center justify-center gap-2 py-3 px-4 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors duration-200"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    编辑
                                </a>
                                
                                <!-- Delete Button -->
                                <button 
                                    type="button"
                                    onclick="handleDelete({{ $product->id }}, '{{ $product->title }}', this)"
                                    class="flex-1 flex items-center justify-center gap-2 py-3 px-4 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors duration-200"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    删除
                                </button>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-20">
                <div class="inline-block p-12 bg-white rounded-3xl border border-gray-100 shadow-sm mb-6">
                    <div class="text-8xl mb-6">📦</div>
                    <p class="text-gray-500 text-xl font-medium mb-2">还没有发布任何商品</p>
                    <p class="text-gray-400 text-sm mb-8">发布第一件商品，让闲置物品找到新主人</p>
                    <a 
                        href="{{ route('items.index') }}#publish-form"
                        class="inline-block px-8 py-3.5 bg-blue-600 text-white rounded-full font-medium transition-all duration-200 ease-in-out hover:bg-blue-700 active:scale-95 shadow-md hover:shadow-lg"
                    >
                        发布第一件商品
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Delete Form (Hidden) -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    /**
     * Handle Delete Action with Confirmation
     * @param {number} productId - The product ID to delete
     * @param {string} productTitle - The product title for confirmation message
     * @param {HTMLElement} buttonElement - The button element that was clicked
     */
    function handleDelete(productId, productTitle, buttonElement) {
        // Show confirmation dialog
        if (!confirm(`确定要删除商品"${productTitle}"吗？\n\n此操作无法撤销。`)) {
            return;
        }

        // Disable button and show loading state
        const originalContent = buttonElement.innerHTML;
        buttonElement.disabled = true;
        buttonElement.innerHTML = `
            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            删除中...
        `;

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Create delete form
        const form = document.getElementById('delete-form');
        form.action = `/items/${productId}`;
        
        // Submit form
        fetch(form.action, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('删除失败');
        })
        .then(data => {
            // Smooth fade out animation
            const card = buttonElement.closest('.product-card-entry');
            card.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
            card.style.opacity = '0';
            card.style.transform = 'translateY(-20px) scale(0.95)';
            
            setTimeout(() => {
                card.remove();
                
                // Check if grid is empty, show empty state
                const grid = document.querySelector('.grid');
                if (grid && grid.children.length === 0) {
                    location.reload();
                }
            }, 300);
        })
        .catch(error => {
            console.error('Error deleting product:', error);
            alert('删除失败，请重试');
            
            // Reset button state
            buttonElement.disabled = false;
            buttonElement.innerHTML = originalContent;
        });
    }

    // Staggered fade-in animation on page load
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.product-card-entry');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 50);
        });
    });
</script>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
