@extends('admin.layouts.app')

@section('title', 'Chi tiết Danh mục')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <a href="{{ route('admin.categories.index') }}" 
               class="text-gray-500 hover:text-gray-700 mr-4">
                <x-heroicon-o-arrow-left class="w-6 h-6"/>
            </a>
            <h1 class="text-2xl font-semibold text-gray-900">Chi tiết Danh mục: {{ $category->name }}</h1>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.categories.edit', $category) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <x-heroicon-o-pencil class="w-4 h-4 mr-2"/>
                Chỉnh sửa
            </a>
            @if($category->products->count() == 0)
                <form action="{{ route('admin.categories.destroy', $category) }}" 
                      method="POST" 
                      class="inline"
                      onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <x-heroicon-o-trash class="w-4 h-4 mr-2"/>
                        Xóa
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Category Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Thông tin danh mục</h2>
                
                <!-- Category Icon -->
                <div class="mb-6">
                    <div class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center mx-auto">
                        <x-heroicon-o-squares-2x2 class="w-12 h-12 text-gray-600" />
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tên danh mục</label>
                        <p class="text-gray-900">{{ $category->name }}</p>
                    </div>

                    <!-- Slug -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                        <code class="text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded">{{ $category->slug }}</code>
                    </div>

                    <!-- Description -->
                    @if($category->description)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
                            <p class="text-gray-600">{{ $category->description }}</p>
                        </div>
                    @endif

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                        @if($category->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <x-heroicon-o-check-circle class="w-4 h-4 mr-1"/>
                                Hoạt động
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <x-heroicon-o-x-circle class="w-4 h-4 mr-1"/>
                                Không hoạt động
                            </span>
                        @endif
                    </div>

                    <!-- Statistics -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Thống kê</label>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Tổng sản phẩm:</span>
                                <span class="text-sm font-medium">{{ $category->products->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Sản phẩm hoạt động:</span>
                                <span class="text-sm font-medium">{{ $category->products->where('is_active', true)->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Ngày tạo:</span>
                                <span class="text-sm font-medium">{{ $category->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Sản phẩm trong danh mục ({{ $category->products->count() }})</h2>
                </div>

                @if($category->products->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($category->products as $product)
                            <div class="p-6 hover:bg-gray-50">
                                <div class="flex items-center space-x-4">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        <img src="{{ $product->featured_image_url ?: asset('images/products/default-product.jpg') }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-16 h-16 object-cover rounded-lg">
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</h3>
                                            <div class="flex items-center space-x-2">
                                                @if($product->is_active)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Hoạt động
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Không hoạt động
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mt-1 flex items-center space-x-4">
                                            <p class="text-sm text-gray-600">
                                                Giá: <span class="font-medium text-blue-600">{{ number_format($product->price) }}đ</span>
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                Tạo: {{ $product->created_at->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center space-x-2">
                                        @if($product->id && ($product->slug ?? null))
                                            <a href="{{ route('admin.products.show', $product) }}" 
                                               class="text-blue-600 hover:text-blue-900">
                                                <x-heroicon-o-eye class="w-5 h-5"/>
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product) }}" 
                                               class="text-yellow-600 hover:text-yellow-900">
                                                <x-heroicon-o-pencil class="w-5 h-5"/>
                                            </a>
                                        @else
                                            <span class="text-gray-400">
                                                <x-heroicon-o-eye class="w-5 h-5"/>
                                            </span>
                                            <span class="text-gray-400">
                                                <x-heroicon-o-pencil class="w-5 h-5"/>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-6 text-center">
                        <div class="flex flex-col items-center py-8">
                            <x-heroicon-o-cube class="w-12 h-12 text-gray-400 mb-4"/>
                            <p class="text-lg font-medium text-gray-900 mb-2">Chưa có sản phẩm nào</p>
                            <p class="text-gray-500 mb-4">Danh mục này chưa có sản phẩm nào</p>
                            <a href="{{ route('admin.products.create') }}?category_id={{ $category->id }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                Thêm sản phẩm
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 