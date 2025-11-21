<?php

namespace App\Models;

// Ganti pewarisan dari Model biasa menjadi Authenticatable
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // Agar ID otomatis UUID
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Seller extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasUuids;

    protected $table = 'sellers';
    protected $primaryKey = 'id';
    public $incrementing = false; // Karena UUID bukan integer auto-increment
    protected $keyType = 'string';

    // Sesuai Class Diagram (ditambah password untuk login teknis)
    protected $fillable = [
        'store_name',
        'store_description',
        'pic_name',
        'pic_phone',
        'pic_email',
        'password', // Wajib ada untuk login, meski tidak eksplisit di diagram
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
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];
}