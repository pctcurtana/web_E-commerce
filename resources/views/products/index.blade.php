@extends('layouts.app')

@section('title', 'Sản phẩm')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            @if(isset($category))
                {{ $category->name }}
            @else
                Tất cả sản phẩm
            @endif
        </h1>
        <p class="text-gray-600 mt-2">
            @if(isset($category))
                {{ $category->description }}
            @else
                Khám phá hàng ngàn sản phẩm chất lượng cao
            @endif
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Filters -->
        <aside class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6 sticky top-20">
                <form id="filter-form" method="GET" action="{{ route('products.index') }}">
                    <!-- Preserve search query -->
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    
                    <!-- Categories Filter -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Danh mục</h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('products.index') }}" 
                                   class="block text-sm {{ !request('category') ? 'text-red-600 font-medium' : 'text-gray-600 hover:text-red-600' }}">
                                    Tất cả sản phẩm
                                </a>
                            </li>
                            @foreach($categories as $cat)
                                <li>
                                    <a href="{{ route('category.show', $cat->slug) }}" 
                                       class="block text-sm {{ isset($category) && $category->id == $cat->id ? 'text-red-600 font-medium' : 'text-gray-600 hover:text-red-600' }}">
                                        {{ $cat->name }}
                                        <span class="text-gray-400">({{ $cat->products_count }})</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Price Filter -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Khoảng giá</h3>
                            @if(request('price_range'))
                                <button type="button" onclick="clearPriceFilters()" class="text-xs text-red-600 hover:text-red-700">
                                    Xóa bộ lọc
                                </button>
                            @endif
                        </div>
                        <div class="space-y-2">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="price_range[]" value="under_500k" 
                                       {{ in_array('under_500k', request('price_range', [])) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-red-600 focus:ring-red-500 filter-checkbox">
                                <span class="ml-2 text-sm text-gray-600">Dưới 500.000đ</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="price_range[]" value="500k_1m" 
                                       {{ in_array('500k_1m', request('price_range', [])) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-red-600 focus:ring-red-500 filter-checkbox">
                                <span class="ml-2 text-sm text-gray-600">500.000đ - 1.000.000đ</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="price_range[]" value="1m_5m" 
                                       {{ in_array('1m_5m', request('price_range', [])) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-red-600 focus:ring-red-500 filter-checkbox">
                                <span class="ml-2 text-sm text-gray-600">1.000.000đ - 5.000.000đ</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="price_range[]" value="above_5m" 
                                       {{ in_array('above_5m', request('price_range', [])) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-red-600 focus:ring-red-500 filter-checkbox">
                                <span class="ml-2 text-sm text-gray-600">Trên 5.000.000đ</span>
                            </label>
                        </div>
                    </div>

                    <!-- Rating Filter -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Đánh giá</h3>
                            @if(request('rating'))
                                <button type="button" onclick="clearRatingFilters()" class="text-xs text-red-600 hover:text-red-700">
                                    Xóa bộ lọc
                                </button>
                            @endif
                        </div>
                        <div class="space-y-2">
                            @for($i = 5; $i >= 1; $i--)
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="rating[]" value="{{ $i }}" 
                                           {{ in_array((string)$i, request('rating', [])) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-red-600 focus:ring-red-500 filter-checkbox">
                                    <div class="ml-2 flex items-center">
                                        @for($j = 1; $j <= 5; $j++)
                                            <x-heroicon-s-star class="w-4 h-4 {{ $j <= $i ? 'text-yellow-400' : 'text-gray-300' }}" />
                                        @endfor
                                        <span class="ml-1 text-sm text-gray-600">{{ $i }} sao</span>
                                    </div>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <!-- Clear All Filters -->
                    @if(request('price_range') || request('rating'))
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <a href="{{ route('products.index') }}{{ request('search') ? '?search=' . request('search') : '' }}" 
                               class="block w-full text-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Xóa tất cả bộ lọc
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="lg:col-span-3">
            <!-- Sort and View Options -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">Sắp xếp theo:</span>
                        <select id="sort-select" name="sort" class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Phổ biến nhất</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Đánh giá cao</option>
                            <option value="most_sold" {{ request('sort') == 'most_sold' ? 'selected' : '' }}>Bán chạy</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Giá thấp đến cao</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Giá cao đến thấp</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                        </select>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            Hiển thị {{ $products->firstItem() }}-{{ $products->lastItem() }} trong {{ $products->total() }} sản phẩm
                        </div>
                        @if(request('price_range') || request('rating'))
                            <div class="ml-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <x-heroicon-o-funnel class="w-3 h-3 mr-1" />
                                    Đã lọc
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow group">
                            <a href="{{ route('products.show', $product->slug) }}">
                                <div class="relative overflow-hidden">
                                    <img src="{{ $product->featured_image_url ?: asset('images/products/default-product.jpg') }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                
                                <!-- Sale Badge -->
                                @if($product->discount_percentage > 0)
                                    <div class="absolute top-2 left-2 bg-red-600 text-white px-2 py-1 rounded-full text-xs font-bold">
                                        -{{ $product->discount_percentage }}%
                                    </div>
                                @endif
                                
                                <div class="absolute top-2 right-2">
                                    <!-- <button class="bg-white rounded-full p-2 shadow-md hover:bg-gray-50">
                                        <x-heroicon-o-heart class="w-4 h-4 text-gray-600" />
                                    </button> -->
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-sm text-gray-500 mb-1">{{ $product->category->name }}</p>
                                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-red-600">{{ $product->name }}</h3>
                                
                                <!-- Price Display -->
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <span class="text-red-600 font-bold text-lg">{{ number_format($product->final_price) }}đ</span>
                                        @if($product->sale_price)
                                            <span class="text-gray-400 line-through text-sm ml-2">{{ number_format($product->price) }}đ</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Ratings and Sales -->
                                <div class="flex items-center justify-between mb-3">
                                    <x-star-rating 
                                        :rating="$product->average_rating" 
                                        :review-count="$product->review_count" 
                                        size="sm" 
                                    />
                                    <span class="text-sm text-gray-500">
                                        Đã bán {{ number_format($product->sold_count) }}
                                    </span>
                                </div>
                            </div>
                        </a>
                        <div class="px-4 pb-4">
                            <form action="{{ route('cart.add') }}" method="POST" onsubmit="event.preventDefault(); addToCartWithNotification(this);">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition-colors font-medium">
                                    <x-heroicon-o-shopping-cart class="w-4 h-4 inline-block mr-2" />
                                    Thêm vào giỏ
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <x-heroicon-o-cube class="w-16 h-16 mx-auto text-gray-400 mb-4" />
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Không tìm thấy sản phẩm</h3>
                    <p class="text-gray-500">Hãy thử tìm kiếm với từ khóa khác hoặc xem tất cả sản phẩm.</p>
                    <a href="{{ route('products.index') }}" class="text-red-600 hover:text-red-700 mt-4 inline-block">
                        Xem tất cả sản phẩm →
                    </a>
                </div>
            @endif
        </main>
    </div>
</div>

<script>
    // Products filter script
    
    // Handle sort change and filters
    document.addEventListener('DOMContentLoaded', function() {
        // Sort change handler
        const sortSelect = document.getElementById('sort-select');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                const url = new URL(window.location);
                url.searchParams.set('sort', this.value);
                window.location = url.toString();
            });
        }

        // Filter checkbox handlers
        const checkboxes = document.querySelectorAll('.filter-checkbox');
        
        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', function() {
                submitFilters();
            });
        });
    });

    function submitFilters() {
        const form = document.getElementById('filter-form');
        if (!form) return;
        
        const url = new URL(window.location.href);
        
        // Clear existing filter parameters
        url.searchParams.delete('price_range[]');
        url.searchParams.delete('rating[]');
        url.searchParams.delete('price_range');
        url.searchParams.delete('rating');
        
        // Get all checked checkboxes
        const priceCheckboxes = form.querySelectorAll('input[name="price_range[]"]:checked');
        const ratingCheckboxes = form.querySelectorAll('input[name="rating[]"]:checked');
        
        // Add price filters
        priceCheckboxes.forEach(checkbox => {
            url.searchParams.append('price_range[]', checkbox.value);
        });
        
        // Add rating filters  
        ratingCheckboxes.forEach(checkbox => {
            url.searchParams.append('rating[]', checkbox.value);
        });
        
        // Preserve search query
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput && searchInput.value) {
            url.searchParams.set('search', searchInput.value);
        }
        
        // Preserve sort
        const sortSelect = document.getElementById('sort-select');
        if (sortSelect && sortSelect.value && sortSelect.value !== 'latest') {
            url.searchParams.set('sort', sortSelect.value);
        }
        
        window.location = url.toString();
    }

    function clearPriceFilters() {
        const priceCheckboxes = document.querySelectorAll('input[name="price_range[]"]');
        priceCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        submitFilters();
    }

    function clearRatingFilters() {
        const ratingCheckboxes = document.querySelectorAll('input[name="rating[]"]');
        ratingCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        submitFilters();
    }
</script>
@endsection 