<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Str;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            $this->command->info('No products found. Please seed products first.');
            return;
        }

        foreach ($products as $product) {
            Review::create([
                'id'         => (string) Str::uuid(),
                'name'       => 'User Dummy ' . rand(1, 100),
                'phone'      => '08' . rand(100000000, 999999999),
                'email'      => 'user' . rand(1, 100) . '@example.com',
                'province'   => 'Province ' . rand(1, 5),
                'product_id' => $product->id,
                'comment'    => 'This is a sample review for product ' . $product->name,
                'rating'     => rand(3, 5),
            ]);
        }
    }
}
