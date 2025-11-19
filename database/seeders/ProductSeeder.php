<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // <-- TAMBAHKAN INI

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // (Data produk tetap sama persis seperti sebelumnya)
        $products1 = [
            ['id' => 1, 'store_id' => 1, 'category_id' => 1, 'name' => 'Laptop Super Cepat', 'description' => 'Laptop untuk gaming dan kerja.', 'price' => 15000000, 'stock' => 10],
            ['id' => 2, 'store_id' => 1, 'category_id' => 2, 'name' => 'Kemeja Batik Premium', 'description' => 'Batik tulis asli Jogja.', 'price' => 250000, 'stock' => 50],
            ['id' => 3, 'store_id' => 1, 'category_id' => 1, 'name' => 'Mouse Gaming RGB', 'description' => 'Mouse dengan 8 tombol macro.', 'price' => 450000, 'stock' => 25],
            ['id' => 4, 'store_id' => 1, 'category_id' => 5, 'name' => 'Tenda Camping 4 Orang', 'description' => 'Tenda dome anti air.', 'price' => 750000, 'stock' => 15],
            ['id' => 5, 'store_id' => 1, 'category_id' => 4, 'name' => 'Pisau Set Dapur (Isi 5)', 'description' => 'Pisau stainless steel anti karat.', 'price' => 200000, 'stock' => 30],
            ['id' => 6, 'store_id' => 1, 'category_id' => 1, 'name' => 'Headphone Bluetooth V5.2', 'description' => 'Noise cancelling, bass mantap.', 'price' => 800000, 'stock' => 20],
            ['id' => 7, 'store_id' => 1, 'category_id' => 5, 'name' => 'Sepatu Lari Pria', 'description' => 'Sol empuk, ringan dipakai.', 'price' => 550000, 'stock' => 40],
        ];
        $products2 = [
            ['id' => 8, 'store_id' => 2, 'category_id' => 3, 'name' => 'Blouse Wanita Korea', 'description' => 'Bahan katun rayon adem.', 'price' => 150000, 'stock' => 100],
            ['id' => 9, 'store_id' => 2, 'category_id' => 3, 'name' => 'Dress Pesta Brokat', 'description' => 'Tampil elegan di acara formal.', 'price' => 450000, 'stock' => 30],
            ['id' => 10, 'store_id' => 2, 'category_id' => 3, 'name' => 'Celana Kulot Linen', 'description' => 'Nyaman untuk dipakai sehari-hari.', 'price' => 180000, 'stock' => 80],
            ['id' => 11, 'store_id' => 2, 'category_id' => 3, 'name' => 'Tunik Muslimah Modern', 'description' => 'Desain simpel dan elegan.', 'price' => 220000, 'stock' => 50],
            ['id' => 12, 'store_id' => 2, 'category_id' => 2, 'name' => 'Kaos Polos Pria (Putih)', 'description' => 'Cotton combed 30s.', 'price' => 75000, 'stock' => 200],
            ['id' => 13, 'store_id' => 2, 'category_id' => 2, 'name' => 'Jaket Denim Pria', 'description' => 'Jaket jeans tebal dan awet.', 'price' => 350000, 'stock' => 40],
            ['id' => 14, 'store_id' => 2, 'category_id' => 3, 'name' => 'Pashmina Ceruty Baby Doll', 'description' => 'Bahan jatuh dan mudah diatur.', 'price' => 45000, 'stock' => 150],
            ['id' => 15, 'store_id' => 2, 'category_id' => 3, 'name' => 'Tas Selempang Wanita', 'description' => 'Tas kulit sintetis premium.', 'price' => 190000, 'stock' => 60],
        ];
        $products3 = [
            ['id' => 16, 'store_id' => 3, 'category_id' => 4, 'name' => 'Set Spatula Silikon (Isi 3)', 'description' => 'Tahan panas dan aman untuk teflon.', 'price' => 90000, 'stock' => 70],
            ['id' => 17, 'store_id' => 3, 'category_id' => 4, 'name' => 'Panci Presto 8 Liter', 'description' => 'Mengempukkan daging dalam 15 menit.', 'price' => 380000, 'stock' => 25],
            ['id' => 18, 'store_id' => 3, 'category_id' => 4, 'name' => 'Bumbu Giling Lengkuas', 'description' => '100% lengkuas asli, kemasan 250gr.', 'price' => 25000, 'stock' => 100],
            ['id' => 19, 'store_id' => 3, 'category_id' => 4, 'name' => 'Kopi Arabika Gayo 200gr', 'description' => 'Biji kopi fresh roast.', 'price' => 80000, 'stock' => 50],
            ['id' => 20, 'store_id' => 3, 'category_id' => 1, 'name' => 'Blender Mini Portable', 'description' => 'Blender jus praktis, USB charge.', 'price' => 120000, 'stock' => 60],
        ];

        $allProducts = array_merge($products1, $products2, $products3);
        $data = [];
        $baseTime = Carbon::parse('2025-10-05 08:00:00'); // Produk mulai dibuat

        foreach ($allProducts as $index => $product) {
            // Setiap produk dibuat dengan jeda 6 jam
            $product['created_at'] = $baseTime->copy()->addHours($index * 6);
            $product['updated_at'] = $product['created_at'];
            $data[] = $product;
        }

        DB::table('products')->insert($data);
    }
}