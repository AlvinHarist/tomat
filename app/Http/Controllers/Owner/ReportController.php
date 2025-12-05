<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\Review;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    // 1. Halaman Menu Laporan
    public function index()
    {
        return view('owner.reports.index');
    }

    // 2. (SRS-09) Laporan Akun Penjual Berdasarkan Status
    public function reportSellerStatus()
    {
        // Urutkan: ACTIVE dulu, baru PENDING, baru REJECTED (Sesuai doc: Aktif dulu baru tidak)
        $data = Seller::orderByRaw("FIELD(status, 'ACTIVE', 'PENDING', 'REJECTED')")
                      ->orderBy('created_at', 'desc')
                      ->get();

        $meta = [
            'title' => 'Laporan Daftar Akun Penjual Berdasarkan Status',
            'date' => Carbon::now()->format('d-m-Y'),
            'processor' => Auth::guard('owner')->user()->name ?? 'Administrator'
        ];

        $pdf = Pdf::loadView('owner.reports.pdf_seller_status', compact('data', 'meta'));
        return $pdf->stream('Laporan_Status_Penjual.pdf'); // stream() agar bisa dipreview dulu
    }

    // 3. (SRS-10) Laporan Daftar Toko Berdasarkan Lokasi Provinsi
    public function reportSellerProvince()
    {
        // Urutkan berdasarkan kolom provinsi
        $data = Seller::orderBy('pic_province', 'asc')->get();

        $meta = [
            'title' => 'Laporan Daftar Toko Berdasarkan Lokasi Provinsi',
            'date' => Carbon::now()->format('d-m-Y'),
            'processor' => Auth::guard('owner')->user()->name ?? 'Administrator'
        ];

        $pdf = Pdf::loadView('owner.reports.pdf_seller_province', compact('data', 'meta'));
        return $pdf->stream('Laporan_Toko_Provinsi.pdf');
    }

    // 4. (SRS-11) Laporan Daftar Produk Berdasarkan Rating
    public function reportProductRating()
    {
        // Ambil data review, join dengan produk dan kategori
        // Provinsi pemberi rating disimpan dalam kolom `province` di tabel `reviews`
        $data = Review::with(['product.seller', 'product.category'])
                ->orderBy('rating', 'desc') // Urutkan rating tertinggi
                ->get();

        $meta = [
            'title' => 'Laporan Daftar Produk Berdasarkan Rating',
            'date' => Carbon::now()->format('d-m-Y'),
            'processor' => Auth::guard('owner')->user()->name ?? 'Administrator'
        ];

        $pdf = Pdf::loadView('owner.reports.pdf_product_rating', compact('data', 'meta'));
        return $pdf->stream('Laporan_Produk_Rating.pdf');
    }
}