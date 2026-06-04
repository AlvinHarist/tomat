<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ProductStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_can_store_product_with_valid_data_and_image(): void
    {
        $user = User::factory()->create(['role' => 'seller']);

        $seller = Seller::create([
            'user_id' => $user->id,
            'store_name' => 'Toko Test',
            'store_description' => 'Deskripsi toko test',
            'pic_phone' => '081234567890',
            'pic_street' => 'Jalan Contoh',
            'pic_rt' => '001',
            'pic_rw' => '002',
            'pic_village' => 'Desa Contoh',
            'pic_district' => 'Kecamatan Contoh',
            'pic_city' => 'Kota Contoh',
            'pic_province' => 'Provinsi Contoh',
            'pic_ktp_number' => '1234567890123456',
            'pic_photo_path' => 'images/ktp.jpg',
            'pic_ktp_file_path' => 'images/ktp-file.pdf',
            'status' => 'ACTIVE',
        ]);

        $category = Category::create([
            'name' => 'Sepatu',
            'slug' => 'sepatu',
            'description' => 'Kategori sepatu',
            'is_active' => true,
        ]);

        $temp = tmpfile();
        $tempPath = stream_get_meta_data($temp)['uri'];
        file_put_contents($tempPath, base64_decode(
            '/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxISEhIQEBAQEA8QDQ8PEA8PEA8QDw8QFREWFhURFRUYHSggGBolGxUVITEhJSkrLi4uGB8zODMtNygtLisBCgoKDg0OGhAQFy0dHR0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLf/AABEIAA8AEAMBIgACEQEDEQH/xAAWAAEBAQAAAAAAAAAAAAAAAAABAgP/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIQAxAAAAH/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oACAEDAQE/AFP/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oACAECAQE/AFP/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/9oACAEBAAE/AFP/2Q=='
        ));
        $uploadedImage = new UploadedFile($tempPath, 'shoe.jpg', 'image/jpeg', null, true);

        File::ensureDirectoryExists(public_path('images/products'));

        $response = $this->actingAs($user)
            ->post(route('seller.products.store'), [
                'name' => 'Aerostreet Austin Coklat Gum Biru Tua Biru Tua - Sepatu Sneakers Casual',
                'description' => "Aerostreet Austin Sneakers Casual – Stylish, Nyaman & Tahan Lama untuk Daily Activity\n\nAerostreet Austin hadir dengan desain clean dan versatile, cocok dipakai untuk aktivitas sehari-hari. Kombinasi warna natural bikin sepatu ini mudah dipadukan dengan berbagai outfit, dari casual sampai semi sporty look.\n\nMenggunakan material kombinasi textile, mesh, dan kulit sintetis yang breathable, ringan, dan tetap nyaman dipakai seharian\n\nKeunggulan:\nDesain casual minimalis, cocok untuk pria & wanita\nMaterial breathable, tidak gerah saat dipakai lama\nSol empuk dan ringan untuk aktivitas harian\nMudah dipadukan dengan berbagai style outfit\nMenggunakan teknologi Shoes Injection Mould, dimana bahan sole dicairkan dengan tekanan tinggi dan menyatu sempurna dengan bagian atas sepatu tanpa lem. Lebih kuat, tahan lama, dan tidak mudah rusak setelah dicuci atau terkena air.\n\nSpesifikasi:\nBahan: Textile - Mesh - Kulit sintetis\nSize: 37\nWarna: Natural, Hitam, Abu Tua, dan lainnya\n\nSemua produk 100% original\nStok tersedia dan siap dikirim\n\nRESELLER dan DROPSHIPPER very welcome\nDapatkan discount spesial dari pabrik langsung\n\nAerostreet — now everyone can buy a good shoe",
                'price' => 210000,
                'stock' => 5,
                'category_id' => $category->id,
                'images' => [$uploadedImage],
            ]);

        $response->assertRedirect(route('seller.products.index'));
        $response->assertSessionHas('success', 'Produk berhasil ditambahkan!');

        $this->assertDatabaseHas('products', [
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'name' => 'Aerostreet Austin Coklat Gum Biru Tua Biru Tua - Sepatu Sneakers Casual',
            'price' => 210000,
            'stock' => 5,
        ]);

        $product = Product::first();
        $this->assertNotNull($product);
        $this->assertSame(1, count($product->images));
        $this->assertStringContainsString('images/products/', $product->images[0]);

        $storedImage = public_path($product->images[0]);
        if (File::exists($storedImage)) {
            File::delete($storedImage);
        }
    }
}
