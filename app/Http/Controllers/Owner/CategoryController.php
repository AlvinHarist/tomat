<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // 1. Tampilkan Daftar Kategori
    public function index()
    {
        // Ambil kategori induk (level 1) beserta anak-anaknya
        $categories = Category::whereNull('parent_id')
                              ->with('children.children') // Support sampai 3 level
                              ->orderBy('sort_order')
                              ->get();
        
        return view('owner.categories.index', compact('categories'));
    }

    // 2. Simpan Kategori Baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(4), // Slug unik
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'is_active' => true,
            'sort_order' => Category::max('sort_order') + 1, // Taruh paling bawah
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    // 3. Update Kategori
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // Update slug juga
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui!');
    }

    // 4. Hapus Kategori
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Hapus kategori (anak-anaknya akan ikut terhapus karena 'onDelete cascade' di migrasi)
        $category->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }
}