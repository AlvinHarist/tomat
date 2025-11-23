<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VisitorSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('visitors')->insert([
            [
                'id' => 'v1111111-1111-1111-1111-111111111111',
                'name' => 'Andi Wijaya',
                'phone' => '081111111111',
                'email' => 'andi@gmail.com',
                'province' => 'Jawa Barat',
                'created_at' => $now->copy()->subMonths(2), // Daftar 2 bulan lalu
                'updated_at' => $now,
            ],
            [
                'id' => 'v2222222-2222-2222-2222-222222222222',
                'name' => 'Siti Aminah',
                'phone' => '081222222222',
                'email' => 'siti@gmail.com',
                'province' => 'DKI Jakarta',
                'created_at' => $now->copy()->subMonths(1), // Daftar 1 bulan lalu
                'updated_at' => $now,
            ],
            [
                'id' => 'v3333333-3333-3333-3333-333333333333',
                'name' => 'Budi Santoso',
                'phone' => '081333333333',
                'email' => 'budi.visitor@gmail.com',
                'province' => 'Jawa Tengah',
                'created_at' => $now->copy()->subDays(5), // Daftar 5 hari lalu
                'updated_at' => $now,
            ],
        ]);
    }
}