<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class ProductController extends Controller
{
    // Tampilkan daftar produk milik penjual
    public function index()
    {
        $products = Product::where('seller_id', Auth::guard('seller')->id())->get();
        return view('seller.products.index', compact('products'));
    }

    // Tampilkan form untuk membuat produk baru (Upload Produk)
    public function create()
    {
        $categories = Category::all(); // Ambil semua kategori untuk dropdown
        return view('seller.products.create', compact('categories'));
    }

    // Simpan produk baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'images' => 'nullable|array|max:5', // Maksimal 5 gambar
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Setiap file harus gambar
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Simpan gambar ke storage (misalnya: storage/app/public/product_images)
                $path = $image->store('product_images', 'public'); 
                $imagePaths[] = $path;
            }
        }

        Product::create([
            'id' => Uuid::uuid4()->toString(),
            'seller_id' => Auth::guard('seller')->id(),
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'images' => $imagePaths, // Simpan array path gambar sebagai JSON
        ]);

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil diunggah!');
    }

    // ... metode show, edit, update, destroy lainnya akan ditambahkan nanti
}