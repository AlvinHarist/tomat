<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class OwnerSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Cek dulu biar tidak duplikat
        if (DB::table('owners')->where('email', 'owner@tomat.com')->doesntExist()) {
            DB::table('owners')->insert([
                [
                    'id' => 'a1111111-1111-1111-1111-111111111111',
                    'name' => 'Eva', // Sesuai gambar dashboard
                    'email' => 'owner@tomat.com',
                    'password' => Hash::make('123456'), // Password login
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            ]);
        }
    }
}