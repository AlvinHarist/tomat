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

        $banners = [
            'images/banners/banner1.png',
            'images/banners/banner2.jpg',
            'images/banners/banner3.png',
            'images/banners/banner4.jpg',
        ];

        return view('product.index', [
            'banners'         => $banners,
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
        $rating     = $request->input('rating'); // ⭐ ambil rating min dari request

        // Mulai query
        $query = Product::with(['category', 'seller'])
            // kalau mau pakai avg rating di kartu, boleh selalu di-withAvg
            // ->withAvg('reviews', 'rating');
        ;

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

        // 🔍 FILTER HARGA
        if (!empty($minPrice)) {
            $query->where('price', '>=', (int) $minPrice);
        }

        if (!empty($maxPrice)) {
            $query->where('price', '<=', (int) $maxPrice);
        }

        // 🔍 FILTER RATING (min rata-rata bintang)
        if (!empty($rating)) {
            $rating = (int) $rating;
            // Filter menggunakan avg_rating yang sudah disimpan di database
            $query->where('avg_rating', '>=', $rating);
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

        return view('product.search', [
            'products'        => $products,
            'categories'      => $categories,
            'currentSearch'   => $search,
            'currentCategory' => $categoryId,
            'currentProvince' => $province,
            'currentMinPrice' => $minPrice,
            'currentMaxPrice' => $maxPrice,
            // bisa juga kirim currentRating kalau mau, tapi kamu pakai request('rating') di Blade sudah cukup
            // 'currentRating'   => $rating,
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

            // ✅ multiple images (JSON)
            'images'      => 'nullable|array',
            'images.*'    => 'image|max:2048',
        ]);

        $imagePaths = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                // simpan ke storage/app/public/products
                $path = $file->store('products', 'public');

                // Simpan path seperti ini:
                // - di DB: "storage/products/xxxx.jpg"
                // - di view: src="{{ asset($img) }}"
                $imagePaths[] = 'storage/' . $path;
            }
        }

        $data['images'] = $imagePaths;

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Product created.');
    }


    public function show(Request $request, Product $product)
    {
        // eager load category + parent chain biar nggak N+1
        $product->load([
            'category.parent.parent',
            'reviews',
            'seller',
        ]);

        // Kumpulkan jejak kategori dari root → current category
        $categoryBreadcrumbs = $product->category
            ? $product->category->getBreadcrumbs()
            : collect();

        // Susun array untuk komponen breadcrumb
        $breadcrumbs = [];

        // 1) Home
        $breadcrumbs[] = [
            'label' => 'Home',
            'url'   => route('home'),
        ];

        // 2) Kategori dari root sampai kategori produk
        foreach ($categoryBreadcrumbs as $cat) {
            $breadcrumbs[] = [
                'label' => $cat->name,
                'url'   => route('search', [
                    'category' => $cat->id,
                ]),
            ];
        }

        // 3) Produk sekarang
        $breadcrumbs[] = [
            'label' => $product->name,
            'url'   => null,
        ];

        // Rekomendasi: produk lain dari seller / toko yang sama (pakai pagination)
        $perPage = 6;

        $recommendationQuery = Product::where('id', '!=', $product->id)
            ->where('seller_id', $product->seller_id)
            ->latest(); // daripada inRandomOrder, biar pagination rapi

        // paginate pakai nama page khusus: rec_page
        $recommendations = $recommendationQuery->paginate($perPage, ['*'], 'rec_page');

        // === HANDLE AJAX LOAD MORE RECOMMENDATIONS ===
        if ($request->ajax() && $request->get('load') === 'recommendations') {
            $html = view('components.product-cards', [
                'products' => $recommendations,
            ])->render();

            return response()->json([
                'html'      => $html,
                'next_page' => $recommendations->currentPage() + 1,
                'has_more'  => $recommendations->hasMorePages(),
            ]);
        }

        return view('product.detail', [
            'product'         => $product,
            'recommendations' => $recommendations,
            'breadcrumbs'     => $breadcrumbs,
            'seller'          => $product->seller,
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

            'images'      => 'nullable|array',
            'images.*'    => 'image|max:2048',
        ]);

        $imagePaths = $product->images ?? [];

        // Kalau ada upload baru, kita replace seluruh gambar lama
        if ($request->hasFile('images')) {

            // 🔥 Hapus file lama yg memang file upload (bukan seeder "images/xxx.png")
            if (!empty($imagePaths)) {
                foreach ((array) $imagePaths as $oldImg) {
                    if (is_string($oldImg) && str_starts_with($oldImg, 'storage/')) {
                        $oldPath = str_replace('storage/', '', $oldImg); // "products/xxx.jpg"
                        Storage::disk('public')->delete($oldPath);
                    }
                }
            }

            $imagePaths = [];
            foreach ($request->file('images') as $file) {
                $path = $file->store('products', 'public');
                $imagePaths[] = 'storage/' . $path;
            }
        }

        $data['images'] = $imagePaths;

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        // Hapus semua file yang ada di kolom images (kalau dia file upload)
        if (!empty($product->images)) {
            foreach ((array) $product->images as $img) {
                if (is_string($img) && str_starts_with($img, 'storage/')) {
                    $oldPath = str_replace('storage/', '', $img);
                    Storage::disk('public')->delete($oldPath);
                }
            }
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted.');
    }

}
