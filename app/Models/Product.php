<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Product extends Model
{
    use HasFactory, HasUuids;

    // penamaan tabel di database
    protected $table = 'products';

    // kolom dapat diisi insert
    protected $fillable = [
        'seller_id',
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'images',
    ];

    // casting sesuai migrasi tabel
    protected $casts = [
        'images' => 'array',
        'price'  => 'double',
    ];

    // relasi model lain
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
}
