<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Review extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'reviews';

    protected $fillable = [
        'product_id',
        'name',
        'phone',
        'email',
        'province',
        'comment',
        'rating',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Boot method untuk menangani events
     */
    protected static function boot()
    {
        parent::boot();

        // Update product rating ketika review created
        static::created(function ($review) {
            if ($review->product_id) {
                $review->product->updateRating();
            }
        });

        // Update product rating ketika review updated
        static::updated(function ($review) {
            if ($review->product_id) {
                $review->product->updateRating();
            }
        });

        // Update product rating ketika review deleted
        static::deleted(function ($review) {
            if ($review->product_id) {
                $review->product->updateRating();
            }
        });
    }

    /**
     * Scope: Filter review dengan rating tertentu
     */
    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope: Filter review dengan rating minimum
     */
    public function scopeMinRating($query, $minRating)
    {
        return $query->where('rating', '>=', $minRating);
    }

    /**
     * Scope: Filter review dengan rating maximum
     */
    public function scopeMaxRating($query, $maxRating)
    {
        return $query->where('rating', '<=', $maxRating);
    }

    /**
     * Scope: Filter review yang memiliki comment
     */
    public function scopeWithComment($query)
    {
        return $query->whereNotNull('comment')->where('comment', '!=', '');
    }

    /**
     * Helper: Dapatkan star display (e.g., "★★★★★")
     */
    public function getStarDisplayAttribute()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    /**
     * Helper: Check apakah review punya comment
     */
    public function hasComment()
    {
        return !empty($this->comment);
    }
}
