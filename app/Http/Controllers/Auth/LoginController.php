<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Seller;

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
            'login_identifier' => 'required|string', // Bisa email atau HP
            'password' => 'required|string',
        ]);

        $inputType = $request->input('login_identifier');
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        // Cek apakah inputnya format Email
        if (filter_var($inputType, FILTER_VALIDATE_EMAIL)) {
            // --- LOGIKA LOGIN VIA EMAIL ---
            if (Auth::attempt(['email' => $inputType, 'password' => $password], $remember)) {
                $request->session()->regenerate();
                return redirect()->intended('dashboard'); // Ganti 'dashboard' sesuai kebutuhan
            }
        } else {
            // --- LOGIKA LOGIN VIA NO HP ---
            // 1. Cari di tabel seller_profiles dulu
            $seller = Seller::where('phone', $inputType)->first();

            if ($seller) {
                // 2. Jika ketemu, ambil user_id-nya
                $user = User::find($seller->user_id);

                // 3. Cek password manual & login
                if ($user && Auth::attempt(['email' => $user->email, 'password' => $password], $remember)) {
                    $request->session()->regenerate();
                    return redirect()->intended('dashboard');
                }
            }
        }

        // Jika gagal login (email/hp atau password salah)
        return back()->withErrors([
            'login_identifier' => 'The provided credentials do not match our records.',
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