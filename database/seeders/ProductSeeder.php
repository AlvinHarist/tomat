<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;
use App\Models\Seller;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil kategori leaf
        $leafCategories = Category::whereDoesntHave('children')->get();

        if ($leafCategories->count() === 0) {
            $this->command->warn("Tidak ditemukan kategori leaf. Jalankan CategorySeeder dulu.");
            return;
        }

        // Ambil semua seller ID
        $sellers = Seller::pluck('id');

        if ($sellers->count() === 0) {
            $this->command->warn("Tidak ada seller ditemukan. Jalankan SellerSeeder dulu.");
            return;
        }

        // Produk
        $productNames = [
            'Monitor Portabel 8 Inch IPS USB Display'      => 'images/products/monitor.png',
            'Headphone Wireless Bluetooth HD Audio'        => 'images/products/headphone.jpg',
            'Keyboard Mechanical RGB 87 Keys'              => 'images/products/keyboard.webp',
            'Tas Selempang Kanvas Waterproof'              => 'images/products/tas.jpg',
            'Smartwatch Fitness Tracker OLED Display'      => 'images/products/jam.jpg',
            'Kamera Mini HD 1080p Motion Detection'        => 'images/products/kamera.jpeg',
            'Bluetooth Speaker Bass Boost'                 => 'images/products/speaker.jpg',
            'Tripod Kamera Portable Aluminium'             => 'images/products/tripod.jpg',
            'Webcam Full HD 1080p Autofocus'               => 'images/products/webcam.webp',
            'Charger USB Fast Charging 3A'                 => 'images/products/charger.jpg',
            'Powerbank 20000mAh Quickcharge'               => 'images/products/powerbank.webp',
            'Lampu LED Smart RGB Wifi Control'             => 'images/products/lampu.jpg',
            'Flashdisk 64GB USB 3.0 High Speed'            => 'images/products/flashdisk.jpg',
            'Mouse Wireless Ergonomic Silent Click'        => 'images/products/mouse.png',
            'Router Wifi Dual Band High Speed'             => 'images/products/router.jpg',
            'SSD NVMe 256GB Ultra Fast'                    => 'images/products/ssd.jpg',
            'Microphone Podcast Condenser USB'             => 'images/products/mic.jpg',
            'Gaming Mousepad XXL Anti Slip'                => 'images/products/mousepad.jpg',
            'Cooling Pad Laptop 5 Fan RGB'                 => 'images/products/coolingpad.jpg',
            'Drone Mini Camera 4K Ultra HD'                => 'images/products/drone.png',
            'Printer Inkjet Wireless Home Office'          => 'images/products/printer.jpg',
            'TV Box Android 4K Streaming Player'           => 'images/products/tvbox.jpg',
            'Kipas Angin Portable Rechargeable'            => 'images/products/kipas.jpg',
            'Mesin Pembuat Kopi Mini Travel'               => 'images/products/mesinkopi.jpg',
            'Timbangan Digital Portable Akurat'            => 'images/products/timbangan.jpg',
        ];

        foreach ($productNames as $name => $imagePath) {

            $category = $leafCategories->random();
            $sellerId = $sellers->random(); // Pilih seller random

            Product::create([
                'id'          => Str::uuid(),
                'name'        => $name,
                'description' => "Deskripsi produk: {$name}. Produk berkualitas tinggi.",
                'price'       => rand(50000, 500000),
                'stock'       => rand(5, 100),

                'category_id' => $category->id,
                'seller_id'   => $sellerId,
                'images'      => $imagePath,
            ]);
        }
    }
}
