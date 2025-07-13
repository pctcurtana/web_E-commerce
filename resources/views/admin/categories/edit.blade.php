@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa Danh mục')

@section('content')
<div class="p-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.categories.index') }}" 
           class="text-gray-500 hover:text-gray-700 mr-4">
            <x-heroicon-o-arrow-left class="w-6 h-6"/>
        </a>
        <h1 class="text-2xl font-semibold text-gray-900">Chỉnh sửa Danh mục: {{ $category->name }}</h1>
    </div>

    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="max-w-2xl mx-auto space-y-6">
                <!-- Category Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Tên danh mục <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $category->name) }}" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                           placeholder="Nhập tên danh mục">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug (readonly) -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                        Slug
                    </label>
                    <input type="text" 
                           id="slug" 
                           value="{{ $category->slug }}" 
                           readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600"
                           placeholder="Slug sẽ được tạo tự động">
                    <p class="mt-1 text-xs text-gray-500">Slug sẽ được tạo tự động khi bạn thay đổi tên danh mục</p>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Mô tả
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                              placeholder="Nhập mô tả danh mục">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1" 
                               {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Kích hoạt danh mục</span>
                    </label>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <a href="{{ route('admin.categories.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Hủy
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <x-heroicon-o-check class="w-4 h-4 inline mr-2"/>
                        Cập nhật danh mục
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection 