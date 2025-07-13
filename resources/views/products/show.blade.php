@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm text-gray-500">
            <li><a href="{{ route('home') }}" class="hover:text-red-600">Trang chủ</a></li>
            <li><x-heroicon-o-chevron-right class="w-4 h-4" /></li>
            <li><a href="{{ route('products.index') }}" class="hover:text-red-600">Sản phẩm</a></li>
            <li><x-heroicon-o-chevron-right class="w-4 h-4" /></li>
            <li><a href="{{ route('category.show', $product->category->slug) }}" class="hover:text-red-600">{{ $product->category->name }}</a></li>
            <li><x-heroicon-o-chevron-right class="w-4 h-4" /></li>
            <li class="text-gray-900 font-medium">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
        <!-- Product Images -->
        <div class="space-y-4">
            <div class="aspect-square rounded-lg overflow-hidden bg-gray-100">
                <img src="{{ $product->featured_image_url ?: asset('images/products/default-product.jpg') }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-full object-cover">
            </div>
        </div>

        <!-- Product Info -->
        <div class="space-y-6">
            <div>
                <p class="text-sm text-gray-500 mb-2">{{ $product->category->name }}</p>
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
                
                <!-- Rating -->
                <div class="flex items-center space-x-4 mb-4">
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <x-heroicon-s-star class="w-5 h-5 {{ $i <= floor($product->average_rating) ? 'text-yellow-400' : 'text-gray-300' }}" />
                        @endfor
                        <span class="ml-2 text-sm text-gray-600">
                            ({{ $product->review_count > 0 ? number_format($product->average_rating, 1) . ' - ' . $product->review_count . ' đánh giá' : 'Chưa có đánh giá' }})
                        </span>
                    </div>
                    <span class="text-sm text-gray-500">|</span>
                    <span class="text-sm text-gray-600">Đã bán {{ number_format($product->sold_count) }}</span>
                </div>

                <!-- Price -->
                <div class="mb-6">
                    <div class="flex items-baseline space-x-4">
                        <span class="text-3xl font-bold text-red-600">{{ number_format($product->final_price) }}đ</span>
                        @if($product->sale_price)
                            <span class="text-xl text-gray-400 line-through">{{ number_format($product->price) }}đ</span>
                            <span class="bg-red-600 text-white px-2 py-1 rounded-full text-sm font-bold">
                                -{{ $product->discount_percentage }}%
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Short Description -->
            @if($product->short_description)
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Mô tả ngắn</h3>
                    <p class="text-gray-600">{{ $product->short_description }}</p>
                </div>
            @endif

            <!-- Stock Status -->
            <div class="border-t border-gray-200 pt-6">
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Tình trạng:</span>
                    @if($product->in_stock && $product->stock_quantity > 0)
                        <span class="text-green-600 font-medium">
                            <x-heroicon-o-check-circle class="w-4 h-4 inline mr-1" />
                            Còn hàng ({{ $product->stock_quantity }} sản phẩm)
                        </span>
                    @else
                        <span class="text-red-600 font-medium">
                            <x-heroicon-o-x-circle class="w-4 h-4 inline mr-1" />
                            Hết hàng
                        </span>
                    @endif
                </div>
            </div>

            <!-- Add to Cart -->
            @if($product->in_stock && $product->stock_quantity > 0)
                <div class="border-t border-gray-200 pt-6">
                    <form action="{{ route('cart.add') }}" method="POST" onsubmit="event.preventDefault(); addToCartWithNotification(this);" class="space-y-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="flex items-center space-x-4">
                            <label class="text-sm font-medium text-gray-700">Số lượng:</label>
                            <div class="flex items-center border border-gray-300 rounded-lg">
                                <button type="button" onclick="decreaseQuantity()" class="px-3 py-2 hover:bg-gray-100 rounded-l-lg">
                                    <x-heroicon-o-minus class="w-4 h-4" />
                                </button>
                                <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}"
                                       class="w-20 text-center border-0 focus:ring-0 focus:outline-none py-2" id="quantity-input">
                                <button type="button" onclick="increaseQuantity()" class="px-3 py-2 hover:bg-gray-100 rounded-r-lg">
                                    <x-heroicon-o-plus class="w-4 h-4" />
                                </button>
                            </div>
                        </div>

                        <div class="flex space-x-4">
                            <button type="submit" class="flex-1 bg-red-600 text-white py-3 px-6 rounded-lg hover:bg-red-700 transition-colors font-medium">
                                <x-heroicon-o-shopping-cart class="w-5 h-5 inline-block mr-2" />
                                Thêm vào giỏ hàng
                            </button>
                            <!-- <button type="button" class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                <x-heroicon-o-heart class="w-5 h-5 text-gray-600" />
                            </button> -->
                        </div>
                    </form>
                </div>
            @endif

            <!-- Features -->
            <div class="border-t border-gray-200 pt-6">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="flex items-center text-gray-600">
                        <x-heroicon-o-truck class="w-4 h-4 mr-2" />
                        <span>Miễn phí vận chuyển</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <x-heroicon-o-shield-check class="w-4 h-4 mr-2" />
                        <span>Bảo hành chính hãng</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <x-heroicon-o-arrow-path class="w-4 h-4 mr-2" />
                        <span>Đổi trả trong 7 ngày</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <x-heroicon-o-credit-card class="w-4 h-4 mr-2" />
                        <span>Thanh toán an toàn</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Description -->
    <div class="bg-white rounded-lg shadow-sm p-8 mb-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Mô tả sản phẩm</h2>
        <div class="prose max-w-none text-gray-600">
            {!! nl2br(e($product->description)) !!}
        </div>
    </div>

    <!-- Customer Reviews -->
    <div class="bg-white rounded-lg shadow-sm p-8 mb-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Đánh giá từ khách hàng</h2>
        
        @if($product->review_count > 0)
            <!-- Rating Summary -->
            <div class="flex items-center justify-between border-b border-gray-200 pb-6 mb-6">
                <div class="flex items-center space-x-6">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-gray-900">{{ number_format($product->average_rating, 1) }}</div>
                        <div class="flex items-center justify-center mt-1">
                            @for($i = 1; $i <= 5; $i++)
                                <x-heroicon-s-star class="w-5 h-5 {{ $i <= floor($product->average_rating) ? 'text-yellow-400' : 'text-gray-300' }}" />
                            @endfor
                        </div>
                        <div class="text-sm text-gray-500 mt-1">{{ $product->review_count }} đánh giá</div>
                    </div>
                    
                    <!-- Rating Breakdown -->
                    <div class="flex-1 max-w-md">
                        @php
                            $ratingBreakdown = $product->approvedReviews->groupBy('rating')->map->count();
                        @endphp
                        @for($star = 5; $star >= 1; $star--)
                            @php
                                $count = $ratingBreakdown->get($star, 0);
                                $percentage = $product->review_count > 0 ? ($count / $product->review_count) * 100 : 0;
                            @endphp
                            <div class="flex items-center space-x-2 mb-1">
                                <span class="text-sm text-gray-600 w-6">{{ $star }}</span>
                                <x-heroicon-s-star class="w-4 h-4 text-yellow-400" />
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="text-sm text-gray-500 w-8">{{ $count }}</span>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Reviews List -->
            <div class="space-y-6">
                @foreach($product->approvedReviews as $review)
                    <div class="border-b border-gray-100 pb-6 last:border-b-0 last:pb-0">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-gray-700">
                                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $review->user->name }}</h4>
                                        <div class="flex items-center mt-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <x-heroicon-s-star class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" />
                                            @endfor
                                            <span class="ml-2 text-sm text-gray-500">{{ $review->created_at->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                                @if($review->comment)
                                    <p class="text-gray-700 leading-relaxed">{{ $review->comment }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($product->review_count > 0)
                <div class="text-center mt-6">
                    <a href="{{ route('products.reviews', $product->slug) }}" class="text-red-600 hover:text-red-700 font-medium">
                        Xem tất cả đánh giá ({{ $product->review_count }})
                    </a>
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <x-heroicon-o-chat-bubble-left-ellipsis class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có đánh giá nào</h3>
                <p class="text-gray-500">Hãy là người đầu tiên đánh giá sản phẩm này!</p>
            </div>
        @endif
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Sản phẩm liên quan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow group">
                        <a href="{{ route('products.show', $relatedProduct->slug) }}">
                            <div class="relative overflow-hidden">
                                <img src="{{ $relatedProduct->featured_image_url ?: asset('images/products/default-product.jpg') }}" 
                                     alt="{{ $relatedProduct->name }}" 
                                     class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                @if($relatedProduct->discount_percentage > 0)
                                    <div class="absolute top-2 left-2 bg-red-600 text-white px-2 py-1 rounded-full text-xs font-bold">
                                        -{{ $relatedProduct->discount_percentage }}%
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-red-600">{{ $relatedProduct->name }}</h3>
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <x-heroicon-s-star class="w-3 h-3 {{ $i <= floor($relatedProduct->average_rating) ? 'text-yellow-400' : 'text-gray-300' }}" />
                                        @endfor
                                        <span class="text-xs text-gray-500 ml-1">
                                            @if($relatedProduct->review_count > 0)
                                                ({{ number_format($relatedProduct->average_rating, 1) }})
                                            @else
                                                (Chưa có)
                                            @endif
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-red-600 font-bold text-lg">{{ number_format($relatedProduct->final_price) }}đ</span>
                                        @if($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->price)
                                            <div class="text-gray-400 line-through text-sm">{{ number_format($relatedProduct->price) }}đ</div>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Đã bán {{ number_format($relatedProduct->sold_count) }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
    function changeMainImage(src) {
        document.getElementById('main-image').src = src;
        
        // Update border for selected thumbnail
        document.querySelectorAll('[onclick^="changeMainImage"]').forEach(thumb => {
            thumb.classList.remove('border-red-500');
            thumb.classList.add('border-transparent');
        });
        event.target.closest('[onclick^="changeMainImage"]').classList.add('border-red-500');
        event.target.closest('[onclick^="changeMainImage"]').classList.remove('border-transparent');
    }

    function increaseQuantity() {
        const input = document.getElementById('quantity-input');
        const max = parseInt(input.getAttribute('max'));
        const current = parseInt(input.value);
        if (current < max) {
            input.value = current + 1;
        }
    }

    function decreaseQuantity() {
        const input = document.getElementById('quantity-input');
        const current = parseInt(input.value);
        if (current > 1) {
            input.value = current - 1;
        }
    }

    function addToCartWithNotification(form) {
        // Check if user is authenticated
        @guest
            Swal.fire({
                title: 'Vui lòng đăng nhập',
                text: 'Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Đăng nhập',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("login") }}';
                }
            });
            return;
        @endguest

        const formData = new FormData(form);
        
        // Show loading
        Swal.fire({
            title: 'Đang thêm vào giỏ hàng...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Thành công!',
                    text: data.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Update cart count if exists
                if (typeof updateCartCount === 'function') {
                    updateCartCount();
                }
            } else {
                Swal.fire({
                    title: 'Lỗi!',
                    text: data.message,
                    icon: 'error',
                    confirmButtonColor: '#dc2626'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Lỗi!',
                text: 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng',
                icon: 'error',
                confirmButtonColor: '#dc2626'
            });
        });
    }
</script>
@endsection 