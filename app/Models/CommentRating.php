<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // <-- WAJIB UTK UUID

class CommentRating extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = []; // Izinkan semua kolom diisi

    // Relasi ke Produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke Visitor
    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}