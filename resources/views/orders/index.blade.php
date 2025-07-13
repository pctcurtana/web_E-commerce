@extends('layouts.app')

@section('title', 'Lịch sử đơn hàng')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Lịch sử đơn hàng</h1>
        <p class="text-gray-600 mt-2">Quản lý và theo dõi các đơn hàng của bạn</p>
    </div>

    @if($orders->isEmpty())
        <div class="text-center py-12">
            <x-heroicon-o-shopping-bag class="w-16 h-16 text-gray-400 mx-auto mb-4" />
            <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có đơn hàng nào</h3>
            <p class="text-gray-500 mb-6">Bạn chưa có đơn hàng nào. Hãy bắt đầu mua sắm ngay!</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                <x-heroicon-o-shopping-cart class="w-5 h-5 mr-2" />
                Mua sắm ngay
            </a>
        </div>
    @else
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Order Header -->
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-center space-x-4">
                                <div>
                                    <h3 class="font-semibold text-gray-900">Đơn hàng #{{ $order->order_number }}</h3>
                                    <p class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="px-3 py-1 rounded-full text-xs font-medium
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
                            <div class="mt-3 sm:mt-0 text-right">
                                <p class="text-lg font-bold text-red-600">{{ number_format($order->total_amount) }}đ</p>
                                <p class="text-sm text-gray-500">{{ $order->orderItems->sum('quantity') }} sản phẩm</p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="px-6 py-4">
                        <div class="space-y-4">
                            @foreach($order->orderItems as $item)
                                <div class="flex items-center space-x-4">
                                    <img src="{{ $item->product->featured_image_url ?: asset('images/products/default-product.jpg') }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-16 h-16 object-cover rounded-lg">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-medium text-gray-900">{{ $item->product->name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $item->product->category->name }}</p>
                                        <div class="flex items-center space-x-4 text-sm text-gray-600 mt-1">
                                            <span>Số lượng: {{ $item->quantity }}</span>
                                            <span>Đơn giá: {{ number_format($item->price) }}đ</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium text-gray-900">{{ number_format($item->total) }}đ</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Shipping Address -->
                            <div>
                                <h5 class="font-medium text-gray-900 mb-2">Địa chỉ giao hàng</h5>
                                <div class="text-sm text-gray-600">
                                    <p class="font-medium">{{ $order->billing_address['name'] ?? 'N/A' }}</p>
                                    <p>{{ $order->billing_address['phone'] ?? 'N/A' }}</p>
                                    <p>{{ $order->billing_address['address'] ?? 'N/A' }}</p>
                                    <p>{{ $order->billing_address['ward'] ?? 'N/A' }}, {{ $order->billing_address['district'] ?? 'N/A' }}, {{ $order->billing_address['city'] ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <!-- Order Details -->
                            <div class="text-right">
                                <div class="space-y-1 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Tạm tính:</span>
                                        <span>{{ number_format($order->total_amount - $order->shipping_amount - $order->tax_amount + $order->discount_amount) }}đ</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Phí vận chuyển:</span>
                                        <span>{{ number_format($order->shipping_amount) }}đ</span>
                                    </div>
                                    @if($order->tax_amount > 0)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Thuế:</span>
                                            <span>{{ number_format($order->tax_amount) }}đ</span>
                                        </div>
                                    @endif
                                    @if($order->discount_amount > 0)
                                        <div class="flex justify-between text-green-600">
                                            <span>Giảm giá:</span>
                                            <span>-{{ number_format($order->discount_amount) }}đ</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between font-bold text-lg border-t border-gray-300 pt-1">
                                        <span>Tổng cộng:</span>
                                        <span class="text-red-600">{{ number_format($order->total_amount) }}đ</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Actions -->
                        <div class="mt-6 flex flex-col sm:flex-row sm:justify-between sm:items-center">
                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                <span class="flex items-center">
                                    <x-heroicon-o-credit-card class="w-4 h-4 mr-1" />
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                                </span>
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->payment_status === 'completed') bg-green-100 text-green-800
                                    @elseif($order->payment_status === 'failed') bg-red-100 text-red-800
                                    @elseif($order->payment_status === 'refunded') bg-purple-100 text-purple-800
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

                            <div class="mt-4 sm:mt-0 flex space-x-3">
                                @if($order->status === 'pending')
                                    <button onclick="cancelOrder('{{ $order->id }}')" 
                                            class="px-4 py-2 text-sm font-medium text-red-600 bg-white border border-red-300 rounded-md hover:bg-red-50">
                                        Hủy đơn
                                    </button>
                                @endif
                                
                                <!-- @if($order->status === 'delivered')
                                    <button class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                                        Mua lại
                                    </button>
                                @endif -->
                                
                                <a href="{{ route('orders.show', $order->id) }}" 
                                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @endif
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