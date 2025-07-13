@extends('admin.layouts.app')

@section('title', 'Chi tiết Đơn hàng #' . $order->order_number)

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <a href="{{ route('admin.orders.index') }}" 
               class="text-gray-500 hover:text-gray-700 mr-4">
                <x-heroicon-o-arrow-left class="w-6 h-6"/>
            </a>
            <h1 class="text-2xl font-semibold text-gray-900">Đơn hàng #{{ $order->order_number }}</h1>
        </div>
        <div class="flex space-x-3">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
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
                @endswitch
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details & Items -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Sản phẩm đã đặt ({{ $order->orderItems->count() }})</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($order->orderItems as $item)
                            <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                                <img src="{{ $item->product->featured_image_url ?: asset('images/products/default-product.jpg') }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="w-16 h-16 object-cover rounded-lg">
                                
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-medium text-gray-900">{{ $item->product->name }}</h3>
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
            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Thông tin khách hàng</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Account Info -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Tài khoản</h3>
                            @if($order->user)
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-900">{{ $order->user->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $order->user->email }}</p>
                                    <p class="text-sm text-gray-500">Thành viên từ: {{ $order->user->created_at->format('d/m/Y') }}</p>
                                </div>
                            @else
                                <p class="text-sm text-gray-500">Khách vãng lai</p>
                            @endif
                        </div>

                        <!-- Billing Address -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Thông tin thanh toán</h3>
                            <div class="space-y-1 text-sm text-gray-900">
                                <p>{{ $order->billing_address['name'] ?? '' }}</p>
                                <p>{{ $order->billing_address['phone'] ?? '' }}</p>
                                <p>{{ $order->billing_address['email'] ?? '' }}</p>
                                <p>{{ $order->billing_address['address'] ?? '' }}</p>
                                <p>{{ $order->billing_address['ward'] ?? '' }}, {{ $order->billing_address['district'] ?? '' }}</p>
                                <p>{{ $order->billing_address['city'] ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary & Actions -->
        <div class="space-y-6">
            <!-- Status Management -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Quản lý trạng thái</h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Update Order Status -->
                    <form id="statusForm" data-order-id="{{ $order->id }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Trạng thái đơn hàng</label>
                                <select id="status" name="status" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @if($order->status === 'pending')
                                        <option value="pending" selected>Chờ xác nhận</option>
                                        <option value="processing">Đang xử lý</option>
                                        <option value="cancelled">Hủy đơn</option>
                                    @elseif($order->status === 'processing')
                                        <option value="processing" selected>Đang xử lý</option>
                                        <option value="shipped">Đang vận chuyển</option>
                                        <option value="cancelled">Hủy đơn</option>
                                    @elseif($order->status === 'shipped')
                                        <option value="shipped" selected>Đang vận chuyển</option>
                                        <option value="delivered">Đã giao hàng</option>
                                    @else
                                        <option value="{{ $order->status }}" selected>
                                            @switch($order->status)
                                                @case('delivered') Đã giao hàng @break
                                                @case('cancelled') Đã hủy @break
                                            @endswitch
                                        </option>
                                    @endif
                                </select>
                            </div>
                            
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Ghi chú</label>
                                <textarea id="notes" name="notes" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                          placeholder="Thêm ghi chú về việc cập nhật trạng thái..."></textarea>
                            </div>

                            @if(!in_array($order->status, ['delivered', 'cancelled']))
                                <button type="submit" 
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                    <span class="btn-text">Cập nhật trạng thái</span>
                                    <span class="btn-loading hidden">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Đang cập nhật...
                                    </span>
                                </button>
                            @endif
                        </div>
                    </form>

                    <!-- Payment Status (Read-only) -->
                    <hr class="my-4">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái thanh toán</label>
                            <div class="w-full px-3 py-2 border border-gray-200 rounded-md bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-900">
                                        @switch($order->payment_status)
                                            @case('pending')
                                                @if($order->payment_method === 'cod')
                                                    Chờ thanh toán (COD)
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
                                        @endswitch
                                    </span>
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        @if($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->payment_status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->payment_status === 'refunded') bg-purple-100 text-purple-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                <x-heroicon-o-lock-closed class="w-3 h-3 inline mr-1"/>
                                Tự động cập nhật theo trạng thái đơn hàng
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Tóm tắt đơn hàng</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Subtotal:</span>
                            <span class="text-sm text-gray-900">{{ number_format($order->total_amount - $order->shipping_amount - $order->tax_amount) }}đ</span>
                        </div>
                        
                        @if($order->shipping_amount > 0)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Phí vận chuyển:</span>
                                <span class="text-sm text-gray-900">{{ number_format($order->shipping_amount) }}đ</span>
                            </div>
                        @endif
                        
                        @if($order->tax_amount > 0)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Thuế:</span>
                                <span class="text-sm text-gray-900">{{ number_format($order->tax_amount) }}đ</span>
                            </div>
                        @endif
                        
                        @if($order->discount_amount > 0)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Giảm giá:</span>
                                <span class="text-sm text-green-600">-{{ number_format($order->discount_amount) }}đ</span>
                            </div>
                        @endif
                        
                        <hr>
                        <div class="flex justify-between">
                            <span class="text-base font-medium text-gray-900">Tổng cộng:</span>
                            <span class="text-base font-medium text-gray-900">{{ number_format($order->total_amount) }}đ</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phương thức thanh toán:</span>
                                <span class="text-gray-900">{{ $order->payment_method === 'cod' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Ngày đặt:</span>
                                <span class="text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @if($order->shipped_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Ngày giao hàng:</span>
                                    <span class="text-gray-900">{{ $order->shipped_at->format('d/m/Y H:i') }}</span>
                                </div>
                            @endif
                            @if($order->delivered_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Ngày hoàn thành:</span>
                                    <span class="text-gray-900">{{ $order->delivered_at->format('d/m/Y H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Notes -->
            @if($order->notes)
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Ghi chú</h2>
                    </div>
                    <div class="p-6">
                        <div class="text-sm text-gray-700 whitespace-pre-line">{{ $order->notes }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update Order Status
    document.getElementById('statusForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const orderId = form.dataset.orderId;
        const status = form.querySelector('#status').value;
        const notes = form.querySelector('#notes').value;
        const button = form.querySelector('button[type="submit"]');
        
        // Show loading state
        button.querySelector('.btn-text').classList.add('hidden');
        button.querySelector('.btn-loading').classList.remove('hidden');
        button.disabled = true;
        
        fetch(`/admin/orders/${orderId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                status: status,
                notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success notification
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
                
                // Reload page to update status display
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                throw new Error(data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: error.message || 'Có lỗi xảy ra khi cập nhật trạng thái',
                toast: true,
                position: 'top-end'
            });
        })
        .finally(() => {
            // Hide loading state
            button.querySelector('.btn-text').classList.remove('hidden');
            button.querySelector('.btn-loading').classList.add('hidden');
            button.disabled = false;
        });
    });
});
</script> 