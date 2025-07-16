@extends('admin.layouts.app')

@section('title', 'Quản lý Sản phẩm')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Quản lý Sản phẩm</h1>
        <a href="{{ route('admin.products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
            <x-heroicon-o-plus class="w-5 h-5 mr-2" />
            Thêm sản phẩm
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('admin.products.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                       name="search" value="{{ request('search') }}" placeholder="Tên, SKU, mô tả...">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" name="category_id">
                    <option value="">Tất cả</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" name="status">
                    <option value="">Tất cả</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kho hàng</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" name="stock">
                    <option value="">Tất cả</option>
                    <option value="in_stock" {{ request('stock') == 'in_stock' ? 'selected' : '' }}>Còn hàng</option>
                    <option value="out_of_stock" {{ request('stock') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <div class="flex space-x-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                        <x-heroicon-o-x-mark class="w-5 h-5" />
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Results -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                Danh sách sản phẩm ({{ $products->total() }} kết quả)
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            @if($products->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hình ảnh</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh mục</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tồn kho</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đã bán</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($products as $product)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img src="{{ $product->featured_image_url ?: asset('images/products/default-product.jpg') }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-16 h-16 rounded-lg object-cover">
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $product->name }}</div>
                                        @if($product->sku)
                                            <div class="text-sm text-gray-500">SKU: {{ $product->sku }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $product->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        @if($product->sale_price)
                                            <div class="font-semibold text-red-600">{{ number_format($product->sale_price) }}đ</div>
                                            <div class="text-sm text-gray-500 line-through">{{ number_format($product->price) }}đ</div>
                                        @else
                                            <div class="font-semibold text-gray-900">{{ number_format($product->price) }}đ</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $product->in_stock ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->stock_quantity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $product->sold_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $product->is_active ? 'Hoạt động' : 'Tạm dừng' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.products.show', $product) }}" 
                                           class="text-blue-600 hover:text-blue-900" title="Xem">
                                            <x-heroicon-o-eye class="w-5 h-5" />
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}" 
                                           class="text-green-600 hover:text-green-900" title="Sửa">
                                            <x-heroicon-o-pencil class="w-5 h-5" />
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <x-heroicon-o-cube class="mx-auto h-12 w-12 text-gray-400" />
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Không tìm thấy sản phẩm nào</h3>
                    <p class="mt-1 text-sm text-gray-500">Hãy thử thay đổi điều kiện tìm kiếm hoặc thêm sản phẩm mới</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.products.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <x-heroicon-o-plus class="w-5 h-5 mr-2" />
                            Thêm sản phẩm đầu tiên
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
