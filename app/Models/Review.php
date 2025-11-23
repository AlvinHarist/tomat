<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Review extends Model
{
    use HasFactory;

    protected $table = 'comment_ratings';

    public $incrementing = false;     // UUID
    protected $keyType = 'string';

    protected $fillable = [
        'product_id',
        'visitor_id',
        'comment',
        'rating',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * Auto generate UUID for ID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (! $model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Relasi ke produk
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relasi ke visitor
     */
    // public function visitor()
    // {
    //     return $this->belongsTo(Visitor::class);
    // }
}
