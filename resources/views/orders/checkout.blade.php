@extends('layouts.app')

@section('title', 'Thanh toán')

@push('styles')
<style>
    /* QR Modal Animation */
    #qr-payment-modal {
        transition: opacity 0.3s ease;
    }
    
    #qr-payment-modal:not(.hidden) {
        opacity: 1;
    }
    
    #qr-payment-modal.hidden {
        opacity: 0;
    }
    
    /* Modal Content Animation */
    #qr-payment-modal .bg-white {
        transform: scale(0.95);
        transition: transform 0.3s ease;
    }
    
    #qr-payment-modal:not(.hidden) .bg-white {
        transform: scale(1);
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Thanh toán</h1>
        <p class="text-gray-600 mt-2">Vui lòng điền thông tin để hoàn tất đơn hàng</p>
    </div>

    <form action="{{ route('orders.store') }}" method="POST" id="checkout-form">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Billing Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Thông tin giao hàng</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="billing_name" class="block text-sm font-medium text-gray-700 mb-1">Họ và tên *</label>
                            <input type="text" id="billing_name" name="billing_name" value="{{ old('billing_name', Auth::user()->name) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            @error('billing_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="billing_phone" class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại *</label>
                            <input type="tel" id="billing_phone" name="billing_phone" value="{{ old('billing_phone') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            @error('billing_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="billing_email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" id="billing_email" name="billing_email" value="{{ old('billing_email', Auth::user()->email) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            @error('billing_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="billing_city" class="block text-sm font-medium text-gray-700 mb-1">Tỉnh/Thành phố *</label>
                            <select id="billing_city" name="billing_city" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                <option value="">Chọn tỉnh/thành phố</option>
                            </select>
                            @error('billing_city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="billing_district" class="block text-sm font-medium text-gray-700 mb-1">Quận/Huyện *</label>
                            <select id="billing_district" name="billing_district" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                <option value="">Chọn quận/huyện</option>
                            </select>
                            @error('billing_district')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="billing_ward" class="block text-sm font-medium text-gray-700 mb-1">Phường/Xã *</label>
                            <select id="billing_ward" name="billing_ward" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                <option value="">Chọn phường/xã</option>
                            </select>
                            @error('billing_ward')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ cụ thể *</label>
                            <textarea id="billing_address" name="billing_address" rows="3" required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                      placeholder="Số nhà, tên đường...">{{ old('billing_address') }}</textarea>
                            @error('billing_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Phương thức thanh toán</h2>
                    
                    <div class="space-y-4">
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="cod" 
                                   {{ old('payment_method', 'cod') == 'cod' ? 'checked' : '' }}
                                   class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300">
                            <div class="ml-3 flex items-center">
                                <x-heroicon-o-banknotes class="w-6 h-6 text-gray-600 mr-3" />
                                <div>
                                    <span class="font-medium text-gray-900">Thanh toán khi nhận hàng (COD)</span>
                                    <p class="text-sm text-gray-500">Thanh toán bằng tiền mặt khi nhận hàng</p>
                                </div>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="bank_transfer" 
                                   {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }}
                                   class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300">
                            <div class="ml-3 flex items-center">
                                <x-heroicon-o-credit-card class="w-6 h-6 text-gray-600 mr-3" />
                                <div>
                                    <span class="font-medium text-gray-900">Chuyển khoản ngân hàng</span>
                                    <p class="text-sm text-gray-500">Chuyển khoản qua các ngân hàng trong nước</p>
                                </div>
                            </div>
                        </label>
                    </div>
                    @error('payment_method')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Order Notes -->
                <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Ghi chú đơn hàng</h2>
                    <textarea name="notes" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                              placeholder="Ghi chú thêm cho đơn hàng (tùy chọn)">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-20">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Tóm tắt đơn hàng</h2>
                    
                    <!-- Cart Items -->
                    <div class="space-y-4 mb-6">
                        @foreach($cartItems as $item)
                            <div class="flex items-center space-x-3">
                                <img src="{{ $item->product->featured_image_url ?: asset('images/products/default-product.jpg') }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="w-12 h-12 object-cover rounded-lg">
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-medium text-gray-900 truncate">{{ $item->product->name }}</h4>
                                    <p class="text-sm text-gray-500">Số lượng: {{ $item->quantity }}</p>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ number_format($item->total) }}đ</span>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Order Totals -->
                    <div class="border-t border-gray-200 pt-6 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tạm tính</span>
                            <span class="font-medium">{{ number_format($subtotal) }}đ</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Phí vận chuyển</span>
                            <span class="font-medium">{{ number_format($shippingFee) }}đ</span>
                        </div>
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between">
                                <span class="text-lg font-semibold text-gray-900">Tổng cộng</span>
                                <span class="text-lg font-bold text-red-600">{{ number_format($total) }}đ</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Place Order Button -->
                    <button type="submit" 
                            class="w-full mt-6 bg-red-600 text-white py-3 px-4 rounded-lg hover:bg-red-700 transition-colors font-medium">
                        <x-heroicon-o-check-circle class="w-5 h-5 inline-block mr-2" />
                        Đặt hàng
                    </button>
                    
                    <!-- Security Info -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-center space-x-4 text-sm text-gray-500">
                            <div class="flex items-center">
                                <x-heroicon-o-shield-check class="w-4 h-4 mr-1" />
                                <span>Bảo mật SSL</span>
                            </div>
                            <div class="flex items-center">
                                <x-heroicon-o-lock-closed class="w-4 h-4 mr-1" />
                                <span>An toàn</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- QR Code Payment Popup -->
<div id="qr-payment-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" style="backdrop-filter: blur(4px);" onclick="closeModalOnBackdrop(event)">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-auto" onclick="event.stopPropagation()">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
              
                <p class="text-sm text-gray-600 mt-1">Quét mã QR hoặc chuyển khoản theo thông tin bên dưới</p>
            </div>
            
            <!-- Content -->
            <div class="px-6 py-6">
                <!-- QR Code -->
                <div class="text-center mb-6">
                    <div class="bg-gray-100 p-2 rounded-lg inline-block">
                        <img src="{{ asset('images/qrcode.jpg') }}" 
                             alt="QR Code Payment" 
                             class="w-24 h-24 mx-auto"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div class="hidden">
                            <div class="w-24 h-24 bg-gray-200 flex items-center justify-center text-gray-500 text-sm">
                                QR Code<br>Payment
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Bank Info -->
                <div class="bg-blue-50 p-4 rounded-lg space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Ngân hàng:</span>
                        <span class="text-sm font-medium">Vietcombank</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Số tài khoản:</span>
                        <span class="text-sm font-medium">thoihongcantiendau</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Chủ tài khoản:</span>
                        <span class="text-sm font-medium">Pham Chi That</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Số tiền:</span>
                        <span class="text-sm font-bold text-red-600" id="payment-amount">{{ number_format($total) }}đ</span>
                    </div>
                    <div class="border-t border-blue-200 pt-3">
                        <span class="text-sm text-gray-600">Nội dung chuyển khoản:</span>
                        <div class="text-sm font-medium bg-white p-2 rounded mt-1 border">
                            <span id="order-ref">DH{{ date('YmdHis') }}</span> {{ Auth::user()->name ?? 'KhachHang' }}
                        </div>
                    </div>
                </div>
                
                <!-- Instructions -->
                <div class="mt-4 bg-yellow-50 p-3 rounded-lg">
                    <p class="text-xs text-yellow-800">
                        <x-heroicon-o-exclamation-triangle class="w-4 h-4 inline mr-1" />
                        Vui lòng chuyển khoản đúng số tiền và nội dung để đơn hàng được xử lý nhanh chóng.
                    </p>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex space-x-3">
                <button type="button" 
                        onclick="cancelPayment()"
                        class="flex-1 bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 transition-colors">
                    <x-heroicon-o-x-mark class="w-4 h-4 inline mr-1" />
                    Hủy
                </button>
                <button type="button" 
                        onclick="confirmPayment()"
                        class="flex-1 bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors">
                    <x-heroicon-o-check class="w-4 h-4 inline mr-1" />
                    Đã thanh toán
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Province API Integration
    const API_BASE_URL = 'https://vn-public-apis.fpo.vn';
    
    // Load provinces on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadProvinces();
    });
    
    // Load provinces
    async function loadProvinces() {
        try {
            const provinceSelect = document.getElementById('billing_city');
            provinceSelect.innerHTML = '<option value="">Đang tải...</option>';
            
            const response = await fetch(`${API_BASE_URL}/provinces/getAll?limit=-1`);
            const data = await response.json();
            
            provinceSelect.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';
            
            if (data.data && data.data.data) {
                data.data.data.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.name;
                    option.textContent = province.name_with_type || province.name;
                    option.dataset.code = province.code;
                    provinceSelect.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading provinces:', error);
            const provinceSelect = document.getElementById('billing_city');
            provinceSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
        }
    }
    
    // Load districts when province changes
    document.getElementById('billing_city').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const provinceCode = selectedOption.dataset.code;
        
        if (provinceCode) {
            loadDistricts(provinceCode);
        } else {
            // Clear districts and wards
            document.getElementById('billing_district').innerHTML = '<option value="">Chọn quận/huyện</option>';
            document.getElementById('billing_ward').innerHTML = '<option value="">Chọn phường/xã</option>';
        }
    });
    
    // Load districts
    async function loadDistricts(provinceCode) {
        try {
            const districtSelect = document.getElementById('billing_district');
            districtSelect.innerHTML = '<option value="">Đang tải...</option>';
            
            const response = await fetch(`${API_BASE_URL}/districts/getByProvince?provinceCode=${provinceCode}&limit=-1`);
            const data = await response.json();
            
            districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
            
            if (data.data && data.data.data) {
                data.data.data.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.name;
                    option.textContent = district.name_with_type || district.name;
                    option.dataset.code = district.code;
                    districtSelect.appendChild(option);
                });
            }
            
            // Clear wards
            document.getElementById('billing_ward').innerHTML = '<option value="">Chọn phường/xã</option>';
        } catch (error) {
            console.error('Error loading districts:', error);
            const districtSelect = document.getElementById('billing_district');
            districtSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
        }
    }
    
    // Load wards when district changes
    document.getElementById('billing_district').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const districtCode = selectedOption.dataset.code;
        
        if (districtCode) {
            loadWards(districtCode);
        } else {
            // Clear wards
            document.getElementById('billing_ward').innerHTML = '<option value="">Chọn phường/xã</option>';
        }
    });
    
    // Load wards
    async function loadWards(districtCode) {
        try {
            const wardSelect = document.getElementById('billing_ward');
            wardSelect.innerHTML = '<option value="">Đang tải...</option>';
            
            const response = await fetch(`${API_BASE_URL}/wards/getByDistrict?districtCode=${districtCode}&limit=-1`);
            const data = await response.json();
            
            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
            
            if (data.data && data.data.data) {
                data.data.data.forEach(ward => {
                    const option = document.createElement('option');
                    option.value = ward.name;
                    option.textContent = ward.name_with_type || ward.name;
                    option.dataset.code = ward.code;
                    wardSelect.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading wards:', error);
            const wardSelect = document.getElementById('billing_ward');
            wardSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
        }
    }
    
    // Checkout form submission
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        
        if (paymentMethod === 'bank_transfer') {
            // Show QR payment modal
            showQRPaymentModal();
        } else {
            // Normal COD checkout
            confirmOrder();
        }
    });
    
    // Show QR Payment Modal
    function showQRPaymentModal() {
        document.getElementById('qr-payment-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
        
        // Add ESC key listener
        document.addEventListener('keydown', handleEscKey);
    }
    
    // Handle ESC key
    function handleEscKey(event) {
        if (event.key === 'Escape') {
            cancelPayment();
        }
    }
    
    // Cancel Payment (Close Modal)
    function cancelPayment() {
        document.getElementById('qr-payment-modal').classList.add('hidden');
        document.body.style.overflow = 'auto'; // Restore scrolling
        
        // Remove ESC key listener
        document.removeEventListener('keydown', handleEscKey);
    }
    
    // Close modal when clicking on backdrop
    function closeModalOnBackdrop(event) {
        if (event.target === event.currentTarget) {
            cancelPayment();
        }
    }
    
    // Confirm Payment (Submit Form)
    function confirmPayment() {
        document.getElementById('qr-payment-modal').classList.add('hidden');
        document.body.style.overflow = 'auto'; // Restore scrolling
        
        // Remove ESC key listener
        document.removeEventListener('keydown', handleEscKey);
        
        // Submit the form
        document.getElementById('checkout-form').submit();
    }
    
    // Normal Order Confirmation (for COD)
    function confirmOrder() {
        Swal.fire({
            title: 'Xác nhận đặt hàng',
            text: 'Bạn có chắc chắn muốn đặt hàng với thông tin đã nhập?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Đặt hàng',
            cancelButtonText: 'Hủy bỏ'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('checkout-form').submit();
            }
        });
    }
</script>
@endsection 