<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Category;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('seller.reports.index');
    }
    
    /**
     * Generate Laporan Produk Berdasarkan Stock
     */
    public function productsByStock()
    {
        $user = Auth::guard('web')->user();
        $seller = Seller::where('pic_email', $user->email)->first();
        
        $products = Product::where('seller_id', $seller->id)
            ->with('category')
            ->orderBy('stock', 'desc')
            ->get();
        
        $pdf = Pdf::loadView('seller.reports.products-by-stock', [
            'products' => $products,
            'seller' => $seller,
            'date' => now()->format('d-m-Y')
        ]);
        
        return $pdf->download('Laporan-Produk-Stock-' . now()->format('d-m-Y') . '.pdf');
    }
    
    /**
     * Generate Laporan Produk Berdasarkan Rating
     */
    public function productsByRating()
    {
        $user = Auth::guard('web')->user();
        $seller = Seller::where('pic_email', $user->email)->first();
        
        // Get products with average rating
        $products = Product::where('seller_id', $seller->id)
            ->with(['category', 'reviewAndRatings'])
            ->get()
            ->map(function($product) {
                $product->avg_rating = $product->reviewAndRatings->avg('rating') ?? 0;
                return $product;
            })
            ->sortByDesc('avg_rating');
        
        $pdf = Pdf::loadView('seller.reports.products-by-rating', [
            'products' => $products,
            'seller' => $seller,
            'date' => now()->format('d-m-Y')
        ]);
        
        return $pdf->download('Laporan-Produk-Rating-' . now()->format('d-m-Y') . '.pdf');
    }
    
    /**
     * Generate Laporan Produk Segera Dipesan
     */
    public function productsNeedRestock()
    {
        $user = Auth::guard('web')->user();
        $seller = Seller::where('pic_email', $user->email)->first();
        
        // Get products with low stock (less than 10)
        $products = Product::where('seller_id', $seller->id)
            ->where('stock', '<', 10)
            ->with('category')
            ->orderBy('stock', 'asc')
            ->get();
        
        // Group by category
        $productsByCategory = $products->groupBy('category.name');
        
        $pdf = Pdf::loadView('seller.reports.products-need-restock', [
            'productsByCategory' => $productsByCategory,
            'seller' => $seller,
            'date' => now()->format('d-m-Y')
        ]);
        
        return $pdf->download('Laporan-Produk-Segera-Dipesan-' . now()->format('d-m-Y') . '.pdf');
    }
}
