<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Testing\Fluent\Concerns\Has;

class SellerSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        // Password default: 'password'
        $password = Hash::make('password'); 

        DB::table('sellers')->insert([
            [
                'id' => '11111111-1111-1111-1111-111111111111', // UUID
                'store_name' => 'Toko Budi Jaya',
                'store_description' => 'Elektronik terbaik.',
                
                // Data PIC
                'pic_name' => 'Budi Santoso',
                'pic_phone' => '081234567890',
                'pic_email' => 'budi@toko.com',
                'password' => Hash::make('123Abc##'),
                
                // Alamat
                'pic_street' => 'Jl. Merdeka No. 10',
                'pic_rt' => '001',
                'pic_rw' => '002',
                'pic_village' => 'Sukmajaya',
                'pic_city' => 'Depok',
                'pic_province' => 'Jawa Barat',
                
                // Legalitas
                'pic_ktp_number' => '3271234567890001',
                'pic_photo_path' => null,
                'pic_ktp_file_path' => null,
                
                'status' => 'ACTIVE',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => '22222222-2222-2222-2222-222222222222',
                'store_name' => 'Citra Fashion',
                'store_description' => 'Baju wanita.',
                'pic_name' => 'Citra Lestari',
                'pic_phone' => '081298765432',
                'pic_email' => 'citra@toko.com',
                'password' => $password,
                'pic_street' => 'Jl. Kartini No. 5',
                'pic_rt' => '005',
                'pic_rw' => '003',
                'pic_village' => 'Menteng',
                'pic_city' => 'Jakarta Pusat',
                'pic_province' => 'DKI Jakarta',
                'pic_ktp_number' => '3171234567890002',
                'pic_photo_path' => null,
                'pic_ktp_file_path' => null,
                'status' => 'PENDING',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}