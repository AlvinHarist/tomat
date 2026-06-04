# DUPL_Regis_20

## Deskripsi
Pengujian white box untuk memverifikasi bahwa ketika seluruh elemen pendaftaran diisi dengan data valid dan sesuai aturan, tombol Daftar Sekarang memproses registrasi dan mengarahkan pengguna ke halaman login.

## Prosedur Pengujian
1. Mengisi form registrasi dengan seluruh field yang valid.
2. Klik Daftar Sekarang.
3. Memeriksa apakah sistem mengarahkan pengguna ke halaman login.
4. Memeriksa apakah data user dan seller tersimpan ke database.

## Masukan
- Nama toko: Toko Tomat Sejahtera
- Deskripsi: Registrasi valid untuk pengujian white box.
- Nama PIC: Budi Santoso
- Nomor telepon: 081234567890
- Email: budi.santoso@example.com
- Jalan: Jl. Contoh No. 1
- RT: 001
- RW: 002
- Provinsi: 11
- Kabupaten/Kota: 1101
- Kecamatan: 1101010
- Kelurahan: 1101010001
- Password: Password123!
- Konfirmasi password: Password123!
- Nomor KTP PIC: 1234567890123456
- Foto PIC: foto-pic.jpg
- Foto KTP: foto-ktp.jpg

## Keluaran yang Diharapkan
Berganti ke halaman login.

## Kriteria Evaluasi Hasil
Berganti ke halaman login.

## Fungsi yang Diuji
App\Http\Controllers\Auth\RegisterController::store()

## Kode Pengujian
File pengujian: [tests/Feature/DUPL_Regis_20Test.php](tests/Feature/DUPL_Regis_20Test.php)

## Hasil yang Didapatkan
- Form registrasi diproses dengan data valid.
- Data user dan seller berhasil disimpan ke database.
- Sistem mengalihkan pengguna ke halaman login.
- Pesan status registrasi berhasil ditampilkan.

## Kesimpulan
Skenario pengujian dinyatakan berhasil. Jika semua field registrasi diisi dengan data valid, tombol Daftar Sekarang mengarahkan pengguna ke halaman login sesuai kriteria.