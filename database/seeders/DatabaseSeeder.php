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

        DB::table('reviews')->truncate();
        DB::table('products')->truncate();
        DB::table('categories')->truncate();
        DB::table('sellers')->truncate();

        Schema::enableForeignKeyConstraints();

        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            SellerSeeder::class,
            ProductSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}