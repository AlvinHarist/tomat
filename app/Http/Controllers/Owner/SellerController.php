<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;

class SellerController extends Controller
{
    // 1. Tampilkan Daftar Penjual
    public function index()
    {
        // Ambil data terbaru, urutkan yang PENDING di atas
        $sellers = Seller::orderByRaw("FIELD(status, 'PENDING', 'ACTIVE', 'REJECTED')")
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('owner.sellers.index', compact('sellers'));
    }

    // 2. Tampilkan Detail Penjual (Untuk cek KTP)
    public function show($id)
    {
        $seller = Seller::findOrFail($id);
        return view('owner.sellers.show', compact('seller'));
    }

    // 3. Proses Verifikasi (Terima/Tolak)
    public function updateStatus(Request $request, $id)
    {
        $seller = Seller::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:ACTIVE,REJECTED'
        ]);

        $seller->update([
            'status' => $request->status
        ]);

        // TODO: Di sini nanti kita tambahkan kirim email notifikasi (SRS-02)

        $message = $request->status == 'ACTIVE' ? 'Penjual berhasil diaktifkan!' : 'Penjual berhasil ditolak.';
        return redirect()->route('owner.sellers.index')->with('success', $message);
    }
}