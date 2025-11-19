<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // <-- Tambahkan ini

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $baseTime = Carbon::parse('2025-10-03 10:00:00');

        $stores = [
            [
                'id' => 1,
                'user_id' => 2, // Milik Budi
                'store_name' => 'Toko Budi Jaya',
                'description' => 'Toko serba ada Budi Jaya.',
                'province' => 'Jawa Barat',
                'city' => 'Depok',
                'district' => 'Sukmajaya',
                'address_details' => 'Dekat Tugu',
            ],
            [
                'id' => 2,
                'user_id' => 3, // Milik Citra
                'store_name' => 'Citra Fashion',
                'description' => 'Toko pakaian wanita modern.',
                'province' => 'Jawa Barat',
                'city' => 'Bandung',
                'district' => 'Kiaracondong',
                'address_details' => 'Lantai 2',
            ],
            [
                'id' => 3,
                'user_id' => 4, // Milik Dedi
                'store_name' => 'Dapur Sehat Dedi',
                'description' => 'Menjual bumbu dan alat masak.',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Selatan',
                'district' => 'Cilandak',
                'address_details' => 'Sebelah Apotek',
            ],
        ];
        
        $data = [];
        foreach ($stores as $index => $store) {
            $store['created_at'] = $baseTime->copy()->addDays($index);
            $store['updated_at'] = $store['created_at'];
            $data[] = $store;
        }
        
        DB::table('stores')->insert($data);
    }
}