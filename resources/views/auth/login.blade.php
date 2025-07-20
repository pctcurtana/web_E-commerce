@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 ">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Đăng nhập tài khoản
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Hoặc
                <a href="{{ route('register') }}" class="font-medium text-red-600 hover:text-red-500">
                    đăng ký tài khoản mới
                </a>
            </p>
        </div>
        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           value="{{ old('email') }}"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-900">
                        Ghi nhớ đăng nhập
                    </label>
                </div>

                <div class="text-sm">
                    <a href="#" 
                       onclick="showForgotPasswordAlert(); return false;" 
                       class="font-medium text-red-600 hover:text-red-500">
                        Quên mật khẩu?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Đăng nhập
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showForgotPasswordAlert() {
    Swal.fire({
        title: 'Quên mật khẩu?',
        html: `
            <div class="text-center">
                <div class="mb-4">
                    <div class="text-6xl mb-3">📧</div>
                </div>
                <p class="text-gray-600 mb-4">
                    Để lấy lại mật khẩu, vui lòng liên hệ với quản trị viên qua email:
                </p>
                <p class="text-lg font-semibold text-blue-600 mb-4 bg-blue-50 px-4 py-2 rounded-lg cursor-pointer hover:bg-blue-100 transition-colors" 
                   onclick="copyEmailToClipboard()" 
                   title="Click để copy email">
                    📮 admin@example.com
                    <small class="block text-xs text-gray-500 mt-1">Click để copy</small>
                </p>
                <p class="text-sm text-gray-500">
                    Chúng tôi sẽ hỗ trợ bạn tạo mật khẩu mới trong thời gian sớm nhất!
                </p>
            </div>
        `,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '📧 Gửi Email',
        cancelButtonText: '❌ Đóng',
        customClass: {
            popup: 'rounded-lg',
            title: 'text-xl font-bold text-gray-800',
            content: 'text-gray-600'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Tạo link mailto để mở email client
            const subject = encodeURIComponent('Yêu cầu đặt lại mật khẩu');
            const body = encodeURIComponent('Xin chào,\n\nTôi muốn yêu cầu đặt lại mật khẩu cho tài khoản của mình.\n\nThông tin tài khoản:\n- Email: [Nhập email của bạn]\n- Họ tên: [Nhập họ tên của bạn]\n\nXin cảm ơn!');
            const mailtoLink = `mailto:admin@example.com?subject=${subject}&body=${body}`;
            
            window.location.href = mailtoLink;
            
            // Hiển thị thông báo thành công
            Swal.fire({
                title: 'Đã mở ứng dụng email!',
                text: 'Vui lòng kiểm tra ứng dụng email của bạn để gửi yêu cầu.',
                icon: 'success',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }
    });
}

// Function để copy email vào clipboard
function copyEmailToClipboard() {
    const email = 'admin@example.com';
    navigator.clipboard.writeText(email).then(() => {
        // Hiển thị toast thông báo đã copy
        Swal.fire({
            title: 'Đã copy!',
            text: 'Địa chỉ email đã được copy vào clipboard',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }).catch(() => {
        // Fallback cho trình duyệt cũ
        const textArea = document.createElement('textarea');
        textArea.value = email;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        
        Swal.fire({
            title: 'Đã copy!',
            text: 'Địa chỉ email đã được copy',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    });
}
</script>
@endsection 