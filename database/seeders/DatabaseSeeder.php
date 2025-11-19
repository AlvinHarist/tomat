<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // Kosongkan tabel (urutan dari 'anak' ke 'induk')
        DB::table('review_and_ratings')->truncate();
        DB::table('products')->truncate();
        DB::table('stores')->truncate();
        DB::table('seller_profiles')->truncate(); // <-- TAMBAHKAN
        DB::table('categories')->truncate();
        DB::table('users')->truncate();

        Schema::enableForeignKeyConstraints();

        // Panggil seeder individual (urutan dari 'induk' ke 'anak')
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            SellerProfileSeeder::class, // <-- TAMBAHKAN
            StoreSeeder::class,
            ProductSeeder::class,
            ReviewAndRatingSeeder::class,
        ]);
    }
}