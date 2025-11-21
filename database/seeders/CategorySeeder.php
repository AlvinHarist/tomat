<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $baseTime = Carbon::parse('2025-10-02 09:00:00'); // Mulai hari berikutnya

        $categories = [
            ['id' => 1, 'name' => 'Elektronik', 'icon' => '💡'],
            ['id' => 2, 'name' => 'Pakaian Pria', 'icon' => '👗'],
            ['id' => 3, 'name' => 'Pakaian Wanita', 'icon' => '👗'],
            ['id' => 4, 'name' => 'Kebutuhan Dapur', 'icon' => '🏠'],
            ['id' => 5, 'name' => 'Olahraga & Outdoor', 'icon' => '🏃'],
        ];

        $data = [];
        foreach ($categories as $index => $category) {
            $category['slug'] = Str::slug($category['name']);
            // Setiap kategori dibuat dengan jeda 10 menit
            $category['created_at'] = $baseTime->copy()->addMinutes($index * 10);
            $category['updated_at'] = $category['created_at'];
            $data[] = $category;
        }

        DB::table('categories')->insert($data);
    }
}