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
        $user = auth()->user();
        $seller = $user->seller;

        if (!$seller) {
            return redirect()->route('login')->with('error', 'Data seller tidak ditemukan.');
        }

        $productsCount = Product::where('seller_id', $seller->id)->count();

        $totalReviews = DB::table('reviews')
            ->join('products', 'reviews.product_id', '=', 'products.id')
            ->where('products.seller_id', $seller->id)
            ->count();

        $monthlyVisitors = $this->getMonthlyVisitors();
        $reviewersByProvince = $this->getReviewersByProvince($seller->id, 3);
        $products = $this->getProductsWithDetails($seller->id);

        return view('seller.dashboard', compact(
            'seller',
            'productsCount',
            'totalReviews',
            'monthlyVisitors',
            'reviewersByProvince',
            'products'
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
     * Mengambil data pengunjung bulanan simulasi.
     */
    private function getMonthlyVisitors()
    {
        // Simulated monthly visitors data (this should be replaced with actual analytics)
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data = [];
        
        foreach ($months as $month) {
            $data[] = [
                'month' => $month,
                'count' => rand(40, 80) // Simulated visitor count
            ];
        }
        
        return $data;
    }
    
    /**
     * Mengambil data reviewer per provinsi dengan batasan limit.
     */
    private function getReviewersByProvince($sellerId, $limit = null)
    {
        // Get reviewer counts by province for seller's products
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
        
        if ($reviewers->isEmpty() && !$limit) {
             return collect([
                (object)['province' => 'No reviews yet', 'count' => 0]
            ]);
        }
        
        return $reviewers;
    }
    
    /**
     * Mengambil produk dengan detail untuk tampilan dashboard.
     */
    private function getProductsWithDetails($sellerId)
    {
        // Get products with stock, category, comments count, and rating
        $products = Product::where('seller_id', $sellerId)
            ->with(['category', 'reviews'])
            ->get()
            ->map(function($product) {
                $commentsCount = $product->reviews->count();
                $avgRating = $product->reviews->avg('rating') ?? 0;
                
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