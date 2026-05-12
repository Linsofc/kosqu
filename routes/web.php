<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

use App\Http\Controllers\Admin\PenghuniController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KamarController;
use App\Http\Controllers\User\UserDashboardController;

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('penghuni', PenghuniController::class);
    Route::resource('kamar', KamarController::class);
    Route::resource('pengumuman', \App\Http\Controllers\Admin\PengumumanController::class);
    Route::get('/transaksi', [\App\Http\Controllers\Admin\TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi/{transaksi}/validasi', [\App\Http\Controllers\Admin\TransaksiController::class, 'validasi'])->name('transaksi.validasi');
    Route::get('/tempo', [\App\Http\Controllers\Admin\TempoController::class, 'index'])->name('tempo.index');
    Route::put('/tempo/{penghuni}/update-tagihan', [\App\Http\Controllers\Admin\TempoController::class, 'updateTagihan'])->name('tempo.update-tagihan');
    Route::get('/laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
});

Route::middleware(['auth:penghuni'])->group(function () {
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/user/invoice', [\App\Http\Controllers\User\InvoiceController::class, 'index'])->name('user.invoice');
    Route::get('/user/payment', [\App\Http\Controllers\User\PaymentController::class, 'index'])->name('user.payment');
    Route::post('/user/payment', [\App\Http\Controllers\User\PaymentController::class, 'store'])->name('user.payment.store');
    Route::get('/user/history', [\App\Http\Controllers\User\HistoryController::class, 'index'])->name('user.history');
    Route::get('/user/profile', [\App\Http\Controllers\User\UserProfileController::class, 'index'])->name('user.profile');
    Route::put('/user/profile', [\App\Http\Controllers\User\UserProfileController::class, 'update'])->name('user.profile.update');
});
