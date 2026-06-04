<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Seller;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DUPL_Regis_20Test extends TestCase
{
    use RefreshDatabase;

    public function test_daftar_sekarang_dengan_seluruh_data_valid_mengarahkan_ke_halaman_login(): void
    {
        Storage::fake('public');

        DB::table('indonesia_provinces')->insert([
            'code' => '11',
            'name' => 'Provinsi Contoh',
            'meta' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('indonesia_cities')->insert([
            'code' => '1101',
            'province_code' => '11',
            'name' => 'Kota Contoh',
            'meta' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('indonesia_districts')->insert([
            'code' => '1101010',
            'city_code' => '1101',
            'name' => 'Kecamatan Contoh',
            'meta' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('indonesia_villages')->insert([
            'code' => '1101010001',
            'district_code' => '1101010',
            'name' => 'Kelurahan Contoh',
            'meta' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->post(route('register.store'), [
            'store_name' => 'Toko Tomat Sejahtera',
            'description' => 'Registrasi valid untuk pengujian white box.',
            'name' => 'Budi Santoso',
            'phone' => '081234567890',
            'email' => 'budi.santoso@example.com',
            'jalan' => 'Jl. Contoh No. 1',
            'rt' => '001',
            'rw' => '002',
            'provinsi' => '11',
            'kabupatenkota' => '1101',
            'kecamatan' => '1101010',
            'kelurahan' => '1101010001',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'ktp_number' => '1234567890123456',
            'photo' => UploadedFile::fake()->image('foto-pic.jpg'),
            'ktp_file' => UploadedFile::fake()->image('foto-ktp.jpg'),
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status', 'Registrasi berhasil! Mohon tunggu verifikasi dari admin.');
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'email' => 'budi.santoso@example.com',
            'role' => 'seller',
        ]);

        $this->assertDatabaseHas('sellers', [
            'pic_phone' => '081234567890',
            'pic_ktp_number' => '1234567890123456',
            'status' => 'PENDING',
        ]);
    }
}