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
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            $imagePath = $request->file('image')->store('products', 'public');
            
            Product::create([
                'seller_id' => $seller->id,
                'category_id' => $request->category_id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'images' => $imagePath,
            ]);
            
            return redirect()->route('seller.products.index')
                ->with('success', 'Produk berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            if (isset($imagePath)) {
                Storage::disk('public')->delete($imagePath);
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
        
        return view('seller.products.edit', compact('product', 'categories'));
    }
    
    public function update(Request $request, $id)
    {
        $user = Auth::guard('web')->user();
        $seller = \App\Models\Seller::where('pic_email', $user->email)->first();
        $product = Product::where('seller_id', $seller->id)->findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'category_id' => $request->category_id,
            ];
            
            if ($request->hasFile('image')) {
                // Delete old image
                if ($product->images) {
                    Storage::disk('public')->delete($product->images);
                }
                
                $data['images'] = $request->file('image')->store('products', 'public');
            }
            
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
        $user = Auth::guard('web')->user();
        $seller = \App\Models\Seller::where('pic_email', $user->email)->first();
        $product = Product::where('seller_id', $seller->id)->findOrFail($id);
        
        try {
            // Delete image
            if ($product->images) {
                Storage::disk('public')->delete($product->images);
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
