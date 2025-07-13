<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id', 
        'rating',
        'title',
        'comment',
        'is_verified_purchase',
        'is_recommended',
        'is_approved',
    ];

    protected $casts = [
        'is_verified_purchase' => 'boolean',
        'is_recommended' => 'boolean',
        'is_approved' => 'boolean',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    public function scopeVerifiedPurchase(Builder $query): Builder
    {
        return $query->where('is_verified_purchase', true);
    }

    public function scopeRecommended(Builder $query): Builder
    {
        return $query->where('is_recommended', true);
    }

    public function scopeByRating(Builder $query, int $rating): Builder
    {
        return $query->where('rating', $rating);
    }

    public function scopeMinRating(Builder $query, int $minRating): Builder
    {
        return $query->where('rating', '>=', $minRating);
    }

    public function scopeForProduct(Builder $query, int $productId): Builder
    {
        return $query->where('product_id', $productId);
    }

    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }



    // Accessors
    public function getStarsArrayAttribute(): array
    {
        return array_fill(0, $this->rating, 1);
    }

    public function getEmptyStarsArrayAttribute(): array
    {
        return array_fill(0, 5 - $this->rating, 1);
    }

    public function getShortCommentAttribute(): string
    {
        return \Str::limit($this->comment, 150);
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    // Static methods for aggregations
    public static function getAverageRating(int $productId): float
    {
        return static::approved()
            ->forProduct($productId)
            ->avg('rating') ?? 0;
    }

    public static function getReviewCount(int $productId): int
    {
        return static::approved()
            ->forProduct($productId)
            ->count();
    }

    public static function getRatingDistribution(int $productId): array
    {
        $distribution = static::approved()
            ->forProduct($productId)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        // Fill missing ratings with 0
        for ($i = 1; $i <= 5; $i++) {
            if (!isset($distribution[$i])) {
                $distribution[$i] = 0;
            }
        }

        ksort($distribution);
        return $distribution;
    }

    // Boot method for automatic calculations
    protected static function boot()
    {
        parent::boot();

        static::created(function ($review) {
            if ($review->is_approved) {
                $review->updateProductRatingStats();
            }
        });

        static::updated(function ($review) {
            if ($review->wasChanged('is_approved') || $review->wasChanged('rating')) {
                $review->updateProductRatingStats();
            }
        });

        static::deleted(function ($review) {
            $review->updateProductRatingStats();
        });
    }

    private function updateProductRatingStats(): void
    {
        $product = $this->product;
        if ($product) {
            $avgRating = static::getAverageRating($product->id);
            $reviewCount = static::getReviewCount($product->id);
            
            $product->update([
                'average_rating' => round($avgRating, 2),
                'review_count' => $reviewCount,
            ]);
        }
    }
}
