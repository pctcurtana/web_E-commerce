@extends('admin.layouts.app')

@section('title', 'Thêm Sản phẩm')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Thêm sản phẩm mới</h1>
        <a href="{{ route('admin.products.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
            Quay lại
        </a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Info -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Thông tin cơ bản</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Tên sản phẩm <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                            <input type="text" id="sku" name="sku" value="{{ old('sku') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sku') border-red-500 @enderror">
                            @error('sku')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">SKU sẽ tự động tạo từ tên sản phẩm nếu bỏ trống</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">Mô tả ngắn</label>
                        <textarea id="short_description" name="short_description" rows="2" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('short_description') border-red-500 @enderror">{{ old('short_description') }}</textarea>
                        @error('short_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Mô tả chi tiết <span class="text-red-500">*</span></label>
                        <textarea id="description" name="description" rows="6" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Pricing & Inventory -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Giá & Kho hàng</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Giá gốc (VND) <span class="text-red-500">*</span></label>
                            <input type="number" id="price" name="price" value="{{ old('price') }}" min="0" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('price') border-red-500 @enderror">
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-2">Giá khuyến mãi (VND)</label>
                            <input type="number" id="sale_price" name="sale_price" value="{{ old('sale_price') }}" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sale_price') border-red-500 @enderror">
                            @error('sale_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">Số lượng tồn kho</label>
                            <input type="number" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity') }}" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('stock_quantity') border-red-500 @enderror">
                            @error('stock_quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">Trọng lượng (kg)</label>
                            <input type="number" id="weight" name="weight" value="{{ old('weight') }}" min="0" step="0.01"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('weight') border-red-500 @enderror">
                            @error('weight')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="manage_stock" value="1" {{ old('manage_stock') ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-900">Quản lý số lượng tồn kho</span>
                        </label>
                    </div>
                </div>

                <!-- Featured Image -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Ảnh đại diện</h3>
                    
                    <div>
                        <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">Ảnh đại diện</label>
                        <input type="file" id="featured_image" name="featured_image" accept="image/*"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('featured_image') border-red-500 @enderror">
                        @error('featured_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Chọn ảnh chính cho sản phẩm (JPEG, PNG, JPG, GIF - Tối đa 2MB)</p>
                    </div>

                    <div id="featured-image-preview" class="mt-4 max-w-sm"></div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Category & Status -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Danh mục & Trạng thái</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Danh mục <span class="text-red-500">*</span></label>
                            <select id="category_id" name="category_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category_id') border-red-500 @enderror">
                                <option value="">Chọn danh mục</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : 'checked' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-900">Kích hoạt sản phẩm</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="space-y-4">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                            <x-heroicon-o-check class="w-5 h-5 mr-2" />
                            Tạo sản phẩm
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                            <x-heroicon-o-x-mark class="w-5 h-5 mr-2" />
                            Hủy bỏ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate SKU based on product name
    const nameInput = document.getElementById('name');
    const skuField = document.getElementById('sku');
    const form = document.querySelector('form');
    
    if (nameInput && skuField) {
        nameInput.addEventListener('input', function(e) {
            const name = e.target.value;
            
            if (name && name.trim() !== '') {
                // Generate SKU from product name
                const words = name.trim().split(/\s+/);
                const initials = words.map(word => {
                    // Remove special characters and get first letter
                    const cleaned = word.replace(/[^a-zA-Z0-9\u00C0-\u1EF9]/g, '');
                    return cleaned.charAt(0).toUpperCase();
                }).join('');
                
                // Add timestamp to ensure uniqueness
                const timestamp = Date.now().toString().slice(-6);
                const sku = initials + timestamp;
                
                // Only auto-fill if SKU is empty
                if (!skuField.value.trim()) {
                    skuField.value = sku;
                }
            }
        });
    }

    // Ensure SKU is filled before form submission
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!skuField.value.trim()) {
                e.preventDefault();
                
                // Generate SKU from name if name exists
                if (nameInput.value.trim()) {
                    const words = nameInput.value.trim().split(/\s+/);
                    const initials = words.map(word => {
                        const cleaned = word.replace(/[^a-zA-Z0-9\u00C0-\u1EF9]/g, '');
                        return cleaned.charAt(0).toUpperCase();
                    }).join('');
                    
                    const timestamp = Date.now().toString().slice(-6);
                    const sku = initials + timestamp;
                    skuField.value = sku;
                    
                    // Submit form again after generating SKU
                    this.submit();
                } else {
                    alert('Vui lòng nhập tên sản phẩm trước!');
                }
            }
        });
    }

    // Preview new featured image
    const featuredImageInput = document.getElementById('featured_image');
    const preview = document.getElementById('featured-image-preview');
    
    if (featuredImageInput && preview) {
        featuredImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `
                        <div class="relative">
                            <img src="${e.target.result}" class="w-full h-48 object-cover rounded-lg">
                            <div class="absolute top-2 left-2 bg-blue-600 text-white px-2 py-1 text-xs rounded">
                                Ảnh đại diện
                            </div>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
            }
        });
    }
});
</script>
@endpush 