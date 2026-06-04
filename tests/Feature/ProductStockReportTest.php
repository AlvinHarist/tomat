<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ProductStockReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_can_download_stock_report_with_15_descending_stock_products(): void
    {
        $user = User::factory()->create(['role' => 'seller']);

        $seller = Seller::create([
            'user_id' => $user->id,
            'store_name' => 'Toko Report',
            'store_description' => 'Deskripsi toko report',
            'pic_phone' => '081234567891',
            'pic_street' => 'Jalan Report',
            'pic_rt' => '001',
            'pic_rw' => '002',
            'pic_village' => 'Desa Report',
            'pic_district' => 'Kecamatan Report',
            'pic_city' => 'Kota Report',
            'pic_province' => 'Provinsi Report',
            'pic_ktp_number' => '1234567890123456',
            'pic_photo_path' => 'images/ktp-report.jpg',
            'pic_ktp_file_path' => 'images/ktp-report.pdf',
            'status' => 'ACTIVE',
        ]);

        $category = Category::create([
            'name' => 'Sepatu',
            'slug' => 'sepatu',
            'description' => 'Kategori sepatu',
            'is_active' => true,
        ]);

        foreach (range(15, 1) as $stock) {
            Product::create([
                'seller_id' => $seller->id,
                'category_id' => $category->id,
                'name' => "Produk Stok $stock",
                'description' => "Deskripsi produk stok $stock",
                'price' => 100000 + $stock,
                'stock' => $stock,
                'images' => [],
            ]);
        }

        $response = $this->actingAs($user)
            ->get(route('seller.reports.stock'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringContainsString('Laporan-Produk-Stock-', $response->headers->get('content-disposition'));
        $this->assertSame(15, Product::where('seller_id', $seller->id)->count());

        $downloadDir = storage_path('app/test-downloads');
        File::ensureDirectoryExists($downloadDir);

        preg_match('/filename="(?P<name>.+?)"/', $response->headers->get('content-disposition'), $matches);
        $filename = $matches['name'] ?? 'downloaded-stock-report.pdf';
        $filePath = $downloadDir . DIRECTORY_SEPARATOR . $filename;

        file_put_contents($filePath, $response->getContent());

        $this->assertTrue(File::exists($filePath), "Expected downloaded report file to exist at {$filePath}");
    }
}
