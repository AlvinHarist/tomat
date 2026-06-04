<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Review;

class RatingCalculationTest extends TestCase
{
    public function test_product_average_rating_calculated_correctly()
    {
        // 1. Cari produk Flashdisk asli di database
        $product = Product::where('name', 'Flashdisk 64GB USB 3.0 High Speed')->first();

        // Pastikan produk ketemu
        $this->assertNotNull($product, 'Produk Flashdisk tidak ada di database.');

        // --- BERSIHKAN DATA TES LAMA ---
        Review::where('email', 'tester@kampus.ac.id')->get()->each->delete();

        // Refresh data produk agar menarik angka terbaru yang sudah bersih dari database
        $product->refresh();

        // Catat jumlah ulasan asli sebelum ditambah data baru
        $jumlahUlasanLama = $product->review_count;

        // 2. Masukkan 1 ulasan baru 
        Review::create([
            'product_id' => $product->id,
            'name'       => 'Tester 1',
            'phone'      => '0812345678',
            'email'      => 'tester@kampus.ac.id',
            'province'   => 'Jawa Tengah',
            'rating'     => 5,
            'comment'    => 'Ini ulasan dari Unit Test.'
        ]);

        // 3. Tarik data terbaru dari database
        $product->refresh();

        // 4. Hitung ekspektasi angka pastinya
        $expectedAvg = round($product->reviews()->avg('rating'), 1);
        $expectedCount = $jumlahUlasanLama + 1;

        // 5. Cek apakah field avg_rating dan review_count berhasil update
        $this->assertEquals($expectedAvg, $product->avg_rating);
        $this->assertEquals($expectedCount, $product->review_count);
    }
}