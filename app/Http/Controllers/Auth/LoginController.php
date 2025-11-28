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
        // Validasi input
        $request->validate([
            'login_identifier' => 'required|string',
            'password' => 'required|string',
        ]);

        $identifier = $request->input('login_identifier');
        $password   = $request->input('password');
        $remember   = $request->boolean('remember');

        // Tentukan login pakai email atau phone
        $loginField = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'pic_email' : 'pic_phone';

        // Attempt login
        if (Auth::attempt([$loginField => $identifier, 'password' => $password], $remember)) {

            $request->session()->regenerate();
            $user = Auth::user();

            // Redirect sesuai role
            if ($user->role === 'seller') {
                return redirect()->route('seller.dashboard');
            }

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // Jika role tidak dikenal
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'login_identifier' => 'Role tidak dikenal.',
            ]);
        }

        // Jika gagal login
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
