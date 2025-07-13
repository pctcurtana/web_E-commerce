@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
<!-- Hero Banner -->
<section class="relative min-h-screen bg-gradient-to-br from-red-600 via-pink-600 to-purple-700 text-white overflow-hidden">
    <!-- Animated Background Decorations -->
    <div class="absolute inset-0">
        <!-- Floating Shapes -->
        <div class="absolute top-20 left-10 w-72 h-72 bg-white/10 rounded-full blur-xl animate-pulse"></div>
        <div class="absolute top-40 right-20 w-96 h-96 bg-yellow-300/20 rounded-full blur-2xl animate-bounce" style="animation-duration: 3s;"></div>
        <div class="absolute bottom-20 left-1/4 w-80 h-80 bg-pink-300/15 rounded-full blur-xl animate-pulse" style="animation-delay: 1s;"></div>
        
        <!-- Grid Pattern -->
        <div class="absolute inset-0 bg-grid-white/[0.05] bg-[size:50px_50px]"></div>
        
        <!-- Gradient Overlays -->
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-red-600/30 to-transparent"></div>
        <div class="absolute bottom-0 right-0 w-full h-full bg-gradient-to-l from-purple-700/30 to-transparent"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center min-h-[80vh]">
            <!-- Left Content -->
            <div class="text-center lg:text-left">
                <!-- Badge -->
                <div class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full mb-6 animate-fadeInUp">
                    <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                    <span class="text-sm font-medium">Ưu đãi đặc biệt mùa lễ hội</span>
                </div>

                <!-- Main Heading -->
                <h1 class="text-5xl lg:text-7xl font-bold mb-6 animate-fadeInUp" style="animation-delay: 0.2s;">
                    <span class="block text-transparent bg-clip-text bg-gradient-to-r from-white to-yellow-200">
                        Mua sắm thả ga
                    </span>
                    <span class="block text-yellow-300 mt-2 text-4xl lg:text-6xl animate-shimmer">
                        Ưu đãi khủng 🔥
                    </span>
                </h1>

                <!-- Subtitle -->
                <p class="text-xl lg:text-2xl mb-8 text-red-100 max-w-2xl animate-fadeInUp" style="animation-delay: 0.4s;">
                    <span class="font-semibold text-yellow-200">Hàng triệu</span> sản phẩm chính hãng • 
                    <span class="font-semibold text-yellow-200">Giao hàng</span> toàn quốc • 
                    <span class="font-semibold text-yellow-200">Thanh toán</span> an toàn
                </p>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-6 mb-10 animate-fadeInUp" style="animation-delay: 0.6s;">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-300">
                            @if($stats['total_products'] >= 1000)
                                {{ number_format($stats['total_products'] / 1000, 0) }}K+
                            @else
                                {{ number_format($stats['total_products']) }}
                            @endif
                        </div>
                        <div class="text-sm text-red-200">Sản phẩm</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-300">{{ $stats['satisfaction_rate'] }}%</div>
                        <div class="text-sm text-red-200">Hài lòng</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-300">24/7</div>
                        <div class="text-sm text-red-200">Hỗ trợ</div>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start animate-fadeInUp" style="animation-delay: 0.8s;">
                    <a href="{{ route('products.index') }}" 
                       class="group relative px-8 py-4 bg-white text-red-600 rounded-2xl font-bold text-lg hover:bg-yellow-50 transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                        <span class="relative z-10 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Mua ngay
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-yellow-400 to-orange-400 rounded-2xl blur opacity-0 group-hover:opacity-75 transition-opacity"></div>
                    </a>
                    
                    <a href="#categories" 
                       class="group px-8 py-4 border-2 border-white/50 text-white rounded-2xl font-bold text-lg hover:bg-white/10 hover:border-white transition-all duration-300 transform hover:scale-105 backdrop-blur-sm">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2 group-hover:animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Khám phá
                        </span>
                    </a>
                </div>

                <!-- Trust Indicators -->
                <div class="flex items-center justify-center lg:justify-start mt-8 space-x-6 animate-fadeInUp" style="animation-delay: 1s;">
                    <div class="flex items-center text-sm text-red-200">
                        <svg class="w-4 h-4 mr-2 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Miễn phí đổi trả
                    </div>
                    <div class="flex items-center text-sm text-red-200">
                        <svg class="w-4 h-4 mr-2 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                        </svg>
                        Bảo mật tuyệt đối
                    </div>
                </div>
            </div>

            <!-- Right Content - Hero Image -->
            <div class="relative lg:block animate-fadeInRight">
                <div class="relative">
                    <!-- Main Image Container -->
                    <div class="relative bg-white/10 backdrop-blur-lg rounded-3xl p-8 shadow-2xl border border-white/20">
                        <!-- Floating Product Cards -->
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Product Card 1 -->
                            <div class="bg-white rounded-2xl p-4 shadow-lg transform rotate-3 hover:rotate-0 transition-transform duration-300 animate-float">
                                <div class="w-full h-24 bg-gradient-to-br from-red-100 to-pink-100 rounded-xl mb-3 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="text-xs text-gray-600 font-medium">Điện thoại</div>
                                <div class="text-xs text-red-600 font-bold">5.990.000đ</div>
                            </div>

                            <!-- Product Card 2 -->
                            <div class="bg-white rounded-2xl p-4 shadow-lg transform -rotate-2 hover:rotate-0 transition-transform duration-300 animate-float" style="animation-delay: 0.5s;">
                                <div class="w-full h-24 bg-gradient-to-br from-blue-100 to-purple-100 rounded-xl mb-3 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="text-xs text-gray-600 font-medium">Laptop</div>
                                <div class="text-xs text-red-600 font-bold">17.990.000đ</div>
                            </div>

                            <!-- Product Card 3 -->
                            <div class="bg-white rounded-2xl p-4 shadow-lg transform rotate-1 hover:rotate-0 transition-transform duration-300 animate-float" style="animation-delay: 1s;">
                                <div class="w-full h-24 bg-gradient-to-br from-green-100 to-teal-100 rounded-xl mb-3 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </div>
                                <div class="text-xs text-gray-600 font-medium">Phụ kiện</div>
                                <div class="text-xs text-red-600 font-bold">199.000đ</div>
                            </div>

                            <!-- Product Card 4 -->
                            <div class="bg-white rounded-2xl p-4 shadow-lg transform -rotate-1 hover:rotate-0 transition-transform duration-300 animate-float" style="animation-delay: 1.5s;">
                                <div class="w-full h-24 bg-gradient-to-br from-yellow-100 to-orange-100 rounded-xl mb-3 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div class="text-xs text-gray-600 font-medium">Nhà cửa</div>
                                <div class="text-xs text-red-600 font-bold">690.000đ</div>
                            </div>
                        </div>

                        <!-- Discount Badge -->
                        <div class="absolute -top-4 -right-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-4 py-2 rounded-full text-sm font-bold animate-pulse shadow-lg">
                            -50%
                        </div>
                    </div>

                    <!-- Floating Elements -->
                    <div class="absolute -top-8 -left-8 w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center animate-bounce shadow-lg">
                        <span class="text-xl">🎁</span>
                    </div>
                    <div class="absolute -bottom-6 -right-6 w-12 h-12 bg-pink-500 rounded-full flex items-center justify-center animate-pulse shadow-lg">
                        <span class="text-lg">💎</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <a href="#categories" class="text-white/70 hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </a>
    </div>
</section>

<!-- Custom CSS for animations -->
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(var(--rotate, 0deg)); }
        50% { transform: translateY(-10px) rotate(var(--rotate, 0deg)); }
    }

    @keyframes shimmer {
        0% { background-position: -200% center; }
        100% { background-position: 200% center; }
    }

    .animate-fadeInUp {
        animation: fadeInUp 0.8s ease-out forwards;
        opacity: 0;
    }

    .animate-fadeInRight {
        animation: fadeInRight 1s ease-out forwards;
        opacity: 0;
    }

    .animate-float {
        animation: float 3s ease-in-out infinite;
    }

    .animate-shimmer {
        background: linear-gradient(90deg, currentColor 25%, #fef3c7 50%, currentColor 75%);
        background-size: 200% 100%;
        animation: shimmer 2s ease-in-out infinite;
        background-clip: text;
        -webkit-background-clip: text;
    }

    .bg-grid-white\/\[0\.05\] {
        background-image: radial-gradient(circle, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
    }
</style>

<!-- Categories Section -->
<section id="categories" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Danh mục sản phẩm</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Khám phá hàng ngàn sản phẩm chất lượng trong các danh mục hot nhất
            </p>
        </div>
        
        @if($categories->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                @foreach($categories as $category)
                    <a href="{{ route('category.show', $category->slug) }}" class="group">
                        <div class="bg-gray-100 rounded-xl py-6 text-center hover:shadow-lg transition-all duration-300 group-hover:bg-gray-100">
                            <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center group-hover:bg-gray-300">
                                <x-heroicon-o-squares-2x2 class="w-8 h-8 text-red-500" />
                            </div>
                            <h3 class="font-semibold text-gray-900">{{ $category->name }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $category->products_count }} sản phẩm</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <x-heroicon-o-squares-2x2 class="w-16 h-16 mx-auto text-gray-400 mb-4" />
                <p class="text-gray-500">Chưa có danh mục nào</p>
            </div>
        @endif
    </div>
</section>

<!-- Sale Products Section -->
<section class="py-16 bg-gradient-to-r from-orange-400 to-red-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-white mb-4">
                <x-heroicon-o-tag class="w-8 h-8 inline-block mr-2" />
                Sản phẩm khuyến mãi
            </h2>
            <p class="text-orange-100">Ưu đãi đặc biệt - Giá tốt nhất!</p>
        </div>
        
        @if($saleProducts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($saleProducts as $product)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                        <div class="relative">
                            <a href="{{ route('products.show', $product->slug) }}">
                                <img src="{{ $product->featured_image_url ?: asset('images/products/default-product.jpg') }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-48 object-cover">
                            </a>
                            @if($product->discount_percentage > 0)
                                <div class="absolute top-2 left-2 bg-red-600 text-white px-2 py-1 rounded-full text-xs font-bold">
                                    -{{ $product->discount_percentage }}%
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                                <a href="{{ route('products.show', $product->slug) }}" class="hover:text-red-600">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <div class="mb-2">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-red-600 font-bold text-lg">{{ number_format($product->final_price) }}đ</span>
                                        @if($product->sale_price)
                                            <span class="text-gray-400 line-through text-sm ml-2">{{ number_format($product->price) }}đ</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-sm mb-3">
                                <x-star-rating 
                                    :rating="$product->average_rating" 
                                    :review-count="$product->review_count" 
                                    size="xs" 
                                />
                                <span class="text-gray-500">Đã bán {{ number_format($product->sold_count) }}</span>
                            </div>
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
            
            <!-- <div class="text-center mt-8">
                <a href="{{ route('products.index') }}" class="bg-white text-red-600 px-8 py-3 rounded-lg hover:bg-orange-50 transition-colors font-semibold">
                    Xem tất cả sản phẩm
                    <x-heroicon-o-arrow-right class="w-4 h-4 inline-block ml-2" />
                </a>
            </div> -->
        @else
            <div class="text-center py-12">
                <x-heroicon-o-tag class="w-16 h-16 mx-auto text-orange-200 mb-4" />
                <p class="text-orange-100">Hiện chưa có sản phẩm khuyến mãi nào</p>
                <p class="text-orange-200 text-sm mt-2">Hãy theo dõi để không bỏ lỡ ưu đãi hấp dẫn!</p>
            </div>
        @endif
    </div>
</section>

<!-- Featured Products Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Sản phẩm nổi bật</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Những sản phẩm được yêu thích nhất tại ShopVN
            </p>
        </div>
        
        @if($featuredProducts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow group">
                        <a href="{{ route('products.show', $product->slug) }}">
                            <div class="relative overflow-hidden">
                                <img src="{{ $product->featured_image_url ?: asset('images/products/default-product.jpg') }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
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
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <span class="text-red-600 font-bold text-lg">{{ number_format($product->final_price) }}đ</span>
                                        @if($product->sale_price)
                                            <span class="text-gray-400 line-through text-sm ml-2">{{ number_format($product->price) }}đ</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <x-star-rating 
                                        :rating="$product->average_rating" 
                                        :review-count="$product->review_count" 
                                        size="sm" 
                                    />
                                    <span class="text-sm text-gray-500">Đã bán {{ number_format($product->sold_count) }}</span>
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
            
            <div class="text-center mt-12">
                <a href="{{ route('products.index') }}" class="bg-red-600 text-white px-8 py-3 rounded-lg hover:bg-red-700 transition-colors font-semibold">
                    Xem tất cả sản phẩm
                    <x-heroicon-o-arrow-right class="w-4 h-4 inline-block ml-2" />
                </a>
            </div>
        @else
            <div class="text-center py-12">
                <x-heroicon-o-cube class="w-16 h-16 mx-auto text-gray-400 mb-4" />
                <p class="text-gray-500">Chưa có sản phẩm nào</p>
                <a href="{{ route('products.index') }}" class="text-red-600 hover:text-red-700 mt-2 inline-block">
                    Khám phá ngay →
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                    <x-heroicon-o-truck class="w-8 h-8 text-red-600" />
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Miễn phí vận chuyển</h3>
                <p class="text-gray-600 text-sm">Đơn hàng từ 300k</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                    <x-heroicon-o-shield-check class="w-8 h-8 text-red-600" />
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Hàng chính hãng</h3>
                <p class="text-gray-600 text-sm">100% chính hãng</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                    <x-heroicon-o-arrow-path class="w-8 h-8 text-red-600" />
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Đổi trả dễ dàng</h3>
                <p class="text-gray-600 text-sm">Trong vòng 7 ngày</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                    <x-heroicon-o-phone class="w-8 h-8 text-red-600" />
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Hỗ trợ 24/7</h3>
                <p class="text-gray-600 text-sm">Luôn sẵn sàng hỗ trợ</p>
            </div>
        </div>
    </div>
</section>
@endsection
