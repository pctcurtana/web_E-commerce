@extends('admin.layouts.app')

@section('title', 'Chi tiết Khách hàng')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Chi tiết Khách hàng</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                <x-heroicon-o-pencil class="w-5 h-5 mr-2" />
                Chỉnh sửa
            </a>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                Quay lại
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Thông tin cơ bản</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-xl font-medium">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h2>
                            <p class="text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">ID Người dùng</label>
                            <p class="mt-1 text-sm text-gray-900">#{{ $user->id }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Ngày đăng ký</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Cập nhật cuối</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Reviews -->
            @if($user->reviews->count() > 0)
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Đánh giá gần đây</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($user->reviews->take(5) as $review)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center space-x-2">
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <x-heroicon-s-star class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" />
                                        @endfor
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $review->rating }}/5</span>
                                </div>
                                <span class="text-xs text-gray-500">{{ $review->created_at->format('d/m/Y') }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">{{ $review->title ?? 'Không có tiêu đề' }}</p>
                            <p class="text-sm text-gray-500">{{ Str::limit($review->comment, 100) }}</p>
                            <p class="text-xs text-blue-600 mt-2">{{ $review->product->name ?? 'Sản phẩm đã bị xóa' }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Statistics -->
        <div class="space-y-6">
            <!-- Stats Cards -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Thống kê</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <x-heroicon-o-star class="w-5 h-5 text-blue-600" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Tổng đánh giá</p>
                                <p class="text-xs text-gray-500">Số lượng đánh giá đã viết</p>
                            </div>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">{{ number_format($stats['total_reviews']) }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <x-heroicon-o-star class="w-5 h-5 text-yellow-600" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Điểm TB</p>
                                <p class="text-xs text-gray-500">Điểm đánh giá trung bình</p>
                            </div>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">{{ number_format($stats['average_rating_given'], 1) }}/5</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <x-heroicon-o-shopping-bag class="w-5 h-5 text-green-600" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Đơn hàng</p>
                                <p class="text-xs text-gray-500">Tổng số đơn đã đặt</p>
                            </div>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">{{ number_format($stats['total_orders']) }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <x-heroicon-o-banknotes class="w-5 h-5 text-purple-600" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Tổng chi tiêu</p>
                                <p class="text-xs text-gray-500">Từ đơn hàng hoàn thành</p>
                            </div>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">{{ number_format($stats['total_spent']) }}đ</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Thao tác</h3>
                </div>
                <div class="p-6 space-y-3">
                    @if(!$user->orders()->exists())
                    <button onclick="deleteUser({{ $user->id }})"
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                        Xóa khách hàng
                    </button>
                    @else
                    <p class="text-sm text-gray-500 text-center py-2">
                        Không thể xóa vì khách hàng có đơn hàng
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form (hidden) -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function deleteUser(id) {
    Swal.fire({
        title: 'Bạn có chắc chắn?',
        text: "Khách hàng sẽ bị xóa vĩnh viễn!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Có, xóa!',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('delete-form');
            form.action = `{{ url('admin/users') }}/${id}`;
            form.submit();
        }
    });
}


</script>
@endpush 