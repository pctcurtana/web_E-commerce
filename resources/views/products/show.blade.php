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
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Đánh giá từ khách hàng</h2>
            @auth
                <button class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors text-sm font-medium"
                        id="review-button">
                    <x-heroicon-o-star class="w-4 h-4 inline mr-1" />
                    Viết đánh giá
                </button>
            @endauth
        </div>

        <!-- Review Form -->
        @auth
            <div id="review-form" class="hidden mb-8 p-6 bg-gray-50 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Đánh giá sản phẩm</h3>
                <form id="review-form-element" method="POST" action="">
                    @csrf
                    <div class="space-y-4">
                        <!-- Rating -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Đánh giá của bạn *</label>
                            <div class="flex items-center space-x-1" id="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" 
                                            class="star-rating text-gray-300 hover:text-yellow-400 transition-colors" 
                                            data-rating="{{ $i }}"
                                            onclick="setRating({{ $i }})">
                                        <x-heroicon-s-star class="w-8 h-8" />
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="rating-input" required>
                            <div id="rating-error" class="text-red-600 text-sm mt-1 hidden">Vui lòng chọn số sao đánh giá.</div>
                        </div>

                        <!-- Comment -->
                        <div>
                            <label for="review-comment" class="block text-sm font-medium text-gray-700 mb-2">Mô tả đánh giá *</label>
                            <textarea id="review-comment" 
                                      name="comment" 
                                      rows="4"
                                      required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                      placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..."></textarea>
                        </div>

                        <!-- Buttons -->
                        <div class="flex space-x-3 pt-4">
                            <button type="submit" 
                                    class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors font-medium"
                                    id="submit-review-btn">
                                Gửi đánh giá
                            </button>
                            <button type="button" 
                                    onclick="cancelReview()" 
                                    class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors font-medium">
                                Hủy
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @endauth
        
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
                                    @auth
                                        @if($review->user_id === Auth::id())
                                            <div class="flex space-x-2">
                                                <button class="text-red-600 hover:text-red-700 text-sm font-medium delete-review-btn">
                                                    <x-heroicon-o-trash class="w-4 h-4 inline mr-1" />
                                                    Xóa
                                                </button>
                                            </div>
                                        @endif
                                    @endauth
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
    let currentRating = 0;
    let userReview = null;
    let canReview = false;
    


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

    // Review functions
    function setRating(rating) {
        currentRating = rating;
        document.getElementById('rating-input').value = rating;
        
        // Update star display
        const stars = document.querySelectorAll('.star-rating');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
        
        // Hide error
        document.getElementById('rating-error').classList.add('hidden');
    }

    function toggleReviewForm() {
        // Check if user can review first
        checkCanReview().then(() => {
            if (canReview) {
                const form = document.getElementById('review-form');
                const button = document.getElementById('review-button');
                
                if (form && button && form.classList.contains('hidden')) {
                    form.classList.remove('hidden');
                    // Giữ nguyên text button
                    
                    // Setup form for new review only
                    resetForm();
                    document.getElementById('review-form-element').action = `{{ route('reviews.store', $product->slug) }}`;
                    document.getElementById('submit-review-btn').textContent = 'Gửi đánh giá';
                    
                    // Remove method input if exists
                    const methodInput = document.querySelector('input[name="_method"]');
                    if (methodInput) {
                        methodInput.remove();
                    }
                } else {
                    form.classList.add('hidden');
                    button.innerHTML = '<svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>Viết đánh giá';
                    resetForm();
                }
            }
        }).catch(error => {
            alert('Có lỗi xảy ra. Vui lòng thử lại!');
        });
    }

    function cancelReview() {
        const form = document.getElementById('review-form');
        const button = document.getElementById('review-button');
        
        form.classList.add('hidden');
        button.innerHTML = '<svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>Viết đánh giá';
        resetForm();
    }

    function resetForm() {
        document.getElementById('review-form-element').reset();
        currentRating = 0;
        setRating(0);
        document.getElementById('rating-input').value = '';
    }

    async function checkCanReview() {
        try {
            const response = await fetch(`{{ route('reviews.can-review', $product->slug) }}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            const data = await response.json();
            canReview = data.can_review;
            
            if (!canReview) {
                Swal.fire({
                    title: 'Không thể đánh giá',
                    text: data.message,
                    icon: 'info',
                    confirmButtonColor: '#dc2626'
                });
            }
        } catch (error) {
            canReview = false;
        }
    }



    async function deleteUserReview() {
        // Check if SweetAlert2 is loaded
        let confirmed = false;
        if (typeof Swal === 'undefined') {
            confirmed = confirm('Bạn có chắc chắn muốn xóa đánh giá này không?');
        } else {
            const result = await Swal.fire({
                title: 'Xóa đánh giá?',
                text: 'Bạn có chắc chắn muốn xóa đánh giá này không?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            });
            confirmed = result.isConfirmed;
        }

        if (confirmed) {
            // Tạo form để submit DELETE request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ route('reviews.destroy', $product->slug) }}`;
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfToken);
            
            // Add method override for DELETE
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);
            
            document.body.appendChild(form);
            
            // Show loading
            Swal.fire({
                title: 'Đang xóa...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit form after short delay
            setTimeout(() => {
                form.submit();
            }, 500);
        }
    }

    // Handle form submission
    document.addEventListener('DOMContentLoaded', function() {
        // Add click listener to review button if it exists
        const reviewButton = document.getElementById('review-button');
        if (reviewButton) {
            reviewButton.addEventListener('click', function(e) {
                e.preventDefault();
                toggleReviewForm();
            });
        }

        // Only delete buttons need event listeners

        document.querySelectorAll('.delete-review-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                deleteUserReview();
            });
        });
        
        // No need to check user review status
        
        const reviewFormElement = document.getElementById('review-form-element');
        if (reviewFormElement) {
            reviewFormElement.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Validate rating
                if (!currentRating) {
                    document.getElementById('rating-error').classList.remove('hidden');
                    return;
                }
                
                // Chuyển sang sử dụng regular form submission thay vì AJAX
                // để tránh vấn đề với CSRF token và routing
                const form = this;
                
                // Show loading
                Swal.fire({
                    title: 'Đang gửi đánh giá...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit form normally sau 500ms
                setTimeout(() => {
                    form.submit();
                }, 500);
            });
        }
    });

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