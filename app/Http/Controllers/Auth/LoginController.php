<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // 1. Tampilkan Halaman Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 2. Proses Login
    public function login(Request $request)
    {
        // Validasi input dasar
        $request->validate([
            'login_identifier' => 'required|string',
            'password' => 'required|string',
        ]);

        $inputType = $request->input('login_identifier');
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        // Tentukan kolom apa yang dipakai: 'pic_email' atau 'pic_phone'
        $loginType = filter_var($inputType, FILTER_VALIDATE_EMAIL) ? 'pic_email' : 'pic_phone';

        // MENGGUNAKAN AUTH::ATTEMPT (Lebih Aman & Ringkas)
        // Laravel otomatis mengecek password hash, kita cukup kasih array kondisi
        if (Auth::attempt([$loginType => $inputType, 'password' => $password], $remember)) {
            
            // Jika Sukses:
            $request->session()->regenerate();
            
            // Cek status seller (Opsional, jika ingin membatasi yang login hanya yang ACTIVE)
            /*
            if (Auth::user()->status !== 'ACTIVE') {
                Auth::logout();
                return back()->withErrors(['login_identifier' => 'Akun Anda belum aktif atau ditolak.']);
            }
            */

            return redirect()->intended('dashboard');
        }

        // Jika Gagal:
        return back()->withErrors([
            'login_identifier' => 'Email/No HP atau password salah.',
        ])->onlyInput('login_identifier');
    }

    // 3. Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}