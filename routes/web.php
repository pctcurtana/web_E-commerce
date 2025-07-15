<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// User routes - Admin KHÔNG được truy cập
Route::middleware('no.admin')->group(function () {
    // Home page
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Products routes
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/{slug}', [ProductController::class, 'show'])->name('show');
        Route::get('/{slug}/reviews', [ProductController::class, 'reviews'])->name('reviews');
    });

    // Category routes
    Route::get('/category/{slug}', [ProductController::class, 'byCategory'])->name('category.show');

    // Clear session intended URL (debug route)
Route::get('/clear-session', function() {
    session()->forget('url.intended');
    return redirect('/')->with('success', 'Đã xóa session intended URL!');
})->name('clear.session');



    // Check session intended URL (debug route)
    Route::get('/check-session', function() {
        $intendedUrl = session()->get('url.intended');
        $message = $intendedUrl ? "Intended URL hiện tại: " . $intendedUrl : "Không có intended URL";
        return redirect('/')->with('info', $message);
    })->name('check.session');

    // Cart routes (require authentication)
    Route::prefix('cart')->name('cart.')->middleware('auth')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::patch('/{id}', [CartController::class, 'update'])->name('update');
        Route::delete('/{id}', [CartController::class, 'remove'])->name('remove');
        Route::delete('/', [CartController::class, 'clear'])->name('clear');
        Route::get('/count', [CartController::class, 'count'])->name('count');
    });
});

// Order routes (require authentication) - Admin cũng KHÔNG được truy cập
Route::middleware(['auth', 'no.admin'])->prefix('orders')->name('orders.')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('index');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/', [OrderController::class, 'store'])->name('store');
    Route::get('/{id}', [OrderController::class, 'show'])->name('show');
    Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
});

// Review routes (require authentication) - Admin KHÔNG được truy cập
Route::middleware(['auth', 'no.admin'])->prefix('products/{product}')->name('reviews.')->group(function () {
    Route::get('/can-review', [App\Http\Controllers\ReviewController::class, 'canReview'])->name('can-review');
    Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('store');
    Route::delete('/reviews', [App\Http\Controllers\ReviewController::class, 'destroy'])->name('destroy');
});

// Auth routes
require __DIR__.'/auth.php';

// Fallback GET logout route to handle CSRF issues
Route::get('/logout', function() {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/')->with('success', 'Đã đăng xuất thành công!');
})->middleware('auth')->name('logout.get');

// Admin Routes - CHỈ admin được truy cập
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    
    // Products
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    Route::delete('products/{product}/remove-featured-image', [App\Http\Controllers\Admin\ProductController::class, 'removeFeaturedImage'])
        ->name('products.remove-featured-image');
    
    // Categories
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    
    // Orders
    Route::get('orders/export', [App\Http\Controllers\Admin\OrderController::class, 'export'])
        ->name('orders.export');
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show']);
    Route::patch('orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])
        ->name('orders.update-status');
    
    // Users - chỉ xem, sửa, xóa (không tạo mới)
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['create', 'store']);
    

});


