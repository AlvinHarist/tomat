<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // <-- PENTING: Pakai ini, bukan Model biasa
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // <-- PENTING: Untuk UUID

class Owner extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;

    // Pastikan nama tabel sesuai dengan yang ada di database Anda (lihat log migrasi tadi)
    protected $table = 'owners'; 
    
    protected $primaryKey = 'id';
    public $incrementing = false; // Karena UUID bukan angka urut
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];
}