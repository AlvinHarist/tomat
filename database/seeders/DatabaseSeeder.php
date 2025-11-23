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
        DB::table('products')->truncate();
        DB::table('categories')->truncate();

        Schema::enableForeignKeyConstraints();

        // Panggil seeder individual (urutan dari 'induk' ke 'anak')
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}