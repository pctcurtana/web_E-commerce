@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa Khách hàng')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Chỉnh sửa Khách hàng</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.users.show', $user) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <x-heroicon-o-eye class="w-5 h-5 mr-2" />
                Xem chi tiết
            </a>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                Quay lại
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                    <span class="text-white text-lg font-medium">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Chỉnh sửa thông tin: {{ $user->name }}</h3>
                    <p class="text-sm text-gray-500">ID: #{{ $user->id }} | {{ $user->email }}</p>
                </div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="p-6 space-y-6">
            @csrf
            @method('PATCH')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Họ và tên <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $user->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                           placeholder="Nhập họ và tên"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $user->email) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                           placeholder="Nhập địa chỉ email"
                           required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


            </div>

            <!-- Password Change Section -->
            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Thay đổi mật khẩu</h4>
                <p class="text-sm text-gray-500 mb-4">Để trống nếu không muốn thay đổi mật khẩu</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Mật khẩu mới
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                               placeholder="Nhập mật khẩu mới">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Mật khẩu phải có ít nhất 8 ký tự</p>
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Xác nhận mật khẩu mới
                        </label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Nhập lại mật khẩu mới">
                    </div>
                </div>
            </div>

            <!-- User Info Section -->
            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Thông tin bổ sung</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Ngày đăng ký</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Cập nhật cuối</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Tổng đánh giá</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->reviews()->count() }} đánh giá</p>
                    </div>
                </div>
            </div>



            @if($user->orders()->exists())
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <x-heroicon-o-information-circle class="w-5 h-5 text-blue-400 mr-3 mt-0.5" />
                    <div class="text-sm text-blue-700">
                        <p><strong>Thông tin:</strong> Khách hàng này có {{ $user->orders()->count() }} đơn hàng</p>
                        <p class="mt-1">Không thể xóa khách hàng có đơn hàng trong hệ thống</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.users.show', $user) }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg">
                    Hủy
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    Cập nhật thông tin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 