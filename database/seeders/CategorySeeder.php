<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('categories')->insert([
            ['id' => 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'name' => 'Elektronik', 'description' => 'Gadget dan alat listrik', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 'bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb', 'name' => 'Fashion Pria', 'description' => 'Pakaian pria', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 'cccccccc-cccc-cccc-cccc-cccccccccccc', 'name' => 'Fashion Wanita', 'description' => 'Pakaian wanita', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 'dddddddd-dddd-dddd-dddd-dddddddddddd', 'name' => 'Kesehatan', 'description' => 'Obat dan vitamin', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 'eeeeeeee-eeee-eeee-eeee-eeeeeeeeeeee', 'name' => 'Otomotif', 'description' => 'Suku cadang kendaraan', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}