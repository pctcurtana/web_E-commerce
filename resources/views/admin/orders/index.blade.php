@extends('admin.layouts.app')

@section('title', 'Quản lý Đơn hàng')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Quản lý Đơn hàng</h1>
        <a href="{{ route('admin.orders.export', request()->query()) }}" 
           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
            <x-heroicon-o-arrow-down-tray class="w-5 h-5 mr-2"/>
            Xuất Excel
        </a>
    </div>

    <!-- Statistics -->
    <div class="space-y-4 mb-6">
        <!-- Hàng đầu: Thống kê trạng thái đơn hàng -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <x-heroicon-o-shopping-bag class="w-6 h-6 text-blue-600"/>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">Tổng đơn hàng</p>
                        <p class="text-lg font-semibold text-gray-900">{{ number_format($stats['total']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <x-heroicon-o-clock class="w-6 h-6 text-yellow-600"/>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">Chờ xử lý</p>
                        <p class="text-lg font-semibold text-gray-900">{{ number_format($stats['pending']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <x-heroicon-o-cog-6-tooth class="w-6 h-6 text-blue-600"/>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">Đang xử lý</p>
                        <p class="text-lg font-semibold text-gray-900">{{ number_format($stats['processing']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <x-heroicon-o-truck class="w-6 h-6 text-purple-600"/>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">Đang giao hàng</p>
                        <p class="text-lg font-semibold text-gray-900">{{ number_format($stats['shipped']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hàng thứ hai: Thống kê kết quả và doanh thu -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <x-heroicon-o-check-circle class="w-6 h-6 text-green-600"/>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">Hoàn thành</p>
                        <p class="text-lg font-semibold text-gray-900">{{ number_format($stats['delivered']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <x-heroicon-o-x-circle class="w-6 h-6 text-red-600"/>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">Đã hủy</p>
                        <p class="text-lg font-semibold text-gray-900">{{ number_format($stats['cancelled']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-indigo-100 rounded-lg">
                        <x-heroicon-o-calendar class="w-6 h-6 text-indigo-600"/>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">Đơn hàng hôm nay</p>
                        <p class="text-lg font-semibold text-gray-900">{{ number_format($stats['today_orders']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-emerald-100 rounded-lg">
                        <x-heroicon-o-banknotes class="w-6 h-6 text-emerald-600"/>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">Doanh thu hôm nay</p>
                        <p class="text-lg font-semibold text-gray-900">{{ number_format($stats['today_revenue']) }}đ</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-4 border-b border-gray-200">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Tìm theo mã đơn, tên khách hàng..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Status Filter -->
                <div>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đang vận chuyển</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <input type="date" 
                           name="date_from" 
                           value="{{ request('date_from') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Date To -->
                <div>
                    <input type="date" 
                           name="date_to" 
                           value="{{ request('date_to') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Submit and Reset -->
                <div class="md:col-span-2 lg:col-span-4 flex space-x-2">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center">
                        <x-heroicon-o-magnifying-glass class="w-4 h-4 mr-2"/>
                        Tìm kiếm
                    </button>
                    <a href="{{ route('admin.orders.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã đơn hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số sản phẩm</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đặt</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</div>
                                <div class="text-sm text-gray-500">{{ $order->payment_method }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $order->user->name ?? 'Khách vãng lai' }}</div>
                                <div class="text-sm text-gray-500">{{ $order->user->email ?? $order->billing_address['email'] ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
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
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                        @if($order->payment_status === 'pending') bg-gray-100 text-gray-800
                                        @elseif($order->payment_status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->payment_status === 'failed') bg-red-100 text-red-800
                                        @elseif($order->payment_status === 'refunded') bg-orange-100 text-orange-800
                                        @endif">
                                        @switch($order->payment_status)
                                            @case('pending')
                                                Chờ thanh toán
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
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ $order->order_items_count }} sản phẩm</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($order->total_amount) }}đ</div>
                                @if($order->shipping_amount > 0)
                                    <div class="text-sm text-gray-500">Phí ship: {{ number_format($order->shipping_amount) }}đ</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $order->created_at->format('d/m/Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $order->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <x-heroicon-o-eye class="w-4 h-4"/>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                <div class="flex flex-col items-center py-8">
                                    <x-heroicon-o-shopping-bag class="w-12 h-12 text-gray-400 mb-4"/>
                                    <p class="text-lg font-medium text-gray-900 mb-2">Không có đơn hàng nào</p>
                                    <p class="text-gray-500">Không tìm thấy đơn hàng phù hợp với bộ lọc</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $orders->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection 