<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Format JSON untuk gambar
        $imagesLaptop = json_encode(['laptop_front.jpg', 'laptop_side.jpg']);
        $imagesKemeja = json_encode(['kemeja_blue.jpg']);

        DB::table('products')->insert([
            [
                'id' => 'p1111111-1111-1111-1111-111111111111',
                'seller_id' => '11111111-1111-1111-1111-111111111111', // Milik Budi
                'category_id' => 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', // Elektronik
                'name' => 'Laptop Gaming High End',
                'description' => 'Laptop super kencang untuk main game rata kanan.',
                'price' => 15000000,
                'stock' => 10,
                'images' => $imagesLaptop,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 'p2222222-2222-2222-2222-222222222222',
                'seller_id' => '11111111-1111-1111-1111-111111111111', // Milik Budi
                'category_id' => 'bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb', // Fashion Pria
                'name' => 'Kemeja Flanel Kotak',
                'description' => 'Bahan adem dan nyaman dipakai.',
                'price' => 150000,
                'stock' => 50,
                'images' => $imagesKemeja,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}