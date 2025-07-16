<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Panel - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0" id="sidebar">
            <div class="flex items-center justify-center h-16 bg-gray-800">
                <h1 class="text-white text-xl font-bold">
                    <x-heroicon-o-cog-6-tooth class="w-6 h-6 inline mr-2" />
                    Admin Panel
                </h1>
            </div>
            
            <nav class="mt-8">
                <div class="px-4 space-y-2">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-4 py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 text-white' : '' }}">
                        <x-heroicon-o-home class="w-5 h-5 mr-3" />
                        Dashboard
                    </a>

                    <!-- Products -->
                    <a href="{{ route('admin.products.index') }}" 
                       class="flex items-center px-4 py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-gray-700 text-white' : '' }}">
                        <x-heroicon-o-cube class="w-5 h-5 mr-3" />
                        Sản phẩm
                    </a>

                    <!-- Categories -->
                    <a href="{{ route('admin.categories.index') }}" 
                       class="flex items-center px-4 py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-gray-700 text-white' : '' }}">
                        <x-heroicon-o-tag class="w-5 h-5 mr-3" />
                        Danh mục
                    </a>

                    <!-- Orders -->
                    <a href="{{ route('admin.orders.index') }}" 
                       class="flex items-center px-4 py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-gray-700 text-white' : '' }}">
                        <x-heroicon-o-shopping-bag class="w-5 h-5 mr-3" />
                        Đơn hàng
                    </a>

                    <!-- Users -->
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center px-4 py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-gray-700 text-white' : '' }}">
                        <x-heroicon-o-users class="w-5 h-5 mr-3" />
                        Khách hàng
                    </a>


                </div>

                <!-- User Profile & Logout -->
                <div class="absolute bottom-0 w-full p-4 border-t border-gray-700">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-medium">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="flex-1">
                            <div class="text-white text-sm font-medium">{{ auth()->user()->name }}</div>
                            <div class="text-gray-400 text-xs">Administrator</div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center px-3 py-2 text-gray-300 rounded hover:bg-gray-700 hover:text-white transition-colors text-sm">
                                <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4 mr-2" />
                                Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 lg:ml-0">
            <!-- Mobile menu button -->
            <div class="lg:hidden bg-white shadow-sm border-b">
                <div class="px-4 py-3">
                    <button onclick="toggleSidebar()" class="text-gray-600 hover:text-gray-900">
                        <x-heroicon-o-bars-3 class="w-6 h-6" />
                    </button>
                </div>
            </div>

            <!-- Page Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
                            @hasSection('breadcrumb')
                                <nav class="mt-1">
                                    <ol class="flex items-center space-x-2 text-sm text-gray-500">
                                        @yield('breadcrumb')
                                    </ol>
                                </nav>
                            @endif
                        </div>
                        
                        @hasSection('header-actions')
                            <div class="flex items-center space-x-3">
                                @yield('header-actions')
                            </div>
                        @endif
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Overlay for mobile -->
    <div class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden hidden" id="sidebar-overlay" onclick="toggleSidebar()"></div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

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
            }
        });

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

        // Global notification function
        window.showNotification = function(type, message) {
            Toast.fire({
                icon: type,
                title: message
            });
        };
    </script>
    
    <!-- Page Scripts -->
    @stack('scripts')
</body>
</html> 