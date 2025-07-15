<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Optimized query with eager loading
        $query = Product::with(['category:id,name,slug'])
                        ->select(['id', 'name', 'slug', 'price', 'sale_price', 'featured_image', 
                                'category_id', 'average_rating', 'review_count', 'sold_count', 
                                'stock_quantity', 'is_active', 'in_stock', 'created_at'])
                        ->active()
                        ->inStock();

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->byCategory($request->category);
        }

        // Search by name
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by price range
        if ($request->has('price_range')) {
            $priceRange = $request->get('price_range');
            
            if (is_array($priceRange)) {
                $query->where(function($q) use ($priceRange) {
                    foreach ($priceRange as $range) {
                        switch ($range) {
                            case 'under_500k':
                                $q->orWhere('price', '<', 500000);
                                break;
                            case '500k_1m':
                                $q->orWhereBetween('price', [500000, 1000000]);
                                break;
                            case '1m_5m':
                                $q->orWhereBetween('price', [1000000, 5000000]);
                                break;
                            case 'above_5m':
                                $q->orWhere('price', '>', 5000000);
                                break;
                        }
                    }
                });
            }
        }

        // Filter by rating ranges (using real database ratings)
        if ($request->has('rating')) {
            $rating = $request->get('rating');
            
            if (is_array($rating)) {
                $query->where(function($q) use ($rating) {
                    foreach ($rating as $stars) {
                        $stars = (int)$stars;
                        switch ($stars) {
                            case 5:
                                // 5 sao: từ 4.5 đến 5.0
                                $q->orWhereBetween('average_rating', [4.5, 5.0]);
                                break;
                            case 4:
                                // 4 sao: từ 3.5 đến 4.49
                                $q->orWhereBetween('average_rating', [3.5, 4.49]);
                                break;
                            case 3:
                                // 3 sao: từ 2.5 đến 3.49
                                $q->orWhereBetween('average_rating', [2.5, 3.49]);
                                break;
                            case 2:
                                // 2 sao: từ 1.5 đến 2.49
                                $q->orWhereBetween('average_rating', [1.5, 2.49]);
                                break;
                            case 1:
                                // 1 sao: từ 0 đến 1.49
                                $q->orWhereBetween('average_rating', [0, 1.49]);
                                break;
                        }
                    }
                });
            }
        }

        // Sort products
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'popularity':
                // Sort by actual popularity using sold_count and average_rating
                $query->orderByRaw('(sold_count * 0.7 + average_rating * review_count * 0.3) DESC');
                break;
            case 'rating':
                // Sort by rating (best rated first)
                $query->orderBy('average_rating', 'desc')
                      ->orderBy('review_count', 'desc');
                break;
            case 'most_sold':
                $query->orderBy('sold_count', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->appends($request->query());
        
        // Optimized categories query
        $categories = Category::select(['id', 'name', 'slug'])
            ->active()
            ->withCount(['products' => function($query) {
                $query->active()->inStock();
            }])
            ->orderBy('name')
            ->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        // Optimized product query with reviews
        $product = Product::with([
                'category:id,name,slug',
                'approvedReviews' => function($query) {
                    $query->with('user:id,name')
                          ->when(auth()->check(), function($q) {
                              // Put current user's review first
                              $q->orderByRaw('CASE WHEN user_id = ? THEN 0 ELSE 1 END', [auth()->id()]);
                          })
                          ->latest()
                          ->take(10);
                }
            ])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        // Get related products with improved algorithm
        $relatedProducts = collect();
        
        // First, try to get products from same category
        $sameCategory = Product::with(['category:id,name,slug'])
            ->select(['id', 'name', 'slug', 'price', 'sale_price', 'featured_image', 
                    'category_id', 'average_rating', 'review_count', 'sold_count'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->inStock()
            ->orderByRaw('(average_rating * review_count + sold_count * 0.1) DESC')
            ->take(4)
            ->get();
            
        $relatedProducts = $relatedProducts->merge($sameCategory);
        
        // If not enough products, get from other categories
        if ($relatedProducts->count() < 4) {
            $needed = 4 - $relatedProducts->count();
            $otherProducts = Product::with(['category:id,name,slug'])
                ->select(['id', 'name', 'slug', 'price', 'sale_price', 'featured_image', 
                        'category_id', 'average_rating', 'review_count', 'sold_count'])
                ->where('category_id', '!=', $product->category_id)
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $relatedProducts->pluck('id'))
                ->active()
                ->inStock()
                ->orderByRaw('(average_rating * review_count + sold_count * 0.1) DESC')
                ->take($needed)
                ->get();
                
            $relatedProducts = $relatedProducts->merge($otherProducts);
        }

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function byCategory($slug)
    {
        $category = Category::where('slug', $slug)->active()->firstOrFail();
        
        $products = Product::with(['category:id,name,slug'])
            ->select(['id', 'name', 'slug', 'price', 'sale_price', 'featured_image', 
                    'category_id', 'average_rating', 'review_count', 'sold_count'])
            ->where('category_id', $category->id)
            ->active()
            ->inStock()
            ->orderByDesc('average_rating')
            ->paginate(12);

        // Get all categories for the sidebar
        $categories = Category::select(['id', 'name', 'slug'])
            ->active()
            ->withCount(['products' => function($query) {
                $query->active()->inStock();
            }])
            ->orderBy('name')
            ->get();

        return view('products.index', compact('category', 'products', 'categories'));
    }

    public function reviews($slug)
    {
        $product = Product::where('slug', $slug)
            ->with(['category:id,name,slug'])
            ->active()
            ->firstOrFail();

        // Get all approved reviews with pagination
        $reviews = $product->approvedReviews()
            ->with('user:id,name')
            ->when(auth()->check(), function($query) {
                // Put current user's review first
                $query->orderByRaw('CASE WHEN user_id = ? THEN 0 ELSE 1 END', [auth()->id()]);
            })
            ->latest()
            ->paginate(10);

        // Get rating statistics
        $ratingStats = $product->approvedReviews()
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->pluck('count', 'rating')
            ->toArray();

        // Fill missing ratings with 0
        for ($i = 1; $i <= 5; $i++) {
            if (!isset($ratingStats[$i])) {
                $ratingStats[$i] = 0;
            }
        }
        krsort($ratingStats); // Sort descending

        // Calculate rating percentages
        $totalReviews = array_sum($ratingStats);
        $ratingPercentages = [];
        foreach ($ratingStats as $rating => $count) {
            $ratingPercentages[$rating] = $totalReviews > 0 ? round(($count / $totalReviews) * 100, 1) : 0;
        }

        return view('products.reviews', compact('product', 'reviews', 'ratingStats', 'ratingPercentages', 'totalReviews'));
    }
}
