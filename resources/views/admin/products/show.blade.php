@extends('admin.layouts.app')

@section('title', 'Chi tiết Sản phẩm')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Chi tiết sản phẩm</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.products.edit', $product) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <x-heroicon-o-pencil class="w-5 h-5 mr-2" />
                Sửa
            </a>
            <a href="{{ route('admin.products.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                Quay lại
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Product Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">{{ $product->name }}</h3>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $product->is_active ? 'Hoạt động' : 'Tạm dừng' }}
                    </span>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Mã SKU</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $product->sku ?: 'Chưa có' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Danh mục</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $product->category->name }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Giá gốc</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">{{ number_format($product->price) }}đ</dd>
                            </div>
                            @if($product->sale_price)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Giá khuyến mãi</dt>
                                <dd class="mt-1 text-lg font-semibold text-red-600">{{ number_format($product->sale_price) }}đ</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Trọng lượng</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $product->weight ? $product->weight . ' kg' : 'Chưa có' }}</dd>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tồn kho</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $product->in_stock ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->stock_quantity }} sản phẩm
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Đã bán</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $product->sold_count }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Đánh giá</dt>
                                <dd class="mt-1 flex items-center">
                                    <div class="flex items-center text-yellow-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            <x-heroicon-s-star class="w-4 h-4 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" />
                                        @endfor
                                    </div>
                                    <span class="ml-2 text-sm text-gray-500">({{ $product->average_rating }}/5 - {{ $product->review_count }} đánh giá)</span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Quản lý kho</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $product->manage_stock ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $product->manage_stock ? 'Có' : 'Không' }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Ngày tạo</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $product->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </div>
                    </div>

                    @if($product->short_description)
                    <div class="mt-6">
                        <dt class="text-sm font-medium text-gray-500">Mô tả ngắn</dt>
                        <dd class="mt-2 text-sm text-gray-700">{{ $product->short_description }}</dd>
                    </div>
                    @endif

                    <div class="mt-6">
                        <dt class="text-sm font-medium text-gray-500">Mô tả chi tiết</dt>
                        <dd class="mt-2 text-sm text-gray-700 bg-gray-50 rounded-lg p-4">
                            {{ $product->description }}
                        </dd>
                    </div>
                </div>
            </div>

            <!-- Featured Image Only -->
            @if($product->featured_image)
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Ảnh đại diện</h3>
                </div>
                <div class="p-6">
                    <div class="max-w-md mx-auto">
                        <img src="{{ $product->featured_image_url }}" 
                             class="w-full h-64 object-cover rounded-lg"
                             alt="Ảnh đại diện">
                    </div>
                </div>
            </div>
            @endif

            <!-- Reviews -->
            @if($product->reviews->count() > 0)
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Đánh giá gần đây</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        @foreach($product->reviews->take(5) as $review)
                        <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <h6 class="font-medium text-gray-900">{{ $review->user->name }}</h6>
                                        <div class="flex items-center text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <x-heroicon-s-star class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" />
                                            @endfor
                                        </div>
                                        <span class="text-sm text-gray-500">({{ $review->rating }}/5)</span>
                                    </div>
                                    @if($review->title)
                                        <h6 class="mt-1 font-semibold text-gray-900">{{ $review->title }}</h6>
                                    @endif
                                    <p class="mt-1 text-sm text-gray-700">{{ $review->comment }}</p>
                                </div>
                                <div class="text-right ml-4">
                                    <div class="text-sm text-gray-500">{{ $review->created_at->format('d/m/Y') }}</div>
                                    @if($review->is_verified_purchase)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 mt-1">
                                            Đã mua hàng
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($product->reviews->count() > 5)
                    <div class="mt-6 text-center">
                        <span class="text-gray-500 text-sm">
                            Tổng cộng {{ $product->reviews->count() }} đánh giá (hiển thị 5 gần nhất)
                        </span>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-6">Thống kê nhanh</h3>
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div class="border-r border-gray-200">
                        <div class="text-2xl font-bold text-blue-600">{{ $product->sold_count }}</div>
                        <div class="text-sm text-gray-500">Đã bán</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-yellow-600">{{ $product->average_rating }}/5</div>
                        <div class="text-sm text-gray-500">Đánh giá TB</div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 text-center mt-4 pt-4 border-t border-gray-200">
                    <div class="border-r border-gray-200">
                        <div class="text-2xl font-bold text-green-600">{{ $product->review_count }}</div>
                        <div class="text-sm text-gray-500">Lượt đánh giá</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold {{ $product->in_stock ? 'text-green-600' : 'text-red-600' }}">
                            {{ $product->stock_quantity }}
                        </div>
                        <div class="text-sm text-gray-500">Tồn kho</div>
                    </div>
                </div>
            </div>

            <!-- Revenue Calculation -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-6">Doanh thu ước tính</h3>
                @php
                    $finalPrice = $product->sale_price ?: $product->price;
                    $estimatedRevenue = $finalPrice * $product->sold_count;
                @endphp
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ number_format($estimatedRevenue) }}đ</div>
                    <div class="text-sm text-gray-500 mt-1">
                        {{ $product->sold_count }} × {{ number_format($finalPrice) }}đ
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 