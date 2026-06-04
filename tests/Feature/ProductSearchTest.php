<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;

class ProductSearchTest extends TestCase
{
    public function test_product_search_is_case_insensitive()
    {
        // 1. Cari produk asli di database
        $namaProdukAsli = 'Mouse Wireless Ergonomic Silent Click';
        $product = Product::where('name', $namaProdukAsli)->first();
        $this->assertNotNull($product, 'Produk Mouse Wireless tidak ditemukan di database.');

        // 2. Siapkan kata kunci pencarian menggunakan huruf kecil semua
        $keyword = strtolower($namaProdukAsli);
        
        // 3. Panggil fitur pencarian website dengan kata kunci tersebut
        $response = $this->get(route('search', ['q' => $keyword]));

        // 4. Pastikan halaman pencarian berhasil dimuat tanpa error
        $response->assertStatus(200);

        // 5. Pastikan nama produk asli tetap muncul di hasil pencarian
        $response->assertSee($namaProdukAsli);
    }
}