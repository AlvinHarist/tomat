<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $baseTime = Carbon::parse('2025-10-01 08:00:00');

        $users = [
            [
                'id' => 1,
                'name' => 'Admin Platform',
                'email' => 'admin@tomat.com',
                'password' => Hash::make('password'),
                'role' => 'platform',
            ],
            [
                'id' => 2,
                'name' => 'Budi Penjual',
                'email' => 'budi@toko.com',
                'password' => Hash::make('password'),
                'role' => 'seller',
            ],
            [
                'id' => 3,
                'name' => 'Citra Penjual',
                'email' => 'citra@toko.com',
                'password' => Hash::make('password'),
                'role' => 'seller',
            ],
            [
                'id' => 4,
                'name' => 'Dedi Penjual',
                'email' => 'dedi@toko.com',
                'password' => Hash::make('password'),
                'role' => 'seller',
            ],
        ];

        $data = [];
        foreach ($users as $index => $user) {
            $user['created_at'] = $baseTime->copy()->addHours($index);
            $user['updated_at'] = $user['created_at'];
            $data[] = $user;
        }

        DB::table('users')->insert($data);
    }
}