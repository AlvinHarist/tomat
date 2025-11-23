<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductController;

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

// Rute Login (Placeholder agar tidak error saat redirect)
Route::get('/login', function () {
    return "Halaman Login (Belum dibuat)";
})->name('login');

// Rute Verifikasi Email
Route::get('/email/verify', function () {
    return "Halaman Verifikasi Email (Belum dibuat)";
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/home', [ProductController::class, 'index'])->name('home');
Route::resource('product', ProductController::class)->except(['index']);