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
    
    // --- Metode untuk Menampilkan Halaman Filter Tanggal (Tidak perlu perubahan) ---

    public function productsByStockFilter()
    {
        return view('seller.reports.filter-page', [
            'reportTitle' => 'Laporan Stock Produk',
            'reportRoute' => route('seller.reports.stock'),
        ]);
    }

    public function productsByRatingFilter()
    {
        return view('seller.reports.filter-page', [
            'reportTitle' => 'Laporan Rating Produk',
            'reportRoute' => route('seller.reports.rating'),
        ]);
    }

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
        // PERBAIKAN 1/3: Mengganti pic_email dengan relasi user->seller
        $user = auth()->user();
        $seller = $user->seller;
        
        if (!$seller) {
             return redirect()->route('login')->with('error', 'Data seller tidak ditemukan.');
        }

        // Menambahkan withAvg untuk kolom Rating di laporan
        $query = Product::where('seller_id', $seller->id)
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->orderBy('stock', 'desc');

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $products = $query->get();
        $filterDateString = ($startDate && $endDate) ? "($startDate s/d $endDate)" : '(Keseluruhan Tanggal)';

        if ($products->isEmpty() && $startDate && $endDate) {
            return redirect()->route('seller.reports.products-by-stock.filter')
                ->with('error', 'Laporan tidak bisa dicetak. Tidak ada produk yang ditambahkan pada rentang tanggal ' . $startDate . ' sampai ' . $endDate . '.');
        }

        $pdf = Pdf::loadView('seller.reports.products-by-stock', [
            'products' => $products,
            'seller' => $seller,
            'date' => now()->format('d-m-Y H:i:s'),
            'filterDate' => $filterDateString
        ]);
        
        $filename = 'Laporan-Produk-Stock-' . ($startDate && $endDate ? "$startDate-$endDate" : now()->format('d-m-Y')) . '.pdf';

        return $pdf->download($filename);
    }
    
    /**
     * Generate Laporan Produk Berdasarkan Rating (dengan Filter Tanggal)
     */
    public function productsByRating(Request $request)
    {
        // Mengganti pic_email dengan relasi user->seller
        $user = auth()->user();
        $seller = $user->seller;
        
        if (!$seller) {
             return redirect()->route('login')->with('error', 'Data seller tidak ditemukan.');
        }

        // Menggunakan withAvg daripada menghitung avg di memory
        $query = Product::where('seller_id', $seller->id)
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->orderByDesc('reviews_avg_rating'); // Sortir berdasarkan hasil average rating

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $products = $query->get(); // Ambil produk
        

        $filterDateString = ($startDate && $endDate) ? "($startDate s/d $endDate)" : '(Keseluruhan Tanggal)';

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
        // Mengganti pic_email dengan relasi user->seller
        $user = auth()->user();
        $seller = $user->seller;
        
        if (!$seller) {
             return redirect()->route('login')->with('error', 'Data seller tidak ditemukan.');
        }

        // Query utama: Produk dengan stok kurang dari 2
        $query = Product::where('seller_id', $seller->id)
            ->where('stock', '<', 2)
            ->with('category')
            ->withAvg('reviews', 'rating') // Ditambahkan untuk kelengkapan data laporan
            ->orderBy('stock', 'asc');
        
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $products = $query->get();
        $filterDateString = ($startDate && $endDate) ? "($startDate s/d $endDate)" : '(Keseluruhan Tanggal)';
        
        // Cek apakah ada produk pada rentang tanggal yang difilter
        if ($products->isEmpty() && $startDate && $endDate) {
            $totalProductsInDateRange = Product::where('seller_id', $seller->id)
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->count();
            
            if ($totalProductsInDateRange === 0) {
                return redirect()->route('seller.reports.products-need-restock.filter')
                    ->with('error', 'Laporan tidak bisa dicetak. Tidak ada produk yang ditambahkan pada rentang tanggal ' . $startDate . ' sampai ' . $endDate . '.');
            } else {
                return redirect()->route('seller.reports.products-need-restock.filter')
                    ->with('warning', 'Semua produk yang ditambahkan pada rentang tanggal ' . $startDate . ' sampai ' . $endDate . ' memiliki stok yang aman.');
            }
        }

        // Group by category (untuk tampilan laporan need-restock)
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