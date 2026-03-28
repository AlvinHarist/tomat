<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;

use App\Http\Controllers\Owner\ReportController;
use App\Http\Controllers\Owner\CategoryController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;

use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Seller\ReportController as SellerReportController;
use App\Http\Controllers\Owner\SellerController as OwnerSellerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =====================
// REGISTER (SELLER REGISTRATION PAGE)
// =====================
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// API endpoints for dependent dropdown
Route::get('/api/cities/{provinceCode}', [RegisterController::class, 'getCities'])->name('api.cities');
Route::get('/api/districts/{cityCode}', [RegisterController::class, 'getDistricts'])->name('api.districts');
Route::get('/api/villages/{districtCode}', [RegisterController::class, 'getVillages'])->name('api.villages');

// =====================
// AUTH (UNIFIED LOGIN)
// =====================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// =====================
// SELLER ROUTES
// =====================
Route::prefix('seller')->name('seller.')->group(function () {

    // Status Page (public)
    Route::get('/status/{status}', function ($status) {
        return view('seller.status', ['status' => strtoupper($status)]);
    })->name('status');

    // Protected Seller Routes
    Route::middleware(['auth', 'role:seller'])->group(function () {

        Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');

        Route::get('/reviewers/province', [SellerDashboardController::class, 'reviewersByProvinceIndex'])
            ->name('reviewers.by-province.index');

        // Products
        Route::get('/products', [SellerProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [SellerProductController::class, 'create'])->name('products.create');
        Route::post('/products', [SellerProductController::class, 'store'])->name('products.store');
        Route::get('/products/{id}/edit', [SellerProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{id}', [SellerProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{id}', [SellerProductController::class, 'destroy'])->name('products.destroy');

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [SellerReportController::class, 'index'])->name('index');

            Route::get('/stock/filter', [SellerReportController::class, 'productsByStockFilter'])->name('products-by-stock.filter');
            Route::get('/stock', [SellerReportController::class, 'productsByStock'])->name('stock');

            Route::get('/rating/filter', [SellerReportController::class, 'productsByRatingFilter'])->name('products-by-rating.filter');
            Route::get('/rating', [SellerReportController::class, 'productsByRating'])->name('rating');

            Route::get('/restock/filter', [SellerReportController::class, 'productsNeedRestockFilter'])->name('products-need-restock.filter');
            Route::get('/restock', [SellerReportController::class, 'productsNeedRestock'])->name('restock');
        });
    });
});

// =====================
// OWNER ROUTES
// =====================
Route::prefix('owner')->name('owner.')->middleware(['auth', 'role:owner'])->group(function () {

    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');

    Route::get('/sellers', [OwnerSellerController::class, 'index'])->name('sellers.index');
    Route::get('/sellers/{id}', [OwnerSellerController::class, 'show'])->name('sellers.show');
    Route::post('/sellers/{id}/status', [OwnerSellerController::class, 'updateStatus'])->name('sellers.updateStatus');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/seller-status', [ReportController::class, 'reportSellerStatus'])->name('reports.seller_status');
    Route::get('/reports/seller-province', [ReportController::class, 'reportSellerProvince'])->name('reports.seller_province');
    Route::get('/reports/product-rating', [ReportController::class, 'reportProductRating'])->name('reports.product_rating');

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

// =====================
// EMAIL VERIFICATION
// =====================

// Note: route ini mengarahkan ke /login (bukan seller.login)
Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = \App\Models\User::findOrFail($id);

    if (!hash_equals($hash, sha1($user->email))) {
        abort(403, 'Invalid verification link.');
    }

    if ($user->email_verified_at) {
        return redirect()->route('login')->with('status', 'Email sudah terverifikasi sebelumnya.');
    }

    $user->email_verified_at = now();
    $user->save();

    return redirect()->route('login')->with('status', 'Email berhasil diverifikasi! Silakan login.');
})->middleware('signed')->name('verification.verify');

Route::get('/email/verify', function () {
    return redirect()->route('login')->with('status', 'Silakan cek email Anda untuk link verifikasi.');
})->name('verification.notice');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// =====================
// PUBLIC PRODUCT ROUTES (NO AUTH)
// =====================
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/search', [ProductController::class, 'search'])->name('search');
Route::resource('product', ProductController::class)->except(['index']);
Route::post('/review', [ReviewController::class, 'store'])->name('review.store');
