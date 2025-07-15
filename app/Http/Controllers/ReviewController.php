<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('no.admin'); // Admin không được review sản phẩm
    }

    /**
     * Check if user can review a product
     */
    public function canReview(Product $product)
    {
        $userId = Auth::id();
        
        // Check if user already reviewed this product
        $existingReview = Review::where('product_id', $product->id)
            ->where('user_id', $userId)
            ->first();
            
        if ($existingReview) {
            return response()->json([
                'can_review' => false,
                'message' => 'Bạn đã đánh giá sản phẩm này rồi.'
            ]);
        }

        // Check if user has purchased and received this product
        $hasPurchased = OrderItem::whereHas('order', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->where('status', 'delivered'); // Chỉ đơn hàng đã giao thành công
        })
        ->where('product_id', $product->id)
        ->exists();

        if (!$hasPurchased) {
            return response()->json([
                'can_review' => false,
                'message' => 'Bạn chỉ có thể đánh giá sản phẩm đã mua và nhận hàng thành công.'
            ]);
        }

        return response()->json([
            'can_review' => true,
            'message' => 'Bạn có thể đánh giá sản phẩm này.'
        ]);
    }

    /**
     * Store a new review
     */
    public function store(Request $request, Product $product)
    {
        $userId = Auth::id();
        
        // Validate input
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'rating.min' => 'Đánh giá tối thiểu 1 sao.',
            'rating.max' => 'Đánh giá tối đa 5 sao.',
            'comment.required' => 'Vui lòng nhập mô tả đánh giá.',
            'comment.max' => 'Mô tả đánh giá không được quá 1000 ký tự.'
        ]);

        // Check if user already reviewed this product
        $existingReview = Review::where('product_id', $product->id)
            ->where('user_id', $userId)
            ->first();
            
        if ($existingReview) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi.');
        }

        // Check if user has purchased and received this product
        $hasPurchased = OrderItem::whereHas('order', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->where('status', 'delivered');
        })
        ->where('product_id', $product->id)
        ->exists();

        if (!$hasPurchased) {
            return back()->with('error', 'Bạn chỉ có thể đánh giá sản phẩm đã mua và nhận hàng thành công.');
        }

        DB::transaction(function () use ($request, $product, $userId) {
            // Create review
            Review::create([
                'product_id' => $product->id,
                'user_id' => $userId,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'is_verified_purchase' => true, // Đã verify qua logic trên
                'is_approved' => true, // Tự động approve cho verified purchase
            ]);

            // Update product rating statistics
            $this->updateProductRatingStats($product);
        });

        return back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm! Đánh giá của bạn rất hữu ích cho những khách hàng khác.');
    }

    /**
     * Update product rating statistics
     */
    private function updateProductRatingStats(Product $product)
    {
        $product->refreshRatingStats();
    }



    /**
     * Delete a review
     */
    public function destroy(Product $product)
    {
        $userId = Auth::id();
        

        
        $review = Review::where('product_id', $product->id)
            ->where('user_id', $userId)
            ->first();
            
        if (!$review) {
            return back()->with('error', 'Không tìm thấy đánh giá của bạn cho sản phẩm này.');
        }

        DB::transaction(function () use ($product, $review) {
            $review->delete();
            
            // Update product rating statistics
            $this->updateProductRatingStats($product);
        });

        return back()->with('success', 'Đánh giá đã được xóa thành công!');
    }
} 