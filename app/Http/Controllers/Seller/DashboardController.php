<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\CommentRating;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil ID Seller yang sedang login
        $sellerId = Auth::guard('seller')->id();

        // 1. Total Produk Milik Seller
        $totalProducts = Product::where('seller_id', $sellerId)->count();

        // 2. Total Review Produk Milik Seller
        // Kita hitung total review untuk semua produk seller ini
        $totalReviews = CommentRating::whereHas('product', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })->count();

        // 3. Rata-rata Rating Produk Milik Seller
        $averageRating = CommentRating::whereHas('product', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })->avg('rating');
        $averageRating = round($averageRating ?? 0, 1);

        // 4. Grafik Aktivitas Review Bulanan (Mirip Owner, tapi hanya produk sendiri)
        $reviewStats = CommentRating::select(
            DB::raw('MONTH(comment_ratings.created_at) as month'), 
            DB::raw('count(*) as total')
        )
        ->join('products', 'comment_ratings.product_id', '=', 'products.id')
        ->where('products.seller_id', $sellerId) // Filter hanya produk milik seller ini
        ->whereYear('comment_ratings.created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month')
        ->all();

        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $reviewStats[$i] ?? 0;
        }

        // 5. Produk Terlaris (Top 5 berdasarkan stok terbanyak, bisa diganti dengan 'paling banyak di-review')
        $topProducts = Product::where('seller_id', $sellerId)
            ->withCount('commentRatings')
            ->orderBy('comment_ratings_count', 'desc')
            ->take(5)
            ->get();


        return view('seller.dashboard', compact(
            'totalProducts',
            'totalReviews',
            'averageRating',
            'chartData',
            'topProducts'
        ));
    }
}