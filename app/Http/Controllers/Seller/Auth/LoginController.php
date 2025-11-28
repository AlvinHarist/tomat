<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // 1. Tampilkan Form Login Seller
    public function showLoginForm()
    {
        return view('seller.login'); // Ganti view ke seller.login
    }

    // 2. Proses Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'pic_email' => 'required|email', // Gunakan pic_email untuk login
            'password' => 'required',
        ]);
        
        // Tambahkan kondisi untuk hanya memperbolehkan status ACTIVE
        $credentials['status'] = 'ACTIVE';

        // Gunakan Guard 'seller'
        if (Auth::guard('seller')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Redirect ke Dashboard Seller
            return redirect()->route('seller.dashboard');
        }

        // Jika login gagal (termasuk status non-aktif)
        return back()->withErrors([
            'pic_email' => 'Email atau password salah, atau akun Anda belum aktif/ditolak.',
        ])->onlyInput('pic_email');
    }

    // 3. Logout Seller
    public function logout(Request $request)
    {
        Auth::guard('seller')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('seller.login'); // Redirect ke halaman login seller
    }
}