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
        $user = Auth::guard('web')->user();
        
        // Get seller record from sellers table based on pic_email
        $seller = \App\Models\Seller::where('pic_email', $user->email)->first();
        
        if (!$seller) {
            return redirect()->route('seller.login')->with('error', 'Data seller tidak ditemukan.');
        }
        
        // Get seller's products count
        $productsCount = Product::where('seller_id', $seller->id)->count();
        
        // Get total reviews for seller's products
        $totalReviews = DB::table('reviews')
            ->join('products', 'reviews.product_id', '=', 'products.id')
            ->where('products.seller_id', $seller->id)
            ->count();
        
        // Get monthly site visitors data (simulated data)
        $monthlyVisitors = $this->getMonthlyVisitors();
        
        // Get reviewer counts by province
        $reviewersByProvince = $this->getReviewersByProvince($seller->id);
        
        // Get products with details (stock, category, comments, rating)
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
    
    private function getReviewersByProvince($sellerId)
    {
        // Get reviewer counts by province for seller's products
        $reviewers = DB::table('reviews')
            ->join('products', 'reviews.product_id', '=', 'products.id')
            ->where('products.seller_id', $sellerId)
            ->select('reviews.province', DB::raw('count(*) as count'))
            ->groupBy('reviews.province')
            ->orderBy('count', 'desc')
            ->limit(3)
            ->get();
        
        if ($reviewers->isEmpty()) {
            return collect([
                ['province' => 'No reviews yet', 'count' => 0]
            ]);
        }
        
        return $reviewers;
    }
    
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
