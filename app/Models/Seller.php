<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable; // jika seller bisa login
use Illuminate\Support\Str;

class Seller extends Authenticatable
{
    use HasFactory;

    public $incrementing = false; // UUID
    protected $keyType = 'string';

    // Override email column for authentication
    public function getAuthIdentifierName()
    {
        return 'pic_email';
    }

    protected $fillable = [
        'store_name',
        'store_description',
        'pic_name',
        'pic_phone',
        'pic_email',
        'password',
        'pic_street',
        'pic_rt',
        'pic_rw',
        'pic_village',
        'pic_city',
        'pic_province',
        'pic_ktp_number',
        'pic_photo_path',
        'pic_ktp_file_path',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'status' => 'string', // enum ['PENDING','ACTIVE','REJECTED']
    ];

    // Generate UUID when creating
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /** 
     * Hash password ketika diset 
     */
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    /**
     * Relasi: Seller punya banyak Product
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}


// <?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- Tambahkan

// class Seller extends Model
// {
//     use HasFactory;

//     // Tambahkan ini agar bisa diisi
//     protected $guarded = [];

//     /**
//      * Mendapatkan user yang memiliki profil ini.
//      */
    
// }