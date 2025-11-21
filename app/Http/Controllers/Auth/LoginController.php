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

    // 1. Cek Login via Email (pic_email)
    if (filter_var($inputType, FILTER_VALIDATE_EMAIL)) {
        if (Auth::attempt(['pic_email' => $inputType, 'password' => $password], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }
    } 
    // 2. Cek Login via No HP (pic_phone)
    else {
        // Auth::attempt mencari kolom password otomatis, kita cuma perlu kasih 'identifier' yang benar
        if (Auth::attempt(['pic_phone' => $inputType, 'password' => $password], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
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