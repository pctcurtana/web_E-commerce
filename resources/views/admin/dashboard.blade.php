@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Products -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <x-heroicon-o-cube class="w-5 h-5 text-white" />
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-500">Tổng sản phẩm</div>
                    <div class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_products']) }}</div>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-sm text-green-600">{{ number_format($stats['active_products']) }} đang hoạt động</span>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <x-heroicon-o-users class="w-5 h-5 text-white" />
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-500">Người dùng</div>
                    <div class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_users']) }}</div>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-sm text-gray-500">Khách hàng đã đăng ký</span>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                        <x-heroicon-o-shopping-bag class="w-5 h-5 text-white" />
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-500">Tổng đơn hàng</div>
                    <div class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_orders']) }}</div>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-sm text-green-600">{{ number_format($stats['delivered_orders']) }} đã giao</span>
            </div>
        </div>

        <!-- Total Reviews -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                        <x-heroicon-o-star class="w-5 h-5 text-white" />
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-500">Đánh giá</div>
                    <div class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_reviews']) }}</div>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-sm text-green-600">{{ number_format($stats['recent_reviews']) }} tuần này</span>
                <span class="text-sm text-gray-500 ml-2">• TB: {{ number_format($stats['avg_rating'], 1) }}/5</span>
            </div>
        </div>
    </div>

    <!-- Revenue Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                        <x-heroicon-o-banknotes class="w-5 h-5 text-white" />
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-500">Tổng doanh thu</div>
                    <div class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_revenue']) }}đ</div>
                </div>
            </div>
            <div class="mt-2 space-y-1">
                <div class="text-xs text-gray-600">
                    <span class="inline-flex items-center">
                        <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span>
                        Lịch sử: {{ number_format($stats['historical_revenue']) }}đ
                    </span>
                </div>
                <div class="text-xs text-gray-600">
                    <span class="inline-flex items-center">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                        Hệ thống mới: {{ number_format($stats['new_system_revenue']) }}đ
                    </span>
                </div>
            </div>
        </div>

        <!-- Today Revenue -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <x-heroicon-o-calendar class="w-5 h-5 text-white" />
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-500">Doanh thu hôm nay</div>
                    <div class="text-2xl font-semibold text-gray-900">{{ number_format($stats['today_revenue']) }}đ</div>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-sm text-gray-500">{{ now()->format('d/m/Y') }}</span>
                <span class="text-xs text-gray-400 block">Chỉ từ hệ thống mới</span>
            </div>
        </div>

        <!-- This Month Revenue -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-indigo-500 rounded-md flex items-center justify-center">
                        <x-heroicon-o-chart-bar class="w-5 h-5 text-white" />
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-500">Doanh thu tháng này</div>
                    <div class="text-2xl font-semibold text-gray-900">{{ number_format($stats['this_month_revenue']) }}đ</div>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-sm text-gray-500">{{ now()->format('m/Y') }}</span>
                <span class="text-xs text-gray-400 block">Chỉ từ hệ thống mới</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Products -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Sản phẩm mới nhất</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentProducts as $product)
                        <div class="flex items-center space-x-4">
                            <img src="{{ $product->featured_image_url ?: asset('images/products/default-product.jpg') }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-12 h-12 rounded-lg object-cover">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</p>
                                <p class="text-sm text-gray-500">{{ $product->category->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ number_format($product->final_price) }}đ</p>
                                <p class="text-xs text-gray-500">{{ $product->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Chưa có sản phẩm nào</p>
                    @endforelse
                </div>
                @if($recentProducts->count() > 0)
                    <div class="mt-4">
                        <a href="{{ route('admin.products.index') }}" 
                           class="text-sm text-blue-600 hover:text-blue-500">
                            Xem tất cả sản phẩm →
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Top Selling Products -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Sản phẩm bán chạy</h3>
                <p class="text-sm text-gray-500">Tổng hợp: lịch sử + hệ thống mới</p>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($topProducts as $product)
                        <div class="flex items-center space-x-4">
                            <img src="{{ $product->featured_image_url ?: asset('images/products/default-product.jpg') }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-12 h-12 rounded-lg object-cover">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</p>
                                <p class="text-sm text-gray-500">{{ $product->category->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ number_format($product->total_sold ?? 0) }} đã bán</p>
                                <p class="text-xs text-gray-500">{{ number_format($product->final_price) }}đ</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Chưa có dữ liệu bán hàng</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Người dùng mới</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentUsers as $user)
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-gray-700">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Chưa có người dùng mới</p>
                    @endforelse
                </div>
                @if($recentUsers->count() > 0)
                    <div class="mt-4">
                        <a href="{{ route('admin.users.index') }}" 
                           class="text-sm text-blue-600 hover:text-blue-500">
                            Xem tất cả người dùng →
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Đơn hàng mới nhất</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentOrders as $order)
                        <div class="flex items-center space-x-4">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</p>
                                <p class="text-sm text-gray-500">{{ $order->user->name ?? 'Khách vãng lai' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ number_format($order->total_amount) }}đ</p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
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
                                            Đang giao
                                            @break
                                        @case('delivered')
                                            Đã giao
                                            @break
                                        @case('cancelled')
                                            Đã hủy
                                            @break
                                    @endswitch
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Chưa có đơn hàng nào</p>
                    @endforelse
                </div>
                @if($recentOrders->count() > 0)
                    <div class="mt-4">
                        <a href="{{ route('admin.orders.index') }}" 
                           class="text-sm text-blue-600 hover:text-blue-500">
                            Xem tất cả đơn hàng →
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 