<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // <-- Tambahkan ini

class SellerProfileSeeder extends Seeder
{
    public function run(): void
    {
        $baseTime = Carbon::parse('2025-10-03 11:00:00'); // Waktu setelah user dibuat

        $profiles = [
            [
                'id' => 1,
                'user_id' => 2, // Milik Budi
                'phone' => '081234567890',
                'ktp_number' => '3270010020030004',
                'ktp_file' => 'uploads/ktp/ktp_budi.jpg', // Path dummy
                'address' => 'Jl. Merdeka No. 10',
                'rt' => '001',
                'rw' => '002',
                'village' => 'Sukmajaya',
            ],
            [
                'id' => 2,
                'user_id' => 3, // Milik Citra
                'phone' => '081222223333',
                'ktp_number' => '3271112233440005',
                'ktp_file' => 'uploads/ktp/ktp_citra.jpg',
                'address' => 'Jl. Kartini No. 20',
                'rt' => '003',
                'rw' => '004',
                'village' => 'Mekarwangi',
            ],
            [
                'id' => 3,
                'user_id' => 4, // Milik Dedi
                'phone' => '081333334444',
                'ktp_number' => '3170112233440006',
                'ktp_file' => 'uploads/ktp/ktp_dedi.jpg',
                'address' => 'Jl. Sudirman No. 30',
                'rt' => '005',
                'rw' => '006',
                'village' => 'Cipete',
            ],
        ];

        $data = [];
        foreach ($profiles as $index => $profile) {
            $profile['created_at'] = $baseTime->copy()->addMinutes($index * 5);
            $profile['updated_at'] = $profile['created_at'];
            $data[] = $profile;
        }

        DB::table('seller_profiles')->insert($data);
    }
}