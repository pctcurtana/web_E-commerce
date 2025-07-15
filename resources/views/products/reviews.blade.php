@extends('layouts.app')

@section('title', 'Đánh giá sản phẩm: ' . $product->name)

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
            <li><a href="{{ route('products.show', $product->slug) }}" class="hover:text-red-600">{{ $product->name }}</a></li>
            <li><x-heroicon-o-chevron-right class="w-4 h-4" /></li>
            <li class="text-gray-900 font-medium">Đánh giá</li>
        </ol>
    </nav>

    <!-- Product Info Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <div class="flex items-center space-x-6">
            <div class="flex-shrink-0">
                <img src="{{ $product->featured_image_url ?: asset('images/products/default-product.jpg') }}" 
                     alt="{{ $product->name }}" 
                     class="w-20 h-20 object-cover rounded-lg">
            </div>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <x-heroicon-s-star class="w-5 h-5 {{ $i <= floor($product->average_rating) ? 'text-yellow-400' : 'text-gray-300' }}" />
                        @endfor
                        <span class="ml-2 text-sm font-medium text-gray-900">
                            {{ number_format($product->average_rating, 1) }}
                        </span>
                    </div>
                    <span class="text-sm text-gray-500">|</span>
                    <span class="text-sm text-gray-600">{{ $product->review_count }} đánh giá</span>
                </div>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('products.show', $product->slug) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <x-heroicon-o-arrow-left class="w-4 h-4 mr-2" />
                    Quay lại sản phẩm
                </a>
            </div>
        </div>
    </div>

    <!-- Rating Summary -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Thống kê đánh giá</h2>
        
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-gray-900">{{ number_format($product->average_rating, 1) }}</div>
                    <div class="flex items-center justify-center mt-1">
                        @for($i = 1; $i <= 5; $i++)
                            <x-heroicon-s-star class="w-5 h-5 {{ $i <= floor($product->average_rating) ? 'text-yellow-400' : 'text-gray-300' }}" />
                        @endfor
                    </div>
                    <div class="text-sm text-gray-500 mt-1">{{ $product->review_count }} đánh giá</div>
                </div>
                
                <!-- Rating Breakdown -->
                <div class="flex-1 max-w-md">
                    @foreach($ratingStats as $star => $count)
                        <div class="flex items-center space-x-2 mb-1">
                            <span class="text-sm text-gray-600 w-6">{{ $star }}</span>
                            <x-heroicon-s-star class="w-4 h-4 text-yellow-400" />
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-400 h-2 rounded-full transition-all duration-300" 
                                     style="width: {{ $ratingPercentages[$star] }}%"></div>
                            </div>
                            <span class="text-sm text-gray-500 w-12 text-right">{{ $count }} ({{ $ratingPercentages[$star] }}%)</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- All Reviews -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Tất cả đánh giá ({{ $reviews->total() }})</h2>
        
        @if($reviews->count() > 0)
            <div class="space-y-6">
                @foreach($reviews as $review)
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
                                            <span class="ml-2 text-sm text-gray-500">{{ $review->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </div>
                                    @auth
                                        @if($review->user_id === Auth::id())
                                            <div class="flex space-x-2">
                                                <a href="{{ route('products.show', $product->slug) }}#review-form" 
                                                   class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                                    <x-heroicon-o-pencil class="w-4 h-4 inline mr-1" />
                                                    Sửa
                                                </a>
                                                <button onclick="deleteReview()" 
                                                        class="text-red-600 hover:text-red-700 text-sm font-medium">
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

            <!-- Pagination -->
            @if($reviews->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $reviews->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <x-heroicon-o-chat-bubble-left-ellipsis class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có đánh giá nào</h3>
                <p class="text-gray-500">Sản phẩm này chưa có đánh giá từ khách hàng.</p>
            </div>
        @endif
    </div>
</div>

<script>
    async function deleteReview() {
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

        if (result.isConfirmed) {
            try {
                const response = await fetch(`{{ route('reviews.destroy', $product->slug) }}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.ok) {
                    Swal.fire({
                        title: 'Đã xóa!',
                        text: 'Đánh giá của bạn đã được xóa.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '{{ route("products.show", $product->slug) }}';
                    });
                } else {
                    throw new Error('Failed to delete review');
                }
            } catch (error) {
                Swal.fire({
                    title: 'Lỗi!',
                    text: 'Có lỗi xảy ra khi xóa đánh giá.',
                    icon: 'error',
                    confirmButtonColor: '#dc2626'
                });
            }
        }
    }
</script>

@endsection 