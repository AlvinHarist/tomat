<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Registered;

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

        // Send email verification ONLY when APPROVED
        if ($request->status == 'ACTIVE') {
            // Find user for this seller
            $user = User::where('email', $seller->pic_email)->first();
            
            if ($user && !$user->hasVerifiedEmail()) {
                // Generate verification URL
                $verificationUrl = URL::temporarySignedRoute(
                    'verification.verify',
                    now()->addMinutes(60),
                    [
                        'id' => $user->id,
                        'hash' => sha1($user->email)
                    ]
                );

                // Send email
                Mail::send('emails.seller-verification', [
                    'sellerName' => $seller->pic_name,
                    'storeName' => $seller->store_name,
                    'verificationUrl' => $verificationUrl
                ], function ($message) use ($seller) {
                    $message->to($seller->pic_email)
                            ->subject('Verifikasi Toko ToMaT - ' . $seller->store_name);
                });
            }
        }

        $message = $request->status == 'ACTIVE' ? 'Penjual berhasil diaktifkan! Email verifikasi telah dikirim.' : 'Penjual berhasil ditolak.';
        return redirect()->route('owner.sellers.index')->with('success', $message);
    }
}