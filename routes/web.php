<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\detailController;
use App\Http\Controllers\listingController;
use App\Http\Controllers\contactController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\admin\adminController;
use App\Http\Controllers\admin\pesertaController;
use App\Http\Controllers\admin\jadwalController;
use App\Http\Controllers\admin\kursusController;
use App\Http\Controllers\admin\materinController;
use App\Http\Controllers\admin\katalogController;
use App\Http\Controllers\admin\laporanController;
use App\Http\Controllers\admin\analisisController;
use App\Http\Controllers\admin\chatController;
use App\Http\Controllers\sistem\SaccountController;
use App\Http\Controllers\sistem\SconnectionsController;
use App\Http\Controllers\sistem\SnotificationController;


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

// Landing Page Routes
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/detail-kursus', [detailController::class, 'detail'])->name('detail-kursus');
Route::get('/daftar-kursus', [listingController::class, 'listing'])->name('daftar-kursus');
Route::get('/contact', [contactController::class, 'contact'])->name('contact');
Route::get('/login', [LoginController::class, 'login'])->name('login');
route::post('/login', [LoginController::class, 'loginPost'])->name('login.post');
route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'register'])->name('register');
route::post('/register', [RegisterController::class, 'registerPost'])->name('register.post');

// Customer Dashboard Routes
Route::prefix('customer')->name('customer.')->group(function () {
    Route::group(['middleware' => 'auth','checkrole:user'], function () {
        route::get('/customer-dashboard', [customerController::class, 'customerDashboard'])->name('customer-dashboard');
    });
});


// Dashboard Routes
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::group(['middleware' => 'auth','checkrole:admin,user'], function () {
        route::get('/admin-dashboard', [adminController::class, 'indexAdmin'])->name('admin-dashboard');
    });
    Route::get('/admin-dashboard', [adminController::class, 'indexAdmin'])->name('admin-dashboard');
    Route::get('/peserta-kursus', [pesertaController::class, 'pesertaKursus'])->name('peserta-kursus');
    Route::get('/jadwal-kursus', [jadwalController::class, 'jadwalKursus'])->name('jadwal-kursus');
    Route::get('/kursus', [kursusController::class, 'kursus'])->name('kursus');
    Route::post('/kursus', [kursusController::class, 'store'])->name('kursus.store');
    Route::put('/kursus/{id}', [kursusController::class, 'update'])->name('kursus.update');
    Route::delete('/kursus/{id}', [kursusController::class, 'destroy'])->name('kursus.destroy');
    Route::get('/materi-kursus', [materinController::class, 'materiKursus'])->name('materi-kursus');
    Route::get('/materi-kursus/create', [materinController::class, 'create'])->name('materi-kursus.create');
    Route::post('/materi-kursus', [materinController::class, 'store'])->name('materi-kursus.store');
    Route::get('/materi-kursus/{id}', [materinController::class, 'show'])->name('materi-kursus.show');
    Route::get('/materi-kursus/{id}/edit', [materinController::class, 'edit'])->name('materi-kursus.edit');
    Route::put('/materi-kursus/{id}', [materinController::class, 'update'])->name('materi-kursus.update');
    Route::delete('/materi-kursus/{id}', [materinController::class, 'destroy'])->name('materi-kursus.destroy');
    Route::get('/katalog-produk', [katalogController::class, 'katalogProduk'])->name('katalog-produk');
    Route::post('/katalog-produk', [katalogController::class, 'store'])->name('katalog-produk.store');
    Route::get('/katalog-produk/{id}', [katalogController::class, 'show'])->name('katalog-produk.show');
    Route::put('/katalog-produk/{id}', [katalogController::class, 'update'])->name('katalog-produk.update');
    Route::delete('/katalog-produk/{id}', [katalogController::class, 'destroy'])->name('katalog-produk.destroy');
    Route::get('/laporan-keuangan', [laporanController::class, 'laporanKeuangan'])->name('laporan-keuangan');
    Route::get('/analisis-peserta', [analisisController::class, 'analisisPeserta'])->name('analisis-peserta');
    Route::get('/chat', [chatController::class, 'chat'])->name('chat');
    Route::get('/account-sistem', [SaccountController::class, 'accountSistem'])->name('account-sistem');
    Route::get('/connections-sistem', [SconnectionsController::class, 'connectionsSistem'])->name('connections-sistem');
    Route::get('/notifications-sistem', [SnotificationController::class, 'notificationSistem'])->name('notifications-sistem');
});