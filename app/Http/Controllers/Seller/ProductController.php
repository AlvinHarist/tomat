<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index()
{
    $user = auth()->user();
    $seller = $user->seller;

    if (!$seller) {
        return redirect()->route('login')->with('error', 'Data seller tidak ditemukan.');
    }

    $products = Product::where('seller_id', $seller->id)
        ->with('category')
        ->latest()
        ->paginate(10);

    return view('seller.products.index', compact('products'));
}

    
    public function create()
    {
        $categories = Category::all();
        return view('seller.products.create', compact('categories'));
    }
    
    public function store(Request $request)
{
    $seller = auth()->user()->seller;

    if (!$seller) {
        return redirect()->route('login')->with('error', 'Data seller tidak ditemukan.');
    }

    $validator = Validator::make($request->all(), [
        'name'        => 'required|string|max:255',
        'description' => 'required|string',
        'price'       => 'required|numeric|min:0',
        'stock'       => 'required|integer|min:0',
        'category_id' => 'required|exists:categories,id',
        'images'      => 'required|array|min:1|max:10',
        'images.*'    => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $imagePaths = [];

    try {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/products'), $filename);
                $imagePaths[] = 'images/products/' . $filename;
            }
        }

        Product::create([
            'seller_id'   => $seller->id,
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'images'      => $imagePaths,
        ]);

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');

    } catch (\Exception $e) {
        // delete uploaded images if error
        foreach ($imagePaths as $path) {
            $fullPath = public_path($path);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        return redirect()->back()
            ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
            ->withInput();
    }
}

    
    public function edit($id)
{
    $seller = auth()->user()->seller;

    if (!$seller) {
        return redirect()->route('login')->with('error', 'Data seller tidak ditemukan.');
    }

    $product = Product::where('seller_id', $seller->id)->findOrFail($id);
    $categories = Category::all();

    return view('seller.products.edit', compact('product', 'categories'));
}

    
    public function update(Request $request, $id)
{
    $seller = auth()->user()->seller;

    if (!$seller) {
        return redirect()->route('login')->with('error', 'Data seller tidak ditemukan.');
    }

    $product = Product::where('seller_id', $seller->id)->findOrFail($id);

    $validator = Validator::make($request->all(), [
        'name'          => 'required|string|max:255',
        'description'   => 'required|string',
        'price'         => 'required|numeric|min:0',
        'stock'         => 'required|integer|min:0',
        'category_id'   => 'required|exists:categories,id',

        'images'        => 'nullable|array|max:10',
        'images.*'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

        'delete_images'   => 'nullable|array',
        'delete_images.*' => 'string',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    try {
        $data = [
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'category_id' => $request->category_id,
        ];

        // Pastikan $existingImages itu array
        $existingImages = is_array($product->images) ? $product->images : (json_decode($product->images, true) ?: []);

        // Delete selected images
        if ($request->filled('delete_images')) {
            foreach ($request->delete_images as $imageToDelete) {
                $fullPath = public_path($imageToDelete);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
                $existingImages = array_values(array_diff($existingImages, [$imageToDelete]));
            }
        }

        // Upload new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/products'), $filename);
                $existingImages[] = 'images/products/' . $filename;
            }
        }

        // Limit 10 images
        $existingImages = array_slice(array_values($existingImages), 0, 10);

        // Simpan images (kalau mau boleh kosong pun disimpan)
        $data['images'] = $existingImages;

        $product->update($data);

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk berhasil diperbarui!');

    } catch (\Exception $e) {
        return redirect()->back()
            ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
            ->withInput();
    }
}

    
   public function destroy($id)
{
    $seller = auth()->user()->seller;

    if (!$seller) {
        return redirect()->route('login')->with('error', 'Data seller tidak ditemukan.');
    }

    $product = Product::where('seller_id', $seller->id)->findOrFail($id);

    try {
        // Pastikan images array
        $images = is_array($product->images)
            ? $product->images
            : (json_decode($product->images, true) ?: []);

        // Delete all images
        foreach ($images as $image) {
            $fullPath = public_path($image);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        $product->delete();

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk berhasil dihapus!');

    } catch (\Exception $e) {
        return redirect()->back()
            ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
}
}

