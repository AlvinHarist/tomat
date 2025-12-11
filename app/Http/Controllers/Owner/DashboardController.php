<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. SRS: Jumlah User Penjual Aktif dan Tidak Aktif [cite: 67]
        $activeSellers = Seller::where('status', 'ACTIVE')->count();
        $nonActiveSellers = Seller::whereIn('status', ['PENDING', 'REJECTED'])->count();

        // 2. Data Kartu Atas (Total Produk, Kategori, Review)
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        // SRS: Jumlah pengunjung yang memberikan komentar dan rating [cite: 67]
        $totalReviews = Review::count();

        // 3. SRS: Sebaran jumlah produk berdasarkan kategori [cite: 67]
        // Ambil semua kategori yang diurutkan berdasarkan jumlah produk (view akan menampilkan 5 pertama)
        $productByCategory = Category::withCount('products')
            ->orderBy('products_count', 'desc')
            ->get();

        // 4. SRS: Sebaran jumlah toko berdasarkan Lokasi provinsi [cite: 67]
        // Ambil sebaran toko per provinsi (view akan menampilkan 5 pertama)
        $sellerByLocation = Seller::select('pic_province', DB::raw('count(*) as total'))
            ->groupBy('pic_province')
            ->orderBy('total', 'desc')
            ->get();

        // 5. Grafik Pengunjung (Berdasarkan aktivitas Review per Bulan)
        // Ini untuk mengisi chart "Site Visitors" sesuai desain
        $visitorStats = Review::select(
            DB::raw('MONTH(created_at) as month'), 
            DB::raw('count(*) as total')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month')
        ->all();

        // Siapkan array data untuk chart (Jan-Des)
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $visitorStats[$i] ?? 0;
        }

        return view('owner.dashboard', compact(
            'activeSellers', 
            'nonActiveSellers', 
            'totalProducts', 
            'totalCategories', 
            'totalReviews',
            'productByCategory',
            'sellerByLocation',
            'chartData'
        ));
    }
}