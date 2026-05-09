<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

use App\Http\Controllers\Admin\PenghuniController;

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    });

    Route::resource('penghuni', PenghuniController::class);
});

Route::middleware(['auth:penghuni'])->group(function () {
    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    });
});
