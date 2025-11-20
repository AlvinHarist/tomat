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
                'name' => 'Andi Pengunjung',
                'phone' => '085111111111',
                'email' => 'andi@gmail.com',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 'v2222222-2222-2222-2222-222222222222',
                'name' => 'Siti Reviewer',
                'phone' => '085222222222',
                'email' => 'siti@gmail.com',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}