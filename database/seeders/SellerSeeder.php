<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SellerSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('sellers')->insert([
            [
                'id' => '11111111-1111-1111-1111-111111111111', // UUID Statis
                'store_name' => 'Toko Budi Jaya',
                'store_description' => 'Menjual elektronik terbaik.',
                'pic_name' => 'Budi Santoso',
                'pic_phone' => '081234567890',
                'pic_email' => 'budi@toko.com',
                'password' => Hash::make('password'), // Password default
                'pic_street' => 'Jl. Merdeka No. 10',
                'pic_rt' => '001',
                'pic_rw' => '002',
                'pic_village' => 'Sukmajaya',
                'pic_city' => 'Depok',
                'pic_province' => 'Jawa Barat',
                'pic_ktp_number' => '3271234567890001',
                'pic_photo_path' => 'profiles/budi.jpg',
                'pic_ktp_file_path' => 'ktp/budi_ktp.jpg',
                'status' => 'ACTIVE',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => '22222222-2222-2222-2222-222222222222',
                'store_name' => 'Citra Fashion',
                'store_description' => 'Fashion wanita kekinian.',
                'pic_name' => 'Citra Lestari',
                'pic_phone' => '081298765432',
                'pic_email' => 'citra@toko.com',
                'password' => Hash::make('password'),
                'pic_street' => 'Jl. Kartini No. 5',
                'pic_rt' => '005',
                'pic_rw' => '003',
                'pic_village' => 'Menteng',
                'pic_city' => 'Jakarta Pusat',
                'pic_province' => 'DKI Jakarta',
                'pic_ktp_number' => '3171234567890002',
                'pic_photo_path' => 'profiles/citra.jpg',
                'pic_ktp_file_path' => 'ktp/citra_ktp.jpg',
                'status' => 'PENDING', // Masih menunggu verifikasi
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => '33333333-3333-3333-3333-333333333333',
                'store_name' => 'Dedi Gadget',
                'store_description' => 'Gadget murah meriah.',
                'pic_name' => 'Dedi Mulyadi',
                'pic_phone' => '081345678901',
                'pic_email' => 'dedi@toko.com',
                'password' => Hash::make('password'),
                'pic_street' => 'Jl. Sudirman No. 88',
                'pic_rt' => '002',
                'pic_rw' => '001',
                'pic_village' => 'Dago',
                'pic_city' => 'Bandung',
                'pic_province' => 'Jawa Barat',
                'pic_ktp_number' => '3271234567890003',
                'pic_photo_path' => 'profiles/dedi.jpg',
                'pic_ktp_file_path' => 'ktp/dedi_ktp.jpg',
                'status' => 'REJECTED',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}