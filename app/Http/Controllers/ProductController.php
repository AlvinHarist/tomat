<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search     = $request->input('q');
        $categoryId = $request->input('category');
        $province   = $request->input('province');

        $query = Product::with(['category', 'seller']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (!empty($categoryId)) {
            // Ambil kategori yang dipilih
            $category = Category::with('children')->find($categoryId);

            if ($category) {
                // Ambil semua ID descendant (tak terbatas)
                $categoryIds = $category->descendantsIdsRecursive();
                $query->whereIn('category_id', $categoryIds);
            } else {
                // Kalau ID kategori tidak ditemukan, paksa kosong
                $query->whereRaw('1 = 0');
            }
        }

        if (!empty($province)) {
            $query->whereHas('seller', function ($q) use ($province) {
                $q->where('pic_province', $province);
            });
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        if ($request->ajax()) {
            $html = view('components.product-cards', [
                'products' => $products,
            ])->render();

            return response()->json([
                'html'      => $html,
                'next_page' => $products->currentPage() + 1,
                'has_more'  => $products->hasMorePages(),
            ]);
        }

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

    public function search(Request $request)
    {
        $search     = $request->input('q');
        $categoryId = $request->input('category');
        $province   = $request->input('province');
        $minPrice   = $request->input('min_price');
        $maxPrice   = $request->input('max_price');

        $query = Product::with(['category', 'seller']);

        // 🔍 Filter pencarian teks
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 🔍 Filter kategori (berjenjang)
        if (!empty($categoryId)) {
            $category = Category::with('children')->find($categoryId);

            if ($category) {
                $categoryIds = $category->descendantsIdsRecursive();
                $query->whereIn('category_id', $categoryIds);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        // 🔍 Filter provinsi (dari seller)
        if (!empty($province)) {
            $query->whereHas('seller', function ($q) use ($province) {
                $q->where('pic_province', $province);
            });
        }

        // 🔍 FILTER HARGA BARU
        if (!empty($minPrice)) {
            $query->where('price', '>=', (int) $minPrice);
        }

        if (!empty($maxPrice)) {
            $query->where('price', '<=', (int) $maxPrice);
        }

        // 🔍 Ambil data
        $products = $query->latest()->paginate(12)->withQueryString();

        // 🔁 Jika AJAX (infinite scroll)
        if ($request->ajax()) {
            $html = view('components.product-cards', [
                'products' => $products,
            ])->render();

            return response()->json([
                'html'      => $html,
                'next_page' => $products->currentPage() + 1,
                'has_more'  => $products->hasMorePages(),
            ]);
        }

        // Untuk sidebar filter
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get();

        return view('search.index', [
            'products'        => $products,
            'categories'      => $categories,
            'currentSearch'   => $search,
            'currentCategory' => $categoryId,
            'currentProvince' => $province,
            'currentMinPrice' => $minPrice,
            'currentMaxPrice' => $maxPrice,
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
        // eager load category + parent chain biar nggak N+1
        $product->load([
            'category.parent.parent', // sesuaikan kedalaman
            'reviews',                // kalau dipakai
        ]);

        // Kumpulkan jejak kategori dari root → current category
        $categoryBreadcrumbs = $product->category
            ? $product->category->getBreadcrumbs()   // method dari model Category kamu
            : collect();

        // Susun array untuk komponen breadcrumb
        $breadcrumbs = [];

        // 1) Home
        $breadcrumbs[] = [
            'label' => 'Home',
            'url'   => route('home'),
        ];

        // 2) Setiap kategori dari root sampai kategori produk
        //    Link-nya aku arahkan ke home dengan filter ?category=...
        foreach ($categoryBreadcrumbs as $cat) {
            $breadcrumbs[] = [
                'label' => $cat->name,
                'url'   => route('search', [
                    'category' => $cat->id,
                ]),
            ];
        }

        // 3) Terakhir: nama produk (tanpa URL)
        $breadcrumbs[] = [
            'label' => $product->name,
            'url'   => null,
        ];

        // contoh rekomendasi saja, sesuaikan punyamu
        $recommendations = Product::where('id', '!=', $product->id)
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return view('product.index', [
            'product'         => $product,
            'recommendations' => $recommendations,
            'breadcrumbs'     => $breadcrumbs,
        ]);
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