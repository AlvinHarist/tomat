# ToMaT Marketplace

Aplikasi marketplace sederhana berbasis Laravel. Repo ini adalah sebuah web app marketplace tanpa fitur transaksi atau pembayaran. Fokus core app adalah iklan produk, review pengguna, manajemen seller, dan dashboard laporan.

## Deskripsi

- Aplikasi dibangun dengan Laravel 10 dan PHP 8.1.
- Penyimpanan data model menggunakan Eloquent ORM.
- Tidak ada fitur transaksi, checkout, atau payment gateway. Aplikasi hanya menyediakan listing produk dan review.
- Ada dua peran utama:
    - `seller`: penjual yang mendaftarkan toko dan mengunggah produk.
    - `owner`: admin yang memverifikasi seller dan mengelola kategori serta laporan.
- Alur utama:
    1. Seller mendaftar melalui halaman registrasi dengan data PIC dan bukti KTP.
    2. Owner memverifikasi status seller (`PENDING`, `ACTIVE`, `REJECTED`).
    3. Produk seller tampil di halaman publik dan bisa dicari.
    4. User publik dapat memberi review dan rating tanpa login.
- Review otomatis memperbarui `avg_rating` dan `review_count` produk setiap kali review dibuat, diubah, atau dihapus.
- Kategori mendukung struktur hirarkis parent/child dan dipakai untuk filter pencarian.

## Fitur Utama

- Public product listing dan search/filter:
    - pencarian teks (`q`)
    - kategori berjenjang
    - filter harga
    - filter provinsi seller
    - filter minimum rating
- Seller panel:
    - CRUD produk
    - upload sampai 10 gambar produk
    - dashboard statistik produk, review, dan distribusi reviewer
    - laporan PDF untuk stok, rating, dan restock
- Owner panel:
    - verifikasi seller
    - daftar seller dan detail KTP
    - manajemen kategori hirarkis
    - laporan statistik seller/product/review
- Pendaftaran seller menggunakan paket `laravolt/indonesia` untuk dropdown provinsi/kota/kecamatan/kelurahan.
- Autentikasi dan email verification menggunakan Laravel built-in.

## Struktur Domain

- `User`
    - atribut penting: `name`, `email`, `password`, `role`, `email_verified_at`
    - role utama: `seller`, `owner`
- `Seller`
    - relasi `user_id` ke `users`
    - menyimpan profil toko dan detail PIC, alamat, KTP, status verifikasi
    - menggunakan UUID sebagai primary key
- `Product`
    - relasi ke `seller` dan `category`
    - menyimpan `images` sebagai array JSON
    - atribut rating: `avg_rating`, `review_count`
- `Category`
    - hirarki parent / children
    - mendukung query descendant recursive untuk filter seluruh subkategori
- `Review`
    - `product_id`, `name`, `phone`, `email`, `province`, `rating`, `comment`
    - rating wajib antara 1 dan 5

## Tech Stack

- PHP 8.1
- Laravel 10.x
- Laravel Sanctum
- Laravel Dompdf
- Tailwind CSS 4
- Vite
- Axios
- SweetAlert2
- laravolt/indonesia
- PHPUnit / Laravel Pint untuk testing dan tooling

## Definisi & Singkatan

- `PIC`: Person In Charge (penanggung jawab toko / kontak seller)
- `CRUD`: Create, Read, Update, Delete
- `ROLE`: sistem peran untuk akses; `seller` untuk penjual, `owner` untuk admin/verifikator
- `PENDING`: status seller menunggu verifikasi owner
- `ACTIVE`: status seller disetujui dan dapat masuk panel seller
- `REJECTED`: status seller ditolak, bisa melakukan registrasi ulang
- `UUID`: Universally Unique Identifier, dipakai untuk `sellers` dan `reviews`
- `avg_rating`: rata-rata nilai bintang produk
- `review_count`: jumlah total ulasan produk
- `ToMaT`: nama repo/aplikasi marketplace

## Catatan Khusus

- Aplikasi berfokus pada listing produk dan review, bukan transaksi.
- Verifikasi email dilakukan dengan signed route `verification.verify`.
- Gambar produk disimpan di public storage dan folder `images/products`.
- Kategori bisa ditambahkan, diubah, dan dihapus dari panel owner.
