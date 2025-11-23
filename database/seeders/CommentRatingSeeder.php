<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // Tambahkan ini untuk generate UUID baru
use Carbon\Carbon;

class CommentRatingSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // 1. AMBIL DATA NYATA DARI DATABASE
        // Ambil semua ID produk yang sudah ada
        $productIds = DB::table('products')->pluck('id')->toArray();
        // Ambil semua ID visitor yang sudah ada
        $visitorIds = DB::table('visitors')->pluck('id')->toArray();

        // Cek keamanan: Kalau tidak ada produk/visitor, jangan jalankan biar ga error
        if (empty($productIds) || empty($visitorIds)) {
            $this->command->info('Skipping CommentRatingSeeder: No products or visitors found.');
            return;
        }

        // 2. BUAT RATING MENGGUNAKAN ID YANG VALID
        // Kita gunakan array_rand atau index [0], [1] untuk mengambil ID nya

        DB::table('comment_ratings')->insert([
            // Review 1: Pakai Produk Pertama & Visitor Pertama
            [
                'id' => Str::uuid()->toString(), // Generate UUID acak untuk rating ini
                'product_id' => $productIds[0], // Ambil ID Produk ke-1 yang valid
                'visitor_id' => $visitorIds[0], // Ambil ID Visitor ke-1 yang valid
                'comment' => 'Produk ini kualitasnya bagus banget!',
                'rating' => 5,
                'created_at' => $now->copy()->subDays(2),
                'updated_at' => $now,
            ],
            
            // Review 2: Pakai Produk Kedua (jika ada) atau Pertama, & Visitor Kedua
            [
                'id' => Str::uuid()->toString(),
                'product_id' => $productIds[1] ?? $productIds[0], // Ambil Produk ke-2, kalau ga ada pakai ke-1
                'visitor_id' => $visitorIds[1] ?? $visitorIds[0], // Ambil Visitor ke-2
                'comment' => 'Pengiriman agak lama tapi barang oke.',
                'rating' => 4,
                'created_at' => $now->copy()->subMonth(),
                'updated_at' => $now,
            ],

            // Review 3: Random
            [
                'id' => Str::uuid()->toString(),
                'product_id' => $productIds[array_rand($productIds)], // Ambil Produk Acak
                'visitor_id' => $visitorIds[array_rand($visitorIds)], // Ambil Visitor Acak
                'comment' => 'Harga bersahabat, recommended seller.',
                'rating' => 5,
                'created_at' => $now->copy()->subMonths(3),
                'updated_at' => $now,
            ],

            // Review 4: Random lagi
            [
                'id' => Str::uuid()->toString(),
                'product_id' => $productIds[array_rand($productIds)], 
                'visitor_id' => $visitorIds[array_rand($visitorIds)],
                'comment' => 'Biasa saja, sesuai harga.',
                'rating' => 3,
                'created_at' => $now->copy()->subMonths(4),
                'updated_at' => $now,
            ],
        ]);
    }
}