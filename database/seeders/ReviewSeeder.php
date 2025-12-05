<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            $this->command->info('No products found. Please seed products first.');
            return;
        }

        $now = Carbon::now();
        $comments = [
            'Produk bagus, recommended!',
            'Kualitas sesuai harga',
            'Pengiriman cepat',
            'Lumayan, tidak sesuai ekspektasi',
            'Bagus banget!',
            'Produk original dan berkualitas',
            'Pelayanan memuaskan',
            'Sesuai foto',
            'Mantap, seller responsif',
            'Tidak mengecewakan',
        ];

        // Buat beberapa reviews per produk
        foreach ($products as $product) {
            $reviewCount = rand(3, 6);
            
            for ($i = 0; $i < $reviewCount; $i++) {
                Review::create([
                    'product_id' => $product->id,
                    'name' => 'Customer ' . rand(100, 999),
                    'phone' => '08' . rand(100000000, 999999999),
                    'email' => 'customer' . rand(1, 999) . '@example.com',
                    'province' => collect(['Aceh', 'Sumatera Utara', 'Sumatera Barat', 'Riau', 'Jambi', 'Sumatera Selatan', 'Bangka Belitung', 'Bengkulu', 'Lampung', 'DKI Jakarta', 'Jawa Barat', 'Jawa Tengah', 'DI Yogyakarta', 'Jawa Timur', 'Banten', 'Bali', 'Nusa Tenggara Barat', 'Nusa Tenggara Timur', 'Kalimantan Barat', 'Kalimantan Tengah', 'Kalimantan Selatan', 'Kalimantan Timur', 'Kalimantan Utara', 'Sulawesi Utara', 'Sulawesi Tengah', 'Sulawesi Selatan', 'Sulawesi Tenggara', 'Gorontalo', 'Sulawesi Barat', 'Maluku', 'Maluku Utara', 'Papua', 'Papua Barat'])->random(),
                    'comment' => collect($comments)->random(),
                    'rating' => rand(3, 5),
                    'created_at' => $now->copy()->subDays(rand(1, 60)),
                    'updated_at' => $now->copy()->subDays(rand(1, 60)),
                ]);
            }
        }

        // Update rating untuk semua produk
        $products->each(function ($product) {
            $product->updateRating();
        });

        $this->command->info('Reviews seeded and product ratings updated successfully!');
    }
}
