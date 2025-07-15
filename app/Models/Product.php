<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'sku',
        'stock_quantity',
        'sold_count',
        'average_rating',
        'review_count',
        'manage_stock',
        'in_stock',
        'is_active',
        'featured_image',
        'weight',
        'category_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'average_rating' => 'decimal:2',
        'weight' => 'decimal:2',
        'manage_stock' => 'boolean',
        'in_stock' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->approved();
    }

    // Route Key Name - Use slug for route model binding
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Mutators
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Accessors
    public function getFinalPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->sale_price && $this->price > $this->sale_price) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->final_price) . 'đ';
    }

    public function getFormattedOriginalPriceAttribute(): string
    {
        return number_format($this->price) . 'đ';
    }

    public function getFeaturedImageUrlAttribute(): string
    {
        if (!$this->featured_image) {
            return asset('images/products/default-product.jpg');
        }
        
        // If it's already a full URL, return as is
        if (str_starts_with($this->featured_image, 'http')) {
            return $this->featured_image;
        }
        
        // If it's a storage path, use storage URL
        if (str_starts_with($this->featured_image, 'products/')) {
            return Storage::url($this->featured_image);
        }
        
        // If it starts with images/, use as is
        if (str_starts_with($this->featured_image, 'images/')) {
            return asset($this->featured_image);
        }
        
        // Otherwise, prepend with images/products/
        return asset('images/products/' . $this->featured_image);
    }

    public function getRatingStarsAttribute(): array
    {
        $fullStars = floor($this->average_rating);
        $hasHalfStar = ($this->average_rating - $fullStars) >= 0.5;
        
        return [
            'full' => $fullStars,
            'half' => $hasHalfStar ? 1 : 0,
            'empty' => 5 - $fullStars - ($hasHalfStar ? 1 : 0)
        ];
    }

    public function getPopularityScoreAttribute(): float
    {
        // Combine rating and sold count for popularity
        return ($this->average_rating * 0.6) + (min($this->sold_count / 100, 5) * 0.4);
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('in_stock', true);
    }

    public function scopeByCategory(Builder $query, $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeMinRating(Builder $query, float $minRating): Builder
    {
        return $query->where('average_rating', '>=', $minRating);
    }

    public function scopePopular(Builder $query): Builder
    {
        return $query->orderByDesc('sold_count');
    }

    public function scopeTopRated(Builder $query): Builder
    {
        return $query->where('review_count', '>', 0)
                    ->orderByDesc('average_rating');
    }

    public function scopeOnSale(Builder $query): Builder
    {
        return $query->whereNotNull('sale_price')
                    ->whereColumn('sale_price', '<', 'price');
    }

    public function scopePriceRange(Builder $query, array $ranges): Builder
    {
        return $query->where(function($q) use ($ranges) {
            foreach ($ranges as $range) {
                if ($range['min'] === null && $range['max'] !== null) {
                    $q->orWhere('price', '<=', $range['max']);
                } elseif ($range['min'] !== null && $range['max'] === null) {
                    $q->orWhere('price', '>=', $range['min']);
                } elseif ($range['min'] !== null && $range['max'] !== null) {
                    $q->orWhereBetween('price', [$range['min'], $range['max']]);
                }
            }
        });
    }

    // Methods
    public function incrementSoldCount(int $quantity = 1): void
    {
        $this->increment('sold_count', $quantity);
    }

    public function refreshRatingStats(): void
    {
        $approvedReviews = $this->approvedReviews();
        $this->update([
            'average_rating' => $approvedReviews->avg('rating') ?? 0,
            'review_count' => $approvedReviews->count(),
        ]);
    }
}
