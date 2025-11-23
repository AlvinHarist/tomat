<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Review;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil hanya kategori yang tidak punya child (kategori akhir)
        $leafCategories = Category::whereDoesntHave('children')->get();

        if ($leafCategories->count() === 0) {
            $this->command->warn("Tidak ditemukan kategori leaf. Jalankan CategorySeeder dulu.");
            return;
        }

        $productNames = [
            'Monitor Portabel 8 Inch IPS USB Display',
            'Headphone Wireless Bluetooth HD Audio',
            'Keyboard Mechanical RGB 87 Keys',
            'Tas Selempang Kanvas Waterproof',
            'Smartwatch Fitness Tracker OLED Display',
            'Kamera Mini HD 1080p Motion Detection',
            'Bluetooth Speaker Bass Boost',
            'Tripod Kamera Portable Aluminium',
            'Webcam Full HD 1080p Autofocus',
            'Charger USB Fast Charging 3A',
            'Powerbank 20000mAh Quickcharge',
            'Lampu LED Smart RGB Wifi Control',
            'Flashdisk 64GB USB 3.0 High Speed',
            'Mouse Wireless Ergonomic Silent Click',
            'Router Wifi Dual Band High Speed',
            'SSD NVMe 256GB Ultra Fast',
            'Microphone Podcast Condenser USB',
            'Gaming Mousepad XXL Anti Slip',
            'Cooling Pad Laptop 5 Fan RGB',
            'Drone Mini Camera 4K Ultra HD',
            'Printer Inkjet Wireless Home Office',
            'TV Box Android 4K Streaming Player',
            'Kipas Angin Portable Rechargeable',
            'Mesin Pembuat Kopi Mini Travel',
            'Timbangan Digital Portable Akurat',
        ];

        $sampleComments = [
            "Barangnya bagus, sesuai deskripsi!",
            "Kualitas oke, harga terjangkau.",
            "Pengiriman cepat dan rapi.",
            "Lumayan, tapi ada sedikit cacat.",
            "Sangat puas! Produk premium.",
            "Seller responsif, rekomendasi.",
            "Performa bagus, sesuai ekspektasi.",
            "Barang ori, packing aman.",
            "Sesuai harga, tidak mengecewakan.",
            "Top banget, akan beli lagi!",
        ];

        foreach ($productNames as $name) {

            // Pilih kategori leaf secara acak
            $category = $leafCategories->random();

            // Buat produk
            $product = Product::create([
                'id'          => Str::uuid(),
                'name'        => $name,
                'description' => "Deskripsi produk: {$name}. Produk berkualitas tinggi.",
                'price'       => rand(50000, 500000),
                'stock'       => rand(5, 100),

                'category_id' => $category->id,
                'seller_id'   => null,
                'images'      => null,
            ]);

            // Generate antara 1–5 komentar
            $totalComments = rand(1, 5);

            for ($i = 0; $i < $totalComments; $i++) {
                Review::create([
                    'id'         => Str::uuid(),
                    'product_id' => $product->id,
                    'visitor_id' => null,
                    'comment'    => $sampleComments[array_rand($sampleComments)],
                    'rating'     => rand(3, 5),
                ]);
            }
        }
    }
}
