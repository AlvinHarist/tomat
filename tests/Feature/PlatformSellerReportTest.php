<?php

namespace Tests\Feature;

use App\Models\Seller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class PlatformSellerReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_download_seller_status_report_pdf(): void
    {
        // 1. Setup: Buat akun Owner (Admin/Pemilik Platform) MENGGUNAKAN MODEL USER
        $owner = User::create([
            'name' => 'Owner Admin',
            'email' => 'admin@tomat.com',
            'password' => Hash::make('password123'),
            'role' => 'owner',
        ]);

        $now = Carbon::now();

        // 2. Setup: Buat 5 data Seller dengan status 'ACTIVE'
        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'name' => "Seller User $i",
                'email' => "seller$i@mail.com",
                'password' => Hash::make('password123'),
                'role' => 'seller',
            ]);

            Seller::create([
                'id' => Str::uuid()->toString(),
                'user_id' => $user->id,
                'store_name' => "Toko Laporan $i",
                'store_description' => "Deskripsi toko laporan $i",
                'pic_phone' => "08123456789$i",
                'pic_street' => 'Jalan Report',
                'pic_rt' => '001',
                'pic_rw' => '002',
                'pic_village' => 'Desa Report',
                'pic_district' => 'Kecamatan Report',
                'pic_city' => 'Kota Report',
                'pic_province' => 'Provinsi Report',
                'pic_ktp_number' => "123456789012345$i",
                'pic_photo_path' => 'images/ktp-report.jpg',
                'pic_ktp_file_path' => 'images/ktp-report.pdf',
                'status' => 'ACTIVE',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 3. Masukan: Filter status dan rentang tanggal
        $requestData = [
            'status' => 'ACTIVE',
            'start_date' => $now->copy()->subDays(7)->format('Y-m-d'), 
            'end_date' => $now->copy()->addDays(7)->format('Y-m-d'),   
        ];

        // 4. Eksekusi: Login sebagai owner dan hit endpoint menggunakan GET
        $response = $this->actingAs($owner)
            ->get(route('owner.reports.seller_status', $requestData));

        // 5. Evaluasi Hasil
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertTrue($response->headers->has('content-disposition'));
        $this->assertSame(5, Seller::where('status', 'ACTIVE')->count());

        // 6. Menyimpan file PDF untuk testing
        $downloadDir = storage_path('app/test-downloads');
        File::ensureDirectoryExists($downloadDir);

        preg_match('/filename="(?P<name>.+?)"/', $response->headers->get('content-disposition'), $matches);
        
        $filename = $matches['name'] ?? 'downloaded-seller-report.pdf';
        $filePath = $downloadDir . DIRECTORY_SEPARATOR . $filename;

        file_put_contents($filePath, $response->getContent());

        $this->assertTrue(File::exists($filePath), "Expected downloaded report file to exist at {$filePath}");
    }
}