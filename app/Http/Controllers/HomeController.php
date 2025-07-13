<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Featured products - top rated and popular
        $featuredProducts = Product::with(['category:id,name,slug'])
            ->select(['id', 'name', 'slug', 'price', 'sale_price', 'featured_image', 
                    'category_id', 'average_rating', 'review_count', 'sold_count'])
            ->active()
            ->inStock()
            ->where('review_count', '>', 0)
            ->orderByRaw('(average_rating * review_count + sold_count * 0.1) DESC')
            ->take(8)
            ->get();

        // Popular categories
        $categories = Category::select(['id', 'name', 'slug', 'icon'])
            ->active()
            ->withCount(['products' => function($query) {
                $query->active()->inStock();
            }])
            ->orderBy('products_count', 'desc')
            ->take(6)
            ->get();

        // Sale products
        $saleProducts = Product::with(['category:id,name,slug'])
            ->select(['id', 'name', 'slug', 'price', 'sale_price', 'featured_image', 
                    'category_id', 'average_rating', 'review_count', 'sold_count'])
            ->active()
            ->inStock()
            ->whereNotNull('sale_price')
            ->whereColumn('sale_price', '<', 'price')
            ->orderByRaw('((price - sale_price) / price * 100) DESC') // Best discount first
            ->orderBy('average_rating', 'desc')
            ->take(8)
            ->get();

        // Statistics for banner
        $stats = [
            // Total active products
            'total_products' => Product::active()->inStock()->count(),
            
            // Customer satisfaction rate (based on reviews 4+ stars out of 5)
            'satisfaction_rate' => $this->calculateSatisfactionRate(),
            
            // Total orders completed
            'total_orders' => Order::where('status', 'completed')->count(),
            
            // Average rating across all products
            'average_rating' => Product::where('review_count', '>', 0)->avg('average_rating'),
        ];

        return view('home', compact('featuredProducts', 'categories', 'saleProducts', 'stats'));
    }

    private function calculateSatisfactionRate()
    {
        $totalReviews = Review::count();
        
        if ($totalReviews == 0) {
            return 99; // Default value if no reviews
        }

        $positiveReviews = Review::where('rating', '>=', 4)->count();
        
        return round(($positiveReviews / $totalReviews) * 100);
    }
}
