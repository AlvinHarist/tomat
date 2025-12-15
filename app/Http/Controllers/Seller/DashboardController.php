<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Menggunakan auth()->user() dan relasi seller (TANPA pic_email)
        $user = auth()->user();
        $seller = $user->seller;
        
        if (!$seller) {
            return redirect()->route('login')->with('error', 'Data seller tidak ditemukan.');
        }
        
        // Get seller's products count
        $productsCount = Product::where('seller_id', $seller->id)->count();
        
        // Get total reviews for seller's products
        $totalReviews = DB::table('reviews')
            ->join('products', 'reviews.product_id', '=', 'products.id')
            ->where('products.seller_id', $seller->id)
            ->count();
        
        // Get reviewer counts by province (DIBATASI 3 BARIS UNTUK DASHBOARD)
        $reviewersByProvince = $this->getReviewersByProvince($seller->id, 3);
        
        // LOGIC UNTUK CHART STOK DAN RATING
        $chartData = $this->getProductChartData($seller->id); 
        $productStockData = $chartData['productStockData'];
        $ratingDistribution = $chartData['ratingDistribution'];
        
        // Get products with details (stock, category, comments, rating)
        $products = $this->getProductsWithDetails($seller->id);
        
        return view('seller.dashboard', compact(
            'seller',
            'productsCount',
            'totalReviews',
            'reviewersByProvince',
            'products',
            'productStockData',     
            'ratingDistribution'    
        ));
    }
    
    public function reviewersByProvinceIndex()
    {
        $user = auth()->user();
        $seller = $user->seller;

        if (!$seller) {
            return redirect()->route('login')->with('error', 'Data seller tidak ditemukan.');
        }

        $reviewers = $this->getReviewersByProvince($seller->id);

        return view('seller.province-index', compact('reviewers')); 
    }

    /**
     * Mengambil data sebaran stok dan rating produk.
     */
    private function getProductChartData($sellerId)
    {
        $products = Product::where('seller_id', $sellerId)
            ->select('id', 'name', 'stock') 
            ->withAvg('reviews', 'rating') 
            ->orderByDesc('stock')
            ->get();

        // 1. Data Sebaran Stok (Top 10)
        $productStockData = $products->take(10)->map(function ($product) {
            return [
                'name' => $product->name,
                'stock' => $product->stock
            ];
        });

        // 2. Data Sebaran Rating (Hitungan produk per nilai rating 1-5)
        $ratingDistribution = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        
        foreach ($products as $product) {
            $avgRating = $product->reviews_avg_rating ?? 0;
            $rating = floor($avgRating);
            
            if ($rating >= 1 && $rating <= 5) {
                $ratingDistribution[$rating]++;
            }
        }
        
        return [
            'productStockData' => $productStockData,
            'ratingDistribution' => $ratingDistribution
        ];
    }
    
    /**
     * Mengambil data reviewer per provinsi.
     */
    private function getReviewersByProvince($sellerId, $limit = null)
    {
        $query = DB::table('reviews')
            ->join('products', 'reviews.product_id', '=', 'products.id')
            ->where('products.seller_id', $sellerId) 
            ->select('reviews.province', DB::raw('count(*) as count'))
            ->groupBy('reviews.province')
            ->orderBy('count', 'desc');

        if ($limit) {
            $query->limit($limit);
        }
        
        $reviewers = $query->get();
        
        if ($reviewers->isEmpty() && $limit) {
            return collect([
                (object)['province' => 'No reviews yet', 'count' => 0]
            ]);
        }
        
        return $reviewers;
    }
    
    private function getProductsWithDetails($sellerId)
    {
        $products = Product::where('seller_id', $sellerId)
            ->with(['category'])
            ->withCount('reviews') 
            ->withAvg('reviews', 'rating')
            ->get()
            ->map(function($product) {
                $commentsCount = $product->reviews_count;
                $avgRating = $product->reviews_avg_rating ?? 0;
                
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->images[0] ?? null,
                    'stock' => $product->stock,
                    'category' => $product->category->name ?? 'Uncategorized',
                    'comments_count' => $commentsCount,
                    'rating' => round($avgRating, 1)
                ];
            })
            ->sortByDesc('stock');
        
        return $products;
    }
}