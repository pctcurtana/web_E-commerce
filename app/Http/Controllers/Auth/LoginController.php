<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Admin đăng nhập thẳng vào admin panel, không qua user interface
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            
            // Xử lý intended URL - nếu là cart hoặc POST request thì clear và về trang chủ
            $intendedUrl = $request->session()->get('url.intended');
            if ($intendedUrl) {
                // Nếu intended URL chứa cart hoặc là POST request
                if (str_contains($intendedUrl, 'cart') || str_contains($intendedUrl, '/add')) {
                    $request->session()->forget('url.intended');
                    return redirect('/')->with('info', 'Bạn đã đăng nhập thành công! Hãy thêm sản phẩm vào giỏ hàng.');
                }
            }
            
            // User thường về intended URL hoặc homepage
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
