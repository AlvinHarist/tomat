<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
// Import Controller Owner
use App\Http\Controllers\Owner\Auth\LoginController as OwnerLoginController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\SellerController as OwnerSellerController;
// Import Controller Seller
use App\Http\Controllers\Seller\Auth\LoginController as SellerLoginController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;

use App\Mail\SendTestEmail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Rute Katalog & Pengunjung (Home, Register, Product) ---
Route::get('/', [ProductController::class, 'index'])->name('home'); // Ganti register menjadi home katalog
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register'); // Tambahkan rute register terpisah
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::get('/search', [ProductController::class, 'search'])->name('search');
Route::resource('product', ProductController::class)->except(['index']);
Route::post('/review', [ReviewController::class, 'store'])->name('review.store');


// --- Rute Admin/Owner ---
Route::prefix('owner')->name('owner.')->group(function () {
    
    // Login & Logout
    Route::get('/login', [OwnerLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [OwnerLoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [OwnerLoginController::class, 'logout'])->name('logout');

    // Protected Routes
    Route::middleware('auth:owner')->group(function () {
        Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
        
        // Manajemen Seller oleh Owner
        Route::get('/sellers', [OwnerSellerController::class, 'index'])->name('sellers.index');
        Route::get('/sellers/{id}', [OwnerSellerController::class, 'show'])->name('sellers.show');
        Route::post('/sellers/{id}/status', [OwnerSellerController::class, 'updateStatus'])->name('sellers.updateStatus');
    });
});


// --- Rute Penjual (Seller) ---
Route::prefix('seller')->name('seller.')->group(function () {
    
    // Login & Logout
    Route::get('/login', [SellerLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [SellerLoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [SellerLoginController::class, 'logout'])->name('logout');
    
    // Status Pendaftaran (Pending/Rejected)
    Route::get('/status/{status}', function ($status) {
        return view('seller.status', ['status' => strtoupper($status)]);
    })->name('status');
    
    // Protected Routes (Memerlukan auth:seller dan akun harus ACTIVE)
    Route::middleware(['auth:seller'])->group(function () {
        
        // Dashboard (Overview)
        Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');

        // Manajemen Produk (Upload Produk, My Products, dll.)
        Route::resource('products', SellerProductController::class)->except(['show']);
    });
});

// --- Rute Verifikasi Email (Jika menggunakan fitur ini) ---
Route::get('/email/verify', function () {
    return view('auth.verify-email'); // Ganti dengan view yang sesuai
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('home'); // Redirect ke home katalog setelah verifikasi
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// --- Test Email (Optional) ---
Route::get('send-mail', function() {
    $message = 'Salam Tomat';
    Mail::to('alvin.harist502@gmail.com')->send(new SendTestEmail($message));
});