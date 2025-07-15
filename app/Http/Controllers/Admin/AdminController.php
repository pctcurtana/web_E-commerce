<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Tính doanh thu hybrid (lịch sử + mới)
        $historicalRevenue = Product::selectRaw('SUM(price * sold_count) as total')
            ->first()->total ?? 0;
        
        $newRevenue = Order::where('status', 'delivered')->sum('total_amount');
        
        $todayNewRevenue = Order::whereDate('delivered_at', today())
            ->where('status', 'delivered')
            ->sum('total_amount');
            
        $thisMonthNewRevenue = Order::whereYear('delivered_at', now()->year)
            ->whereMonth('delivered_at', now()->month)
            ->where('status', 'delivered')
            ->sum('total_amount');

        // Basic statistics
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'total_users' => User::where('role', 'user')->count(),
            'total_categories' => Category::where('is_active', true)->count(),
            'total_reviews' => Review::count(),
            'recent_reviews' => Review::where('created_at', '>=', now()->subDays(7))->count(),
            'avg_rating' => Review::avg('rating') ?? 0,
            // Hybrid revenue calculation
            'total_revenue' => $historicalRevenue + $newRevenue,
            'historical_revenue' => $historicalRevenue,
            'new_system_revenue' => $newRevenue,
            'today_revenue' => $todayNewRevenue,
            'this_month_revenue' => $thisMonthNewRevenue,
            // Order statistics
            'total_orders' => Order::count(),
            'delivered_orders' => Order::where('status', 'delivered')->count(),
        ];

        // Recent products
        $recentProducts = Product::with('category')
            ->latest()
            ->take(5)
            ->get();

        // Recent users
        $recentUsers = User::where('role', 'user')
            ->latest()
            ->take(5)
            ->get();



        // Recent orders
        $recentOrders = Order::with(['user:id,name,email'])
            ->latest()
            ->take(5)
            ->get();

        // Top selling products (combined: historical sold_count + new order deliveries)
        $topProducts = Product::with('category')
            ->select('products.*')
            ->selectRaw('
                products.sold_count + COALESCE(SUM(order_items.quantity), 0) as total_sold
            ')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', function($join) {
                $join->on('order_items.order_id', '=', 'orders.id')
                     ->where('orders.status', '=', 'delivered');
            })
            ->groupBy('products.id')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        // Monthly revenue data (last 6 months) - chỉ tính đơn hàng delivered từ hệ thống mới
        $monthlyRevenue = Order::selectRaw('
                MONTH(delivered_at) as month,
                YEAR(delivered_at) as year,
                COUNT(*) as total_orders,
                SUM(total_amount) as revenue
            ')
            ->where('status', 'delivered')
            ->where('delivered_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentProducts',
            'recentUsers', 
            'recentOrders',
            'topProducts',
            'monthlyRevenue'
        ));
    }
}
