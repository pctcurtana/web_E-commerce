@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->order_number)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm text-gray-500">
            <li><a href="{{ route('home') }}" class="hover:text-red-600">Trang chủ</a></li>
            <li><x-heroicon-o-chevron-right class="w-4 h-4" /></li>
            <li><a href="{{ route('orders.index') }}" class="hover:text-red-600">Đơn hàng</a></li>
            <li><x-heroicon-o-chevron-right class="w-4 h-4" /></li>
            <li class="text-gray-900 font-medium">#{{ $order->order_number }}</li>
        </ol>
    </nav>

    <!-- Order Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Đơn hàng #{{ $order->order_number }}</h1>
                <p class="text-gray-500">Đặt ngày {{ $order->created_at->format('d/m/Y lúc H:i') }}</p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="px-4 py-2 rounded-full text-sm font-medium inline-block
                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                    @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                    @elseif($order->status === 'delivered') bg-green-100 text-green-800
                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                    @endif">
                    @switch($order->status)
                        @case('pending')
                            Chờ xác nhận
                            @break
                        @case('processing')
                            Đang xử lý
                            @break
                        @case('shipped')
                            Đang vận chuyển
                            @break
                        @case('delivered')
                            Đã giao hàng
                            @break
                        @case('cancelled')
                            Đã hủy
                            @break
                        @default
                            {{ ucfirst($order->status) }}
                    @endswitch
                </div>
            </div>
        </div>

        <!-- Order Progress -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full {{ $order->status !== 'cancelled' ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center">
                        <x-heroicon-s-check class="w-4 h-4 text-white" />
                    </div>
                    <span class="text-xs mt-2 text-center">Đặt hàng</span>
                </div>
                <div class="flex-1 h-1 mx-2 {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center">
                        <x-heroicon-s-check class="w-4 h-4 text-white" />
                    </div>
                    <span class="text-xs mt-2 text-center">Xác nhận</span>
                </div>
                <div class="flex-1 h-1 mx-2 {{ in_array($order->status, ['shipped', 'delivered']) ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full {{ in_array($order->status, ['shipped', 'delivered']) ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center">
                        <x-heroicon-s-truck class="w-4 h-4 text-white" />
                    </div>
                    <span class="text-xs mt-2 text-center">Vận chuyển</span>
                </div>
                <div class="flex-1 h-1 mx-2 {{ $order->status === 'delivered' ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full {{ $order->status === 'delivered' ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center">
                        <x-heroicon-s-home class="w-4 h-4 text-white" />
                    </div>
                    <span class="text-xs mt-2 text-center">Giao hàng</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Items -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Sản phẩm đã đặt</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        @foreach($order->orderItems as $item)
                            <div class="flex items-start space-x-4">
                                <img src="{{ $item->product->featured_image_url ?: asset('images/products/default-product.jpg') }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="w-20 h-20 object-cover rounded-lg">
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900 mb-1">{{ $item->product->name }}</h3>
                                    <p class="text-sm text-gray-500 mb-2">{{ $item->product->category->name }}</p>
                                    <div class="flex items-center justify-between">
                                        <div class="text-sm text-gray-600">
                                            <span>Số lượng: {{ $item->quantity }}</span>
                                            <span class="mx-2">×</span>
                                            <span>{{ number_format($item->price) }}đ</span>
                                        </div>
                                        <div class="font-medium text-gray-900">
                                            {{ number_format($item->total) }}đ
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary & Info -->
        <div class="space-y-6">
            <!-- Order Total -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tóm tắt đơn hàng</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Tạm tính:</span>
                        <span>{{ number_format($order->total_amount - $order->shipping_amount - $order->tax_amount + $order->discount_amount) }}đ</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Phí vận chuyển:</span>
                        <span>{{ number_format($order->shipping_amount) }}đ</span>
                    </div>
                    @if($order->tax_amount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Thuế:</span>
                            <span>{{ number_format($order->tax_amount) }}đ</span>
                        </div>
                    @endif
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Giảm giá:</span>
                            <span>-{{ number_format($order->discount_amount) }}đ</span>
                        </div>
                    @endif
                    <div class="border-t border-gray-200 pt-3">
                        <div class="flex justify-between font-semibold text-lg">
                            <span>Tổng cộng:</span>
                            <span class="text-red-600">{{ number_format($order->total_amount) }}đ</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Địa chỉ giao hàng</h3>
                <div class="text-sm text-gray-600 space-y-1">
                    <p class="font-medium text-gray-900">{{ $order->billing_address['name'] ?? 'N/A' }}</p>
                    <p>{{ $order->billing_address['phone'] ?? 'N/A' }}</p>
                    <p>{{ $order->billing_address['email'] ?? 'N/A' }}</p>
                    <p>{{ $order->billing_address['address'] ?? 'N/A' }}</p>
                    <p>{{ $order->billing_address['ward'] ?? 'N/A' }}, {{ $order->billing_address['district'] ?? 'N/A' }}, {{ $order->billing_address['city'] ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin thanh toán</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Phương thức:</span>
                        <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Trạng thái:</span>
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            @if($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->payment_status === 'completed') bg-green-100 text-green-800
                            @elseif($order->payment_status === 'refunded') bg-purple-100 text-purple-800
                            @else bg-red-100 text-red-800
                            @endif">
                            @switch($order->payment_status)
                                @case('pending')
                                    @if($order->payment_method === 'cod')
                                        Thanh toán khi nhận hàng
                                    @else
                                        Chờ thanh toán
                                    @endif
                                    @break
                                @case('completed')
                                    Đã thanh toán
                                    @break
                                @case('failed')
                                    Thanh toán thất bại
                                    @break
                                @case('refunded')
                                    Đã hoàn tiền
                                    @break
                                @default
                                    {{ ucfirst($order->payment_status) }}
                            @endswitch
                        </span>
                    </div>
                </div>
            </div>

            <!-- Order Notes -->
            @if($order->notes)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Ghi chú</h3>
                    <p class="text-sm text-gray-600">{{ $order->notes }}</p>
                </div>
            @endif

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="space-y-3">
                    @if($order->status === 'pending')
                        <button onclick="cancelOrder('{{ $order->id }}')" 
                                class="w-full px-4 py-2 text-sm font-medium text-red-600 bg-white border border-red-300 rounded-md hover:bg-red-50">
                            Hủy đơn hàng
                        </button>
                    @endif
                    
                    @if($order->status === 'delivered')
                        <!-- <button class="w-full px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                            Mua lại
                        </button> -->
                        <button class="w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Đánh giá sản phẩm
                        </button>
                    @endif
                    
                    <a href="{{ route('orders.index') }}" 
                       class="w-full inline-block text-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function cancelOrder(orderId) {
        Swal.fire({
            title: 'Xác nhận hủy đơn hàng',
            text: 'Bạn có chắc chắn muốn hủy đơn hàng này? Hành động này không thể hoàn tác.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Hủy đơn',
            cancelButtonText: 'Không'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/orders/${orderId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('success', 'Đã hủy đơn hàng thành công');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showNotification('error', data.message || 'Có lỗi xảy ra khi hủy đơn hàng');
                    }
                })
                .catch(error => {
                    showNotification('error', 'Có lỗi xảy ra khi hủy đơn hàng');
                });
            }
        });
    }
</script>
@endsection 