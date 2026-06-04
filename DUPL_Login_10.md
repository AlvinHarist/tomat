# DUPL_Login_10

## Deskripsi
Pengujian white box untuk memverifikasi bahwa pengguna seller yang status akunnya aktif, emailnya sudah terverifikasi, dan kata sandinya benar dapat masuk ke sistem lalu diarahkan ke dashboard toko.

## Prosedur Pengujian
1. Menyiapkan data seller aktif di database beserta data user yang sudah terverifikasi.
2. Mengambil email aktif dari database sebagai masukan `login_identifier`.
3. Memasukkan password yang benar.
4. Menjalankan proses login melalui route `login.submit`.
5. Memeriksa apakah aplikasi mengalihkan pengguna ke dashboard toko.

## Masukan
- Email aktif dari database: `seller.aktif@example.com`
- Password benar: `Password123!`

## Keluaran yang Diharapkan
Halaman berganti ke dashboard toko.

## Kriteria Evaluasi Hasil
Halaman berganti ke dashboard toko.

## Fungsi yang Diuji
`App\Http\Controllers\Auth\LoginController::login()`

## Kode Pengujian
File pengujian: [tests/Feature/DUPL_Login_10Test.php](tests/Feature/DUPL_Login_10Test.php)

```php
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
```

## Hasil yang Didapatkan
- Data seller dan user berhasil dibuat di database pengujian.
- Login menggunakan email aktif dan password yang benar berhasil diproses.
- Sistem mengalihkan pengguna ke route `seller.dashboard`.
- Status autentikasi user terdeteksi aktif pada session pengujian.

## Kesimpulan
Skenario login seller valid dinyatakan berhasil. Jika email aktif dan password benar diberikan, sistem mengarahkan pengguna ke dashboard toko sesuai ekspektasi.