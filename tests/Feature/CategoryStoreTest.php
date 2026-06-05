<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User; 
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class CategoryStoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * DUPL-KelolaKategori-01
     * Pengujian pemilik platform menambahkan kategori utama dengan nama valid.
     *
     * @return void
     */
    public function test_pengujian_menyimpan_kategori_utama_dengan_masukan_valid()
    {
        // 1. Setup: Membuat akun Pemilik Platform menggunakan model User dengan role 'owner'
        $owner = User::create([
            'name' => 'Owner Admin',
            'email' => 'admin@tomat.com',
            'password' => Hash::make('password123'),
            'role' => 'owner', 
        ]);

        // 2. Pemilik platform login 
        $this->actingAs($owner);

        // 3. Masukan: Mengisi nama kategori utama (tanpa parent/induk)
        $categoryData = [
            'name' => 'Elektronik',
            'parent_id' => null 
        ];

        // 4. Eksekusi: Menekan tombol simpan
        $response = $this->from(route('owner.categories.index'))
                         ->post(route('owner.categories.store'), $categoryData);

        // 5. Evaluasi Hasil: Sistem mengarahkan kembali ke daftar kategori dengan pesan sukses
        $response->assertRedirect(route('owner.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'name' => 'Elektronik',
            'parent_id' => null
        ]);
    }
}