<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CommentRatingSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('comment_ratings')->insert([
            [
                'id' => 'c1111111-1111-1111-1111-111111111111',
                'product_id' => 'p1111111-1111-1111-1111-111111111111', // Laptop
                'visitor_id' => 'v1111111-1111-1111-1111-111111111111', // Andi
                'comment' => 'Barang bagus, pengiriman cepat!',
                'rating' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 'c2222222-2222-2222-2222-222222222222',
                'product_id' => 'p1111111-1111-1111-1111-111111111111', // Laptop
                'visitor_id' => 'v2222222-2222-2222-2222-222222222222', // Siti
                'comment' => 'Agak mahal tapi worth it.',
                'rating' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}