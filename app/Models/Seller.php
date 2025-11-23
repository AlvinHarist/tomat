<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- Tambahkan

class Seller extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    // Tambahkan ini agar bisa diisi
    protected $guarded = [];

    /**
     * Mendapatkan user yang memiliki profil ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}