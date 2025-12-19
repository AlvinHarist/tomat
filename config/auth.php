<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        // --- TAMBAHAN UNTUK MULTI-AUTH ---
        'owner' => [
            'driver' => 'session',
            'provider' => 'owners',
        ],
        'seller' => [
            'driver' => 'session',
            'provider' => 'sellers',
        ],
        // ---------------------------------
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        // --- TAMBAHAN UNTUK MULTI-AUTH ---
        'owners' => [
            'driver' => 'eloquent',
            'model' => App\Models\Owner::class, // Pastikan Model ini ada
        ],
        'sellers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Seller::class, // Pastikan Model ini ada
        ],
        // ---------------------------------
    ],

    'passwords' => [
        // ... (sisanya sama, Anda mungkin ingin menambahkan 'owners' dan 'sellers' juga jika mereka punya fitur reset password)
    ],

    'password_timeout' => 10800,

];