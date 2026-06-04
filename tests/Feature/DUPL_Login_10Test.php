<?php

namespace Tests\Feature;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DUPL_Login_10Test extends TestCase
{
    use RefreshDatabase;

    public function test_login_penjual_dengan_email_aktif_dan_password_benar_mengarahkan_ke_dashboard_toko(): void
    {
        $plainPassword = 'Password123!';

        $user = User::factory()->create([
            'name' => 'Seller Aktif',
            'email' => 'seller.aktif@example.com',
            'password' => $plainPassword,
            'role' => 'seller',
            'email_verified_at' => now(),
        ]);

        Seller::create([
            'user_id' => $user->id,
            'store_name' => 'Toko Tomat Sejahtera',
            'store_description' => 'Data uji white box untuk login seller berhasil.',
            'pic_phone' => '081234567890',
            'pic_street' => 'Jl. Contoh No. 1',
            'pic_rt' => '001',
            'pic_rw' => '002',
            'pic_village' => 'Kelurahan Contoh',
            'pic_district' => 'Kecamatan Contoh',
            'pic_city' => 'Kota Contoh',
            'pic_province' => 'Provinsi Contoh',
            'pic_ktp_number' => '1234567890123456',
            'pic_photo_path' => 'seller/photos/pic.jpg',
            'pic_ktp_file_path' => 'seller/ktp/ktp.pdf',
            'status' => 'ACTIVE',
        ]);

        $loginIdentifier = User::query()->whereKey($user->id)->value('email');

        $response = $this->post(route('login.submit'), [
            'login_identifier' => $loginIdentifier,
            'password' => $plainPassword,
        ]);

        $response->assertRedirect(route('seller.dashboard'));
        $this->assertAuthenticatedAs($user);
    }
}