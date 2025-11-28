<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'guard' => 'web', // Guard default untuk user/pembeli
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    | Mendefinisikan mekanisme otentikasi (session) dan provider model untuk setiap jenis pengguna.
    */
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        
        // ** GUARD SELLER (Penjual) **
        'seller' => [
            'driver' => 'session',
            'provider' => 'sellers', // Menggunakan provider 'sellers'
        ],

        // ** GUARD OWNER (Pemilik/Admin) **
        'owner' => [
            'driver' => 'session',
            'provider' => 'admins', // Menggunakan provider 'admins'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    | Mendefinisikan Model Eloquent mana yang akan digunakan untuk setiap guard.
    */
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class, // Model untuk User/Pembeli
        ],

        // ** PROVIDER SELLERS **
        'sellers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Seller::class, // Model untuk Seller
        ],
        
        // ** PROVIDER ADMINS **
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Owner::class, // Model untuk Owner
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    */
    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        
        // Anda mungkin perlu menambahkan konfigurasi reset password untuk Seller dan Owner
        // 'sellers' => [
        //     'provider' => 'sellers',
        //     'table' => 'password_reset_tokens', // Atau tabel terpisah
        //     'expire' => 60,
        //     'throttle' => 60,
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */
    'password_timeout' => 10800,

];