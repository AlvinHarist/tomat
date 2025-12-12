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
        $user = Auth::guard('web')->user();
        $seller = \App\Models\Seller::where('pic_email', $user->email)->first();
        
        $products = Product::where('seller_id', $seller->id)
            ->with('category')
            // Tambahkan casting untuk kolom 'images' jika menggunakan JSON/Array
            ->select('*') 
            // Coba ambil path pertama jika model Anda menggunakan kolom 'images' array
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
        $user = Auth::guard('web')->user();
        $seller = \App\Models\Seller::where('pic_email', $user->email)->first();
        
        // --- PERBAIKAN VALIDASI UNTUK MULTIPLE IMAGES ---
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:1', // Stok minimal 1
            'category_id' => 'required|exists:categories,id',
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $imagePaths = [];
        try {
            // Upload multiple images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('images/products'), $filename);
                    $imagePaths[] = 'images/products/' . $filename;
                }
            }
            
            Product::create([
                'seller_id' => $seller->id,
                'category_id' => $request->category_id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'images' => $imagePaths,
            ]);
            
            return redirect()->route('seller.products.index')
                ->with('success', 'Produk berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            // Delete uploaded images if error occurs
            if (isset($imagePaths) && count($imagePaths) > 0) {
                foreach ($imagePaths as $path) {
                    $fullPath = public_path($path);
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                    }
                }
            }
            
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }
    
    public function edit($id)
    {
        $user = Auth::guard('web')->user();
        $seller = \App\Models\Seller::where('pic_email', $user->email)->first();
        $product = Product::where('seller_id', $seller->id)->findOrFail($id);
        $categories = Category::all();
        
        // ASUMSI: Jika 'images' di model adalah string path tunggal, ubah menjadi array
        if (is_string($product->images)) {
             $product->images = json_decode($product->images, true) ?? [];
        }
        
        return view('seller.products.edit', compact('product', 'categories'));
    }
    
    public function update(Request $request, $id)
    {
        $user = Auth::guard('web')->user();
        $seller = \App\Models\Seller::where('pic_email', $user->email)->first();
        $product = Product::where('seller_id', $seller->id)->findOrFail($id);
        
        // Ambil path gambar yang sudah ada (pastikan selalu array)
        $existingImages = is_string($product->images) ? json_decode($product->images, true) ?? [] : $product->images ?? [];
        
        // Hitung gambar yang akan dihapus
        $imagesToDelete = $request->input('delete_images', []);

        // Tentukan gambar yang tersisa setelah dihapus
        $remainingImages = array_values(array_diff($existingImages, $imagesToDelete));
        $newImages = $request->file('images') ?? [];
        $totalImagesAfterUpdate = count($remainingImages) + count($newImages);
        
        // --- PERBAIKAN VALIDASI UNTUK UPDATE MULTIPLE IMAGES ---
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'images' => 'nullable|array|max:10',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Validasi kustom untuk memastikan minimal 1 gambar
        // Validasi kustom untuk memastikan minimal 1 gambar
        if ($totalImagesAfterUpdate < 1) {
             return redirect()->back()
                 ->withErrors(['images' => 'Produk harus memiliki minimal 1 foto.'])
                 ->withInput();
        }

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
        ];
        
        $uploadedPaths = [];
        try {
            // 1. Hapus gambar lama yang ditandai untuk dihapus
            foreach ($imagesToDelete as $path) {
                $fullPath = public_path($path);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }

            // 2. Upload gambar baru
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('images/products'), $filename);
                    $uploadedPaths[] = 'images/products/' . $filename;
                }
            }
            
            // 3. Gabungkan gambar yang tersisa dan yang baru diupload
            $finalImages = array_merge($remainingImages, $uploadedPaths);
            
            // Limit to 10 images
            $finalImages = array_slice(array_values($finalImages), 0, 10);
            
            $data['images'] = $finalImages;

            $product->update($data);
            
            return redirect()->route('seller.products.index')
                ->with('success', 'Produk berhasil diperbarui!');
                
        } catch (\Exception $e) {
            // Hapus gambar baru jika terjadi kegagalan update
            foreach ($uploadedPaths as $path) {
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
    
    public function destroy($id)
    {
        $user = Auth::guard('web')->user();
        $seller = \App\Models\Seller::where('pic_email', $user->email)->first();
        $product = Product::where('seller_id', $seller->id)->findOrFail($id);
        
        // ASUMSI: Jika 'images' di model adalah string path tunggal, ubah menjadi array
        $imagesToDelete = is_string($product->images) ? json_decode($product->images, true) ?? [] : $product->images ?? [];
        
        try {
            // Hapus semua gambar
            foreach ($imagesToDelete as $imagePath) {
                $fullPath = public_path($imagePath);
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
