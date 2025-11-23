<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProductController;
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

// Rute Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        
        $user = Auth::user();
        return "<h1>Dashboard Penjual (Coming Soon)</h1>" .
               "<p>Halo, <b>" . $user->pic_name . "</b>!</p>" .
               "<p>Bagian ini sedang dikerjakan oleh teman.</p>" .
               "<form action='" . route('logout') . "' method='POST'>" . 
               csrf_field() . 
               "<button type='submit'>Logout</button></form>";
               
    })->name('seller.dashboard'); // <--- PENTING: Nama ini harus sama dengan di LoginController

});

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


Route::get('send-mail', function() {
    $message = 'Salam Tomat';
    Mail::to('alvin.harist502@gmail.com')->send(new SendTestEmail($message));
});

Route::get('/home', [ProductController::class, 'index'])->name('home');
Route::resource('product', ProductController::class)->except(['index']);