<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SellerSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('sellers')->insert([
            [
                'id' => '11111111-1111-1111-1111-111111111111', // UUID
                'user_id' => 2, // Budi Penjual from UserSeeder
                'store_name' => 'Toko Budi Jaya',
                'store_description' => 'Elektronik terbaik.',
                
                // Alamat
                'pic_phone' => '081234567890',
                'pic_street' => 'Jl. Merdeka No. 10',
                'pic_rt' => '001',
                'pic_rw' => '002',
                'pic_village' => 'Sukmajaya',
                'pic_district' => 'Sukamundur',
                'pic_city' => 'Depok',
                'pic_province' => 'Jawa Barat',
                
                // Legalitas
                'pic_ktp_number' => '3271234567890001',
                'pic_photo_path' => 'xxxxxxxxxxxxxxxxxxxxx',
                'pic_ktp_file_path' => 'xxxxxxxxxxxxxxxxxxxxx',
                
                'status' => 'ACTIVE',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => '22222222-2222-2222-2222-222222222222',
                'user_id' => 3, // Citra Penjual from UserSeeder
                'store_name' => 'Citra Fashion',
                'store_description' => 'Baju wanita.',
                'pic_phone' => '081298765432',
                'pic_street' => 'Jl. Kartini No. 5',
                'pic_rt' => '005',
                'pic_rw' => '003',
                'pic_village' => 'Menteng',
                'pic_district' => 'Genteng',
                'pic_city' => 'Jakarta Pusat',
                'pic_province' => 'DKI Jakarta',
                'pic_ktp_number' => '3171234567890002',
                'pic_photo_path' => 'xxxxxxxxxxxxxxxxxxxxx',
                'pic_ktp_file_path' => 'xxxxxxxxxxxxxxxxxxxxx',
                'status' => 'PENDING',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => '33333333-3333-3333-3333-333333333333',
                'user_id' => 4, // Dedi Penjual from UserSeeder
                'store_name' => 'Dedi Gadget',
                'store_description' => 'Gadget murah meriah.',
                'pic_phone' => '081345678901',
                'pic_street' => 'Jl. Sudirman No. 88',
                'pic_rt' => '002',
                'pic_rw' => '001',
                'pic_village' => 'Dago',
                'pic_district' => 'Bandung Wetan',
                'pic_city' => 'Bandung',
                'pic_province' => 'Jawa Barat',
                'pic_ktp_number' => '3271234567890003',
                'pic_photo_path' => 'xxxxxxxxxxxxxxxxxxxxx',
                'pic_ktp_file_path' => 'xxxxxxxxxxxxxxxxxxxxx',
                'status' => 'REJECTED',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}