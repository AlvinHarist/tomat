<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Product extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'products';

    protected $fillable = [
        'seller_id',
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'images',
        'avg_rating',
        'review_count',
    ];

    protected $casts = [
        'price'  => 'double',
        'images' => 'array',
        'avg_rating' => 'float',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    public function reviewAndRatings()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    /**
     * Update rating average dan count dari reviews
     */
    public function updateRating()
    {
        $avgRating = $this->reviews()->avg('rating') ?? 0;
        $reviewCount = $this->reviews()->count();

        $this->update([
            'avg_rating' => round($avgRating, 1),
            'review_count' => $reviewCount,
        ]);

        return $this;
    }

    /**
     * Accessor untuk rating dengan bintang display
     */
    public function getStarDisplayAttribute()
    {
        $rating = floor($this->avg_rating);
        return str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
    }

    /**
     * Helper: Get rating percentage (0-100)
     */
    public function getRatingPercentageAttribute()
    {
        return $this->avg_rating ? ($this->avg_rating / 5) * 100 : 0;
    }

    /**
     * Scope: Filter produk dengan rating minimum
     */
    public function scopeMinRating($query, $minRating)
    {
        return $query->where('avg_rating', '>=', $minRating);
    }

    /**
     * Scope: Filter produk dengan rating maximum
     */
    public function scopeMaxRating($query, $maxRating)
    {
        return $query->where('avg_rating', '<=', $maxRating);
    }

    /**
     * Scope: Urutkan produk berdasarkan rating (tertinggi)
     */
    public function scopeByRating($query)
    {
        return $query->orderBy('avg_rating', 'desc')->orderBy('review_count', 'desc');
    }

    /**
     * Scope: Filter produk yang sudah ada reviews
     */
    public function scopeWithReviews($query)
    {
        return $query->where('review_count', '>', 0);
    }
}
