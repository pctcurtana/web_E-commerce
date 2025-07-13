@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Giỏ hàng của bạn</h1>
        <p class="text-gray-600 mt-2">Kiểm tra và chỉnh sửa đơn hàng trước khi thanh toán</p>
    </div>

    @if($cartItems && $cartItems->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">
                            Sản phẩm ({{ $cartItems->sum('quantity') }} món)
                        </h2>
                    </div>
                    
                    <div class="divide-y divide-gray-200">
                        @foreach($cartItems as $item)
                            <div class="p-6">
                                <div class="flex items-center space-x-4">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        <img src="{{ $item->product->featured_image_url ?: asset('images/products/default-product.jpg') }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="w-20 h-20 object-cover rounded-lg">
                                    </div>
                                    
                                    <!-- Product Info -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-medium text-gray-900">
                                            <a href="{{ route('products.show', $item->product->slug) }}" class="hover:text-red-600">
                                                {{ $item->product->name }}
                                            </a>
                                        </h3>
                                        <p class="text-sm text-gray-500">{{ $item->product->category->name }}</p>
                                        <div class="mt-2 flex items-center space-x-4">
                                            <span class="text-lg font-bold text-red-600">
                                                {{ number_format($item->product->final_price) }}đ
                                            </span>
                                            @if($item->product->sale_price)
                                                <span class="text-sm text-gray-400 line-through">
                                                    {{ number_format($item->product->price) }}đ
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Quantity Controls -->
                                    <div class="flex items-center space-x-3">
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center border border-gray-300 rounded-lg">
                                            @csrf
                                            @method('PATCH')
                                            <button type="button" onclick="decreaseQuantity({{ $item->id }})" class="px-3 py-2 hover:bg-gray-100 rounded-l-lg">
                                                <x-heroicon-o-minus class="w-4 h-4" />
                                            </button>
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock_quantity }}"
                                                   class="w-16 text-center border-0 focus:ring-0 focus:outline-none py-2" 
                                                   onchange="updateQuantity({{ $item->id }}, this.value)">
                                            <button type="button" onclick="increaseQuantity({{ $item->id }})" class="px-3 py-2 hover:bg-gray-100 rounded-r-lg">
                                                <x-heroicon-o-plus class="w-4 h-4" />
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <!-- Item Total -->
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-gray-900">
                                            {{ number_format($item->total) }}đ
                                        </p>
                                    </div>
                                    
                                    <!-- Remove Button -->
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 p-2" 
                                                onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                            <x-heroicon-o-trash class="w-5 h-5" />
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Cart Actions -->
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex justify-between">
                            <form action="{{ route('cart.clear') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium"
                                        onclick="return confirm('Bạn có chắc muốn xóa tất cả sản phẩm?')">
                                    <x-heroicon-o-trash class="w-4 h-4 inline mr-2" />
                                    Xóa tất cả
                                </button>
                            </form>
                            <a href="{{ route('products.index') }}" class="text-red-600 hover:text-red-800 font-medium">
                                <x-heroicon-o-arrow-left class="w-4 h-4 inline mr-2" />
                                Tiếp tục mua hàng
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-20">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tóm tắt đơn hàng</h3>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tạm tính ({{ $cartItems->sum('quantity') }} sản phẩm)</span>
                            <span class="font-medium">{{ number_format($total) }}đ</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Phí vận chuyển</span>
                            <span class="font-medium">30.000đ</span>
                        </div>
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between">
                                <span class="text-lg font-semibold text-gray-900">Tổng cộng</span>
                                <span class="text-lg font-bold text-red-600">{{ number_format($total + 30000) }}đ</span>
                            </div>
                        </div>
                    </div>
                    
                    @auth
                        <a href="{{ route('orders.checkout') }}" 
                           class="w-full bg-red-600 text-white py-3 px-4 rounded-lg hover:bg-red-700 transition-colors font-medium text-center block">
                            Tiến hành thanh toán
                        </a>
                    @else
                        <div class="space-y-3">
                            <a href="{{ route('login') }}" 
                               class="w-full bg-red-600 text-white py-3 px-4 rounded-lg hover:bg-red-700 transition-colors font-medium text-center block">
                                Đăng nhập để thanh toán
                            </a>
                            <a href="{{ route('register') }}" 
                               class="w-full bg-white border border-red-600 text-red-600 py-3 px-4 rounded-lg hover:bg-red-50 transition-colors font-medium text-center block">
                                Đăng ký tài khoản
                            </a>
                        </div>
                    @endauth
                    
                    <!-- Security Badges -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-center space-x-4 text-sm text-gray-500">
                            <div class="flex items-center">
                                <x-heroicon-o-shield-check class="w-4 h-4 mr-1" />
                                <span>Bảo mật</span>
                            </div>
                            <div class="flex items-center">
                                <x-heroicon-o-truck class="w-4 h-4 mr-1" />
                                <span>Giao hàng</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="text-center py-16">
            <div class="max-w-md mx-auto">
                <x-heroicon-o-shopping-cart class="w-24 h-24 mx-auto text-gray-300 mb-6" />
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Giỏ hàng trống</h2>
                <p class="text-gray-600 mb-8">
                    Bạn chưa có sản phẩm nào trong giỏ hàng. Hãy khám phá và thêm sản phẩm yêu thích!
                </p>
                <div class="space-y-4">
                    <a href="{{ route('products.index') }}" 
                       class="bg-red-600 text-white px-8 py-3 rounded-lg hover:bg-red-700 transition-colors font-medium inline-block">
                        <x-heroicon-o-cube class="w-5 h-5 inline mr-2" />
                        Khám phá sản phẩm
                    </a>
                    <div class="text-center">
                        <a href="{{ route('home') }}" class="text-red-600 hover:text-red-700 font-medium">
                            Về trang chủ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    function updateQuantity(itemId, quantity) {
        if (quantity < 1) return;
        
        const form = document.querySelector(`form[action*="cart/${itemId}"]`);
        if (form) {
            form.submit();
        }
    }
    
    function increaseQuantity(itemId) {
        const input = document.querySelector(`input[onchange*="${itemId}"]`);
        if (input) {
            const newValue = parseInt(input.value) + 1;
            const max = parseInt(input.getAttribute('max'));
            if (newValue <= max) {
                input.value = newValue;
                updateQuantity(itemId, newValue);
            }
        }
    }
    
    function decreaseQuantity(itemId) {
        const input = document.querySelector(`input[onchange*="${itemId}"]`);
        if (input) {
            const newValue = parseInt(input.value) - 1;
            if (newValue >= 1) {
                input.value = newValue;
                updateQuantity(itemId, newValue);
            }
        }
    }
</script>
@endsection 