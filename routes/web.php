<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;

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

Route::get('/login', function () {
  return view('auth.register');
})->name('login');

Route::get('/home', [ProductController::class, 'index'])->name('home.index');

Route::get('/product', function () {
  return view('product.index');
});

// Product CRUD (resource)
Route::resource('products', ProductController::class);

Route::resource('products', ProductController::class);