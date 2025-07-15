<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'ShopVN - Mua sắm trực tuyến') | ShopVN</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom Styles -->
    <style>
        /* User Menu Dropdown Animations */
        [x-cloak] { display: none !important; }
        
        /* Custom scrollbar for dropdown if needed */
        .dropdown-menu::-webkit-scrollbar {
            width: 4px;
        }
        
        .dropdown-menu::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .dropdown-menu::-webkit-scrollbar-thumb {
            background: #dc2626;
            border-radius: 10px;
        }
        
        /* Avatar gradient effect */
        .user-avatar {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
        }
        
        /* Menu item hover effects */
        .menu-item {
            transition: all 0.2s ease-in-out;
        }
        
        .menu-item:hover {
            transform: translateX(2px);
        }

        /* Badge animation */
        .cart-badge {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .8;
            }
        }
        
        /* Mobile responsive adjustments */
        @media (max-width: 640px) {
            .user-dropdown {
                right: -1rem;
                width: calc(100vw - 2rem);
                max-width: 280px;
            }
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">

        <!-- Main Navigation -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-red-600">
                        ShopVN
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="flex-1 max-w-2xl mx-2">
                    <form action="{{ route('products.index') }}" method="GET" class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Tìm kiếm sản phẩm..." 
                               class="w-full pl-4 pr-12 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <button type="submit" class="absolute right-0 top-0 h-full px-4 bg-red-600 text-white rounded-r-md hover:bg-red-700">
                            <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                        </button>
                    </form>
                </div>

                <!-- Right Side Icons & User Menu -->
                <div class="flex items-center mx-4 lg:mx-4">
                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-red-600 transition-colors">
                        <x-heroicon-o-shopping-cart class="w-6 h-6 sm:w-8 sm:h-8" />
                        <span id="cart-count" class="cart-badge absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center text-xs">
                            0
                        </span>
                    </a>
                </div>
                <div class="flex items-center">
                    <!-- user logo -->
                    @auth
                        <!-- User Menu Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <!-- User Avatar & Name -->
                            <button @click="open = !open" class="flex items-center space-x-1 sm:space-x-2 text-gray-700 hover:text-red-600 transition-colors focus:outline-none">
                                <div class="user-avatar w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center text-white font-medium text-sm">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="hidden lg:block font-medium text-sm">{{ Str::limit(Auth::user()->name, 12) }}</span>
                                <x-heroicon-o-chevron-down class="w-3 h-3 sm:w-4 sm:h-4 transition-transform duration-200" x-bind:class="open ? 'rotate-180' : ''" />
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 x-cloak
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-95"
                                 class="user-dropdown dropdown-menu absolute right-0 mt-2 w-56 sm:w-64 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50">
                                
                                <!-- User Info Header -->
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <div class="flex items-center space-x-3">
                                        <div class="user-avatar w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold text-lg">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                                            <p class="text-sm text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Menu Items -->
                                <div class="py-2">
                                    <a href="{{ route('orders.index') }}" class="menu-item flex items-center px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                                        <x-heroicon-o-shopping-bag class="w-5 h-5 mr-3 flex-shrink-0" />
                                        <span>Đơn hàng của tôi</span>
                                    </a>                           

                                    <hr class="my-2 border-gray-100">

                                    <!-- Fallback simple GET logout link -->
                                    <a href="{{ route('logout.get') }}" 
                                       onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?')"
                                       class="menu-item flex items-center w-full px-4 py-2 text-red-600 hover:bg-red-50 transition-colors">
                                        <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5 mr-3 flex-shrink-0" />
                                        <span>Đăng xuất</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Login/Register Links -->
                        <div class="flex items-center space-x-2 sm:space-x-4">
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-red-600 transition-colors font-medium text-sm">
                                <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4 sm:w-5 sm:h-5 inline-block mr-1" />
                                <span class="hidden sm:inline">Đăng nhập</span>
                            </a>
                            <a href="{{ route('register') }}" class="bg-red-600 text-white px-2 py-1 sm:px-4 sm:py-2 rounded-lg hover:bg-red-700 transition-colors font-medium text-sm">
                                Đăng ký
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Categories Navigation - Removed for cleaner design -->
    </header>

    <!-- Flash Messages will be handled by SweetAlert2 -->

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">CHĂM SÓC KHÁCH HÀNG</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="#" class="hover:text-white">Trung tâm trợ giúp</a></li>
                        <li><a href="#" class="hover:text-white">ShopVN Blog</a></li>
                        <li><a href="#" class="hover:text-white">Hướng dẫn mua hàng</a></li>
                        <li><a href="#" class="hover:text-white">Hướng dẫn bán hàng</a></li>
                        <li><a href="#" class="hover:text-white">Thanh toán</a></li>
                        <li><a href="#" class="hover:text-white">ShopVN Xu</a></li>
                        <li><a href="#" class="hover:text-white">Vận chuyển</a></li>
                        <li><a href="#" class="hover:text-white">Trả hàng & hoàn tiền</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">VỀ SHOPVN</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="#" class="hover:text-white">Giới thiệu về ShopVN Việt Nam</a></li>
                        <li><a href="#" class="hover:text-white">Tuyển dụng</a></li>
                        <li><a href="#" class="hover:text-white">Điều khoản ShopVN</a></li>
                        <li><a href="#" class="hover:text-white">Chính sách bảo mật</a></li>
                        <li><a href="#" class="hover:text-white">Chính hãng</a></li>
                        <li><a href="#" class="hover:text-white">Kênh người bán</a></li>
                        <li><a href="#" class="hover:text-white">Flash Sales</a></li>
                        <li><a href="#" class="hover:text-white">Chương trình Tiếp thị liên kết ShopVN</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">THANH TOÁN</h3>
                    <div class="grid grid-cols-3 gap-2 mb-6">
                        <div class="bg-white rounded p-2">
                            <img src="{{ file_exists(public_path('images/payment/visa.png')) ? asset('images/payment/visa.png') : 'https://via.placeholder.com/60x30/cccccc/666666?text=VISA' }}" 
                                 alt="Visa" class="w-full h-auto">
                        </div>
                        <div class="bg-white rounded p-2">
                            <img src="{{ file_exists(public_path('images/payment/mastercard.png')) ? asset('images/payment/mastercard.png') : 'https://via.placeholder.com/60x30/cccccc/666666?text=MC' }}" 
                                 alt="Mastercard" class="w-full h-auto">
                        </div>
                        <div class="bg-white rounded p-2">
                            <img src="{{ file_exists(public_path('images/payment/jcb.png')) ? asset('images/payment/jcb.png') : 'https://via.placeholder.com/60x30/cccccc/666666?text=JCB' }}" 
                                 alt="JCB" class="w-full h-auto">
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold mb-4">ĐƠN VỊ VẬN CHUYỂN</h3>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="bg-white rounded p-2">
                            <img src="{{ file_exists(public_path('images/shipping/spx.png')) ? asset('images/shipping/spx.png') : 'https://via.placeholder.com/60x30/cccccc/666666?text=SPX' }}" 
                                 alt="SPX" class="w-full h-auto">
                        </div>
                        <div class="bg-white rounded p-2">
                            <img src="{{ file_exists(public_path('images/shipping/ghn.png')) ? asset('images/shipping/ghn.png') : 'https://via.placeholder.com/60x30/cccccc/666666?text=GHN' }}" 
                                 alt="GHN" class="w-full h-auto">
                        </div>
                        <div class="bg-white rounded p-2">
                            <img src="{{ file_exists(public_path('images/shipping/vtp.png')) ? asset('images/shipping/vtp.png') : 'https://via.placeholder.com/60x30/cccccc/666666?text=VTP' }}" 
                                 alt="VTP" class="w-full h-auto">
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">THEO DÕI CHÚNG TÔI TRÊN</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="#" class="hover:text-white flex items-center"><x-heroicon-o-chat-bubble-left-ellipsis class="w-4 h-4 mr-2" />Facebook</a></li>
                        <li><a href="#" class="hover:text-white flex items-center"><x-heroicon-o-camera class="w-4 h-4 mr-2" />Instagram</a></li>
                        <li><a href="#" class="hover:text-white flex items-center"><x-heroicon-o-video-camera class="w-4 h-4 mr-2" />LinkedIn</a></li>
                    </ul>
                    <h3 class="text-lg font-semibold mb-4 mt-6">TẢI ỨNG DỤNG SHOPVN NGAY THÔI</h3>
                    <div class="flex space-x-2">
                        <p>Đã có mặt trên Google Play</p>
                        <p>Đã có mặt trên App Store</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex justify-center items-center text-center text-gray-300">
                    <p>&copy; 2024 ShopVN. Tất cả các quyền được bảo lưu.</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Global authentication status
        window.isUserAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
        
        // Update cart count
        function updateCartCount() {
            fetch('/cart/count')
                .then(response => response.json())
                .then(data => {
                    const count = data.count;
                    document.getElementById('cart-count').textContent = count;
                    // Update cart count in user menu if it exists
                    const menuCartCount = document.getElementById('cart-count-menu');
                    if (menuCartCount) {
                        menuCartCount.textContent = count;
                        // Hide badge if cart is empty
                        menuCartCount.style.display = count > 0 ? 'inline-block' : 'none';
                    }
                });
        }

        // Update cart count on page load
        updateCartCount();

        // SweetAlert2 Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            },
            customClass: {
                popup: 'colored-toast'
            }
        });

        // Custom CSS for SweetAlert2
        const style = document.createElement('style');
        style.textContent = `
            .colored-toast.swal2-icon-success {
                background-color: #10b981 !important;
            }
            .colored-toast.swal2-icon-error {
                background-color: #ef4444 !important;
            }
            .colored-toast.swal2-icon-warning {
                background-color: #f59e0b !important;
            }
            .colored-toast.swal2-icon-info {
                background-color: #3b82f6 !important;
            }
            .colored-toast .swal2-popup {
                color: white !important;
            }
            .colored-toast .swal2-title {
                color: white !important;
            }
        `;
        document.head.appendChild(style);

        // Handle Flash Messages
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        @endif

        @if(session('warning'))
            Toast.fire({
                icon: 'warning',
                title: '{{ session('warning') }}'
            });
        @endif

        @if(session('info'))
            Toast.fire({
                icon: 'info',
                title: '{{ session('info') }}'
            });
        @endif

        // Global notification function for AJAX calls
        window.showNotification = function(type, message) {
            Toast.fire({
                icon: type,
                title: message
            });
        };



        // Enhanced add to cart function
        window.addToCartWithNotification = function(form) {
            // Kiểm tra authentication bằng global variable
            if (!window.isUserAuthenticated) {
                Toast.fire({
                    icon: 'info',
                    title: 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!',
                    timer: 2000
                });
                setTimeout(() => {
                    // Clear any existing intended URL before redirect
                    sessionStorage.clear();
                    window.location.href = '{{ route("login") }}';
                }, 2000);
                return;
            }
            
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(async response => {
                console.log('Response status:', response.status);
                
                // Handle authentication required từ server
                if (response.status === 401) {
                    try {
                        const errorData = await response.json();
                        Toast.fire({
                            icon: 'info',
                            title: errorData.message,
                            timer: 2000
                        });
                        setTimeout(() => {
                            window.location.href = errorData.redirect || '{{ route("login") }}';
                        }, 2000);
                        return null;
                    } catch (jsonError) {
                        // Fallback
                        Toast.fire({
                            icon: 'info',
                            title: 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!',
                            timer: 2000
                        });
                        setTimeout(() => {
                            window.location.href = '{{ route("login") }}';
                        }, 2000);
                        return null;
                    }
                }
                
                if (!response.ok) {
                    // Try to get error message from response
                    try {
                        const errorData = await response.json();
                        throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                    } catch (jsonError) {
                        const errorText = await response.text();
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                }
                
                // Get response text first
                const responseText = await response.text();
                console.log('Response text:', responseText);
                
                // Try to parse as JSON
                try {
                    return JSON.parse(responseText);
                } catch (jsonError) {
                    console.error('JSON parse error:', jsonError);
                    
                    // If it's not JSON, assume it's a successful operation
                    return {
                        success: true,
                        message: 'Đã thêm sản phẩm vào giỏ hàng!'
                    };
                }
            })
            .then(data => {
                if (data === null) return; // Skip if redirected to login
                
                console.log('Response data:', data);
                if (data.success) {
                    Toast.fire({
                        icon: 'success',
                        title: data.message || 'Đã thêm sản phẩm vào giỏ hàng!'
                    });
                    updateCartCount();
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.message || 'Có lỗi xảy ra!'
                    });
                }
            })
            .catch(error => {
                console.error('Error details:', error);
                Toast.fire({
                    icon: 'error',
                    title: 'Có lỗi xảy ra khi thêm sản phẩm! Vui lòng thử lại.'
                });
            });
        };
    </script>
</body>
</html>

