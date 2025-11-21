<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Store;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search     = $request->input('q');
        $categoryId = $request->input('category');
        $province    = $request->input('province');

        $query = Product::with(['category', 'store', 'reviews']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        if (!empty($province)) {
            $query->whereHas('store', function ($q) use ($province) {
                $q->where('province', $province);
            });
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get();

        return view('home.index', [
            'products'        => $products,
            'categories'      => $categories,
            'currentSearch'   => $search,
            'currentCategory' => $categoryId,
        ]);
    }

    public function create()
    {
        $stores = Store::all();
        $categories = Category::all();

        return view('products.create', compact('stores', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'store_id'    => 'required|exists:stores,id',
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'main_image'  => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('products', 'public');
            $data['main_image'] = 'storage/' . $path;
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Product created.');
    }

    public function show(Product $product)
    {
        $product->load(['store', 'category', 'reviews']);

        $recommendations = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(6)
            ->get();

        return view('product.index', compact('product', 'recommendations'));
    }

    public function edit(Product $product)
    {
        $stores = Store::all();
        $categories = Category::all();

        return view('products.edit', compact('product', 'stores', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'store_id'    => 'required|exists:stores,id',
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'main_image'  => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('main_image')) {
            // hapus file lama kalau dia file upload (bukan seeder "images/xxx.png")
            if ($product->main_image && str_starts_with($product->main_image, 'storage/')) {
                $oldPath = str_replace('storage/', '', $product->main_image); // "products/xxx.jpg"
                Storage::disk('public')->delete($oldPath);
            }

            // simpan file baru
            $path = $request->file('main_image')->store('products', 'public');
            $data['main_image'] = 'storage/' . $path;
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        if ($product->main_image && str_starts_with($product->main_image, 'storage/')) {
            $oldPath = str_replace('storage/', '', $product->main_image);
            Storage::disk('public')->delete($oldPath);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted.');
    }
}
