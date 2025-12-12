<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('owner.reports.index');
    }

    // --- HELPER FUNCTION (PENTING) ---
    // Fungsi ini membuat array $meta secara otomatis dan konsisten untuk semua laporan
    private function getMeta($title, $request)
    {
        $start = Carbon::parse($request->start_date)->format('d/m/Y');
        $end = Carbon::parse($request->end_date)->format('d/m/Y');

        return [
            'title' => $title,
            'period' => "Periode: $start - $end", // <-- INI KUNCI AGAR ERROR HILANG
            'date' => Carbon::now()->format('d-m-Y'),
            'processor' => auth()->user()->name ?? 'Administrator'
        ];
    }

    // 1. (SRS-09) Laporan Status Penjual
    public function reportSellerStatus(Request $request)
    {
        // Validasi Tanggal
        $request->validate(['start_date' => 'required|date', 'end_date' => 'required|date']);

        // Filter Query
        $data = Seller::with('user')
    ->whereBetween('created_at', [$request->start_date, $request->end_date . ' 23:59:59'])
    ->orderByRaw("FIELD(status, 'ACTIVE', 'PENDING', 'REJECTED')")
    ->get();


        // Gunakan Helper getMeta
        $meta = $this->getMeta('Laporan Status Penjual', $request);

        $pdf = Pdf::loadView('owner.reports.pdf_seller_status', compact('data', 'meta'));
        return $pdf->stream('Laporan_Status_Penjual.pdf');
    }

    // 2. (SRS-10) Laporan Lokasi Toko
    public function reportSellerProvince(Request $request)
    {
        // Validasi Tanggal
        $request->validate(['start_date' => 'required|date', 'end_date' => 'required|date']);

        // Filter Query
        $data = Seller::whereBetween('created_at', [$request->start_date, $request->end_date . ' 23:59:59'])
                      ->orderBy('pic_province', 'asc')
                      ->get();

        // Gunakan Helper getMeta
        $meta = $this->getMeta('Laporan Lokasi Toko', $request);

        $pdf = Pdf::loadView('owner.reports.pdf_seller_province', compact('data', 'meta'));
        return $pdf->stream('Laporan_Toko_Provinsi.pdf');
    }

    // 3. (SRS-11) Laporan Rating Produk
    public function reportProductRating(Request $request)
    {
        // Validasi Tanggal
        $request->validate(['start_date' => 'required|date', 'end_date' => 'required|date']);

        // Filter Query - ambil dari reviews yang ada di periode tersebut
        $data = \App\Models\Review::with(['product.seller', 'product.category'])
                    ->whereBetween('created_at', [$request->start_date, $request->end_date . ' 23:59:59'])
                    ->orderByDesc('rating')
                    ->get();

        // Gunakan Helper getMeta
        $meta = $this->getMeta('Laporan Rating Produk', $request);

        $pdf = Pdf::loadView('owner.reports.pdf_product_rating', compact('data', 'meta'));
        return $pdf->stream('Laporan_Produk_Rating.pdf');
    }
}