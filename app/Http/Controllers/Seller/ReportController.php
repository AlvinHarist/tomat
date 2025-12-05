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
    /**
     * Tampilkan halaman indeks laporan.
     */
    public function index()
    {
        return view('seller.reports.index');
    }
    
    // --- Metode untuk Menampilkan Halaman Filter Tanggal ---

    /**
     * Tampilkan halaman filter untuk Laporan Stock
     */
    public function productsByStockFilter()
    {
        return view('seller.reports.filter-page', [
            'reportTitle' => 'Laporan Stock Produk',
            'reportRoute' => route('seller.reports.stock'),
        ]);
    }

    /**
     * Tampilkan halaman filter untuk Laporan Rating
     */
    public function productsByRatingFilter()
    {
        return view('seller.reports.filter-page', [
            'reportTitle' => 'Laporan Rating Produk',
            'reportRoute' => route('seller.reports.rating'),
        ]);
    }

    /**
     * Tampilkan halaman filter untuk Laporan Produk Segera Dipesan
     */
    public function productsNeedRestockFilter()
    {
        return view('seller.reports.filter-page', [
            'reportTitle' => 'Laporan Produk Segera Dipesan',
            'reportRoute' => route('seller.reports.restock'),
        ]);
    }
    
    // --- Metode untuk Mencetak Laporan (dengan Filter Tanggal) ---

    /**
     * Generate Laporan Produk Berdasarkan Stock (dengan Filter Tanggal)
     */
    public function productsByStock(Request $request)
    {
        $user = Auth::guard('web')->user();
        $seller = Seller::where('pic_email', $user->email)->first();

        $query = Product::where('seller_id', $seller->id)
            ->with('category')
            ->orderBy('stock', 'desc');

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            // Filter berdasarkan tanggal produk dibuat (created_at)
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $products = $query->get();
        $filterDateString = ($startDate && $endDate) ? "($startDate s/d $endDate)" : '(Keseluruhan Tanggal)';

        // Cek apakah ada produk pada rentang tanggal yang difilter
        if ($products->isEmpty() && $startDate && $endDate) {
            return redirect()->route('seller.reports.products-by-stock.filter')
                ->with('error', 'Laporan tidak bisa dicetak. Tidak ada produk yang ditambahkan pada rentang tanggal ' . $startDate . ' sampai ' . $endDate . '.');
        }

        $pdf = Pdf::loadView('seller.reports.products-by-stock', [
            'products' => $products,
            'seller' => $seller,
            'date' => now()->format('d-m-Y H:i:s'),
            'filterDate' => $filterDateString // Variabel baru untuk tampilan PDF
        ]);
        
        $filename = 'Laporan-Produk-Stock-' . ($startDate && $endDate ? "$startDate-$endDate" : now()->format('d-m-Y')) . '.pdf';

        return $pdf->download($filename);
    }
    
    /**
     * Generate Laporan Produk Berdasarkan Rating (dengan Filter Tanggal)
     */
    public function productsByRating(Request $request)
    {
        $user = Auth::guard('web')->user();
        $seller = Seller::where('pic_email', $user->email)->first();
        
        $query = Product::where('seller_id', $seller->id)
            ->with(['category', 'reviewAndRatings']);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            // Filter berdasarkan tanggal produk dibuat (created_at)
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        // Ambil produk dan hitung rata-rata rating (di memory)
        $products = $query->get()
            ->map(function($product) {
                $product->avg_rating = $product->reviewAndRatings->avg('rating') ?? 0;
                return $product;
            })
            ->sortByDesc('avg_rating');

        $filterDateString = ($startDate && $endDate) ? "($startDate s/d $endDate)" : '(Keseluruhan Tanggal)';

        // Cek apakah ada produk pada rentang tanggal yang difilter
        if ($products->isEmpty() && $startDate && $endDate) {
            return redirect()->route('seller.reports.products-by-rating.filter')
                ->with('error', 'Laporan tidak bisa dicetak. Tidak ada produk yang ditambahkan pada rentang tanggal ' . $startDate . ' sampai ' . $endDate . '.');
        }

        $pdf = Pdf::loadView('seller.reports.products-by-rating', [
            'products' => $products,
            'seller' => $seller,
            'date' => now()->format('d-m-Y H:i:s'),
            'filterDate' => $filterDateString
        ]);
        
        $filename = 'Laporan-Produk-Rating-' . ($startDate && $endDate ? "$startDate-$endDate" : now()->format('d-m-Y')) . '.pdf';

        return $pdf->download($filename);
    }
    
    /**
     * Generate Laporan Produk Segera Dipesan (dengan Filter Tanggal)
     */
    public function productsNeedRestock(Request $request)
    {
        $user = Auth::guard('web')->user();
        $seller = Seller::where('pic_email', $user->email)->first();
        
        // Query utama: Produk dengan stok kurang dari 10
        $query = Product::where('seller_id', $seller->id)
            ->where('stock', '<', 10)
            ->with('category')
            ->orderBy('stock', 'asc');
        
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        if ($startDate && $endDate) {
            // Filter query utama berdasarkan tanggal produk dibuat (created_at)
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $products = $query->get();
        $filterDateString = ($startDate && $endDate) ? "($startDate s/d $endDate)" : '(Keseluruhan Tanggal)';
        
        // Cek apakah ada produk pada rentang tanggal yang difilter
        if ($products->isEmpty() && $startDate && $endDate) {
            // Cek apakah memang ada produk yang ditambahkan di rentang tgl tersebut
            $totalProductsInDateRange = Product::where('seller_id', $seller->id)
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->count();
            
            if ($totalProductsInDateRange === 0) {
                // Tidak ada produk yang di-upload sama sekali di rentang tanggal ini
                return redirect()->route('seller.reports.products-need-restock.filter')
                    ->with('error', 'Laporan tidak bisa dicetak. Tidak ada produk yang ditambahkan pada rentang tanggal ' . $startDate . ' sampai ' . $endDate . '.');
            } else {
                // Ada produk, tapi stoknya aman (tidak ada yang perlu restock)
                return redirect()->route('seller.reports.products-need-restock.filter')
                    ->with('warning', 'Semua produk yang ditambahkan pada rentang tanggal ' . $startDate . ' sampai ' . $endDate . ' memiliki stok yang aman.');
            }
        }

        // Group by category
        $productsByCategory = $products->groupBy('category.name');

        $pdf = Pdf::loadView('seller.reports.products-need-restock', [
            'productsByCategory' => $productsByCategory,
            'seller' => $seller,
            'date' => now()->format('d-m-Y H:i:s'),
            'filterDate' => $filterDateString
        ]);
        
        $filename = 'Laporan-Produk-Segera-Dipesan-' . ($startDate && $endDate ? "$startDate-$endDate" : now()->format('d-m-Y')) . '.pdf';

        return $pdf->download($filename);
    }
}