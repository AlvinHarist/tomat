<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login_identifier' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $identifier = trim($request->login_identifier);
        $password   = $request->password;
        $remember   = $request->boolean('remember');

        /**
         * ======================================
         * LOGIN DENGAN EMAIL (OWNER / SELLER)
         * ======================================
         */
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {

            if (!Auth::attempt(['email' => $identifier, 'password' => $password], $remember)) {
                return back()->withErrors([
                    'login_identifier' => 'Email atau password salah.',
                ])->onlyInput('login_identifier');
            }

            $request->session()->regenerate();

            return $this->redirectByRole();
        }

        /**
         * ======================================
         * LOGIN DENGAN NO HP (SELLER SAJA)
         * ======================================
         */
        if (!preg_match('/^\d+$/', $identifier)) {
            return back()->withErrors([
                'login_identifier' => 'Format email atau nomor HP tidak valid.',
            ])->onlyInput('login_identifier');
        }

        $seller = Seller::with('user')
            ->where('pic_phone', $identifier)
            ->first();

        if (!$seller || !$seller->user) {
            return back()->withErrors([
                'login_identifier' => 'No HP atau password salah.',
            ])->onlyInput('login_identifier');
        }

        if (!Auth::attempt(['id' => $seller->user->id, 'password' => $password], $remember)) {
            return back()->withErrors([
                'login_identifier' => 'No HP atau password salah.',
            ])->onlyInput('login_identifier');
        }

        $request->session()->regenerate();

        return $this->redirectByRole();
    }

    /**
     * Redirect sesuai role user
     */
    protected function redirectByRole()
    {
        $user = Auth::user();

        // ================= OWNER =================
        if ($user->role === 'platform') {
            return redirect()->route('owner.dashboard');
        }

        // ================= SELLER =================
        if ($user->role === 'seller') {

            $seller = $user->seller;

            if (!$seller) {
                Auth::logout();
                return redirect()->route('login')
                    ->withErrors(['login_identifier' => 'Data seller tidak ditemukan.']);
            }

            if ($seller->status !== 'ACTIVE') {
                Auth::logout();
                return redirect()->route('seller.status', strtolower($seller->status));
            }

            return redirect()->route('seller.dashboard');
        }

        // ================= ROLE TIDAK DIKENAL =================
        Auth::logout();
        return redirect()->route('login')
            ->withErrors(['login_identifier' => 'Role akun tidak dikenali.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
