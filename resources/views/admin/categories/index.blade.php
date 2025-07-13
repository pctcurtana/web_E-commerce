@extends('admin.layouts.app')

@section('title', 'Quản lý Danh mục')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Quản lý Danh mục</h1>
        <a href="{{ route('admin.categories.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
            <x-heroicon-o-plus class="w-5 h-5 mr-2"/>
            Thêm danh mục
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-4 border-b border-gray-200">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Tìm kiếm danh mục..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tất cả trạng thái</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Không hoạt động</option>
                    </select>
                </div>
                <div class="flex space-x-2">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center">
                        <x-heroicon-o-magnifying-glass class="w-4 h-4 mr-2"/>
                        Tìm kiếm
                    </button>
                    <a href="{{ route('admin.categories.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Icon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên danh mục</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <x-heroicon-o-squares-2x2 class="w-6 h-6 text-gray-600" />
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                @if($category->description)
                                    <div class="text-sm text-gray-500 truncate max-w-xs">{{ $category->description }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <code class="text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded">{{ $category->slug }}</code>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $category->products_count }} sản phẩm
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
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
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.categories.show', $category) }}" 
                                       class="text-blue-600 hover:text-blue-900">
                                        <x-heroicon-o-eye class="w-4 h-4"/>
                                    </a>
                                    <a href="{{ route('admin.categories.edit', $category) }}" 
                                       class="text-yellow-600 hover:text-yellow-900">
                                        <x-heroicon-o-pencil class="w-4 h-4"/>
                                    </a>
                                    @if($category->products_count == 0)
                                        <form action="{{ route('admin.categories.destroy', $category) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <x-heroicon-o-trash class="w-4 h-4"/>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400" title="Không thể xóa danh mục có sản phẩm">
                                            <x-heroicon-o-trash class="w-4 h-4"/>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                <div class="flex flex-col items-center py-8">
                                    <x-heroicon-o-folder class="w-12 h-12 text-gray-400 mb-4"/>
                                    <p class="text-lg font-medium text-gray-900 mb-2">Không có danh mục nào</p>
                                    <p class="text-gray-500 mb-4">Bắt đầu bằng cách tạo danh mục đầu tiên</p>
                                    <a href="{{ route('admin.categories.create') }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                        Thêm danh mục
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $categories->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection 