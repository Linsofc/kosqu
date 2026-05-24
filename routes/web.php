<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

use App\Http\Controllers\Auth\ForgotPasswordController;
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

use App\Http\Controllers\Admin\PenghuniController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KamarController;
use App\Http\Controllers\User\UserDashboardController;

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('penghuni', PenghuniController::class);
    Route::get('penghuni-arsip', [PenghuniController::class, 'trashed'])->name('penghuni.trashed');
    Route::post('penghuni-arsip/{id}/restore', [PenghuniController::class, 'restore'])->name('penghuni.restore');
    Route::delete('penghuni-arsip/{id}/force-delete', [PenghuniController::class, 'forceDelete'])->name('penghuni.force-delete');
    Route::resource('kamar', KamarController::class);
    Route::resource('booking', \App\Http\Controllers\Admin\BookingController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('/booking/{booking}/confirm', [\App\Http\Controllers\Admin\BookingController::class, 'confirm'])->name('booking.confirm');
    Route::post('/booking/{booking}/cancel', [\App\Http\Controllers\Admin\BookingController::class, 'cancel'])->name('booking.cancel');
    Route::resource('pengumuman', \App\Http\Controllers\Admin\PengumumanController::class);
    Route::get('/transaksi', [\App\Http\Controllers\Admin\TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi/{transaksi}/validasi', [\App\Http\Controllers\Admin\TransaksiController::class, 'validasi'])->name('transaksi.validasi');
    Route::get('/tempo', [\App\Http\Controllers\Admin\TempoController::class, 'index'])->name('tempo.index');
    Route::put('/tempo/{penghuni}/update-tagihan', [\App\Http\Controllers\Admin\TempoController::class, 'updateTagihan'])->name('tempo.update-tagihan');
    Route::post('/tempo/{penghuni}/send-reminder', [\App\Http\Controllers\Admin\TempoController::class, 'sendReminder'])->name('tempo.send-reminder');
    Route::post('/tempo/send-bulk-reminder', [\App\Http\Controllers\Admin\TempoController::class, 'sendBulkReminder'])->name('tempo.send-bulk-reminder');
    Route::get('/laporan/export', [\App\Http\Controllers\Admin\LaporanController::class, 'export'])->name('laporan.export');
    Route::get('/laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    Route::put('/settings/whatsapp', [\App\Http\Controllers\Admin\SettingController::class, 'updateWhatsapp'])->name('settings.update-whatsapp');
    Route::put('/settings/tempo', [\App\Http\Controllers\Admin\SettingController::class, 'updateTempo'])->name('settings.update-tempo');
    Route::post('/settings/test-whatsapp', [\App\Http\Controllers\Admin\SettingController::class, 'testWhatsapp'])->name('settings.test-whatsapp');
});

Route::middleware(['auth:penghuni'])->group(function () {
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/user/invoice', [\App\Http\Controllers\User\InvoiceController::class, 'index'])->name('user.invoice');
    Route::get('/user/invoice/{transaksi}/pdf', [\App\Http\Controllers\User\InvoiceController::class, 'downloadPdf'])->name('user.invoice.pdf');
    Route::get('/user/payment', [\App\Http\Controllers\User\PaymentController::class, 'index'])->name('user.payment');
    Route::post('/user/payment', [\App\Http\Controllers\User\PaymentController::class, 'store'])->name('user.payment.store');
    Route::get('/user/history', [\App\Http\Controllers\User\HistoryController::class, 'index'])->name('user.history');
    Route::get('/user/profile', [\App\Http\Controllers\User\UserProfileController::class, 'index'])->name('user.profile');
    Route::put('/user/profile', [\App\Http\Controllers\User\UserProfileController::class, 'update'])->name('user.profile.update');
    Route::get('/user/pengumuman', [\App\Http\Controllers\User\PengumumanController::class, 'index'])->name('user.pengumuman');
    Route::get('/user/help-center', [\App\Http\Controllers\User\HelpCenterController::class, 'index'])->name('user.help');
});
