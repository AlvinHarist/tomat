<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Seller;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('seller.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // Check if seller exists (correct column name is pic_email)
        $seller = Seller::where('pic_email', $request->email)->first();

        if (!$seller) {
            return back()->withErrors(['email' => 'Email tidak terdaftar.'])->withInput();
        }

        // Check seller status
        if ($seller->status === 'PENDING') {
            return redirect()->route('seller.status', ['status' => 'pending']);
        }

        if ($seller->status === 'REJECTED') {
            return redirect()->route('seller.status', ['status' => 'rejected']);
        }

        // If status is ACTIVE, attempt login via users table
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Check if email is verified (via users table)
            $user = Auth::user();
            if (!$user->email_verified_at) {
                Auth::logout();
                return back()->withErrors(['email' => 'Email Anda belum diverifikasi. Silakan cek email untuk link verifikasi.'])->withInput();
            }

            // Redirect to seller dashboard
            return redirect()->intended(route('seller.dashboard'));
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('seller.login');
    }
}
