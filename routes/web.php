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
use App\Http\Controllers\Owner\ReportController;
use App\Http\Controllers\Owner\CategoryController;
use App\Http\Controllers\Owner\Auth\LoginController as OwnerLoginController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Mail\SendTestEmail;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Rute untuk menampilkan halaman formulir
Route::get('/', [RegisterController::class, 'showRegistrationForm'])->name('register');

// Rute untuk memproses data saat formulir di-submit
Route::post('/', [RegisterController::class, 'store'])->name('register.store');

// Seller Routes
Route::prefix('seller')->name('seller.')->group(function () {
    // Login & Logout
    Route::get('/login', [App\Http\Controllers\Seller\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Seller\Auth\LoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [App\Http\Controllers\Seller\Auth\LoginController::class, 'logout'])->name('logout');
    
    // Status Page
    Route::get('/status/{status}', function ($status) {
        return view('seller.status', ['status' => strtoupper($status)]);
    })->name('status');
    
    // Protected Routes
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', function () {
            return view('seller.dashboard');
        })->name('dashboard');
    });
});

Route::prefix('owner')->name('owner.')->group(function () {
    
    // Login & Logout
    Route::get('/login', [OwnerLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [OwnerLoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [OwnerLoginController::class, 'logout'])->name('logout');

    // Dashboard (Protected by auth:owner middleware)
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard')->middleware('auth:owner');

    Route::get('/sellers', [App\Http\Controllers\Owner\SellerController::class, 'index'])->name('sellers.index');
    Route::get('/sellers/{id}', [App\Http\Controllers\Owner\SellerController::class, 'show'])->name('sellers.show');
    Route::post('/sellers/{id}/status', [App\Http\Controllers\Owner\SellerController::class, 'updateStatus'])->name('sellers.updateStatus');

    // Reports
    // Menu Utama Laporan
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Action Print PDF
    Route::get('/reports/seller-status', [ReportController::class, 'reportSellerStatus'])->name('reports.seller_status');
    Route::get('/reports/seller-province', [ReportController::class, 'reportSellerProvince'])->name('reports.seller_province');
    Route::get('/reports/product-rating', [ReportController::class, 'reportProductRating'])->name('reports.product_rating');

    // Rute Kategori
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

// Rute Verifikasi Email
Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = \App\Models\User::findOrFail($id);

    // Verify hash
    if (!hash_equals($hash, sha1($user->email))) {
        abort(403, 'Invalid verification link.');
    }

    // Check if already verified
    if ($user->email_verified_at) {
        return redirect()->route('seller.login')->with('status', 'Email sudah terverifikasi sebelumnya.');
    }

    // Mark as verified
    $user->email_verified_at = now();
    $user->save();

    return redirect()->route('seller.login')->with('status', 'Email berhasil diverifikasi! Silakan login.');
})->middleware('signed')->name('verification.verify');

Route::get('/email/verify', function () {
    return redirect()->route('seller.login')->with('status', 'Silakan cek email Anda untuk link verifikasi.');
})->name('verification.notice');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


Route::get('send-mail', function() {
    $message = 'Salam Tomat';
    Mail::to('alvin.harist502@gmail.com')->send(new SendTestEmail($message));
});

Route::get('/home', [ProductController::class, 'index'])->name('home');
Route::get('/search', [ProductController::class, 'search'])->name('search');
Route::resource('product', ProductController::class)->except(['index']);

Route::post('/review', [ReviewController::class, 'store'])->name('review.store');