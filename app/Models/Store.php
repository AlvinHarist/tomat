<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- Tambahkan

class Store extends Model
{
    use HasFactory;

    // Tambahkan ini agar bisa diisi
    protected $guarded = [];

    /**
     * Mendapatkan user (penjual) yang memiliki toko ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}