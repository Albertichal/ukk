<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Siswa\AspirasiController;
use App\Http\Controllers\PJ\AspirasiController as PJAspirasiController;

// Auth
Route::get('/', fn() => redirect()->route('login'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/assign/{id}', [DashboardController::class, 'assign'])->name('admin.assign');
    Route::post('/feedback/{id}', [DashboardController::class, 'feedback'])->name('admin.feedback');
    Route::post('/tolak/{id}', [DashboardController::class, 'tolak'])->name('admin.tolak');
    Route::post('/konfirmasi-tolak/{id}', [DashboardController::class, 'konfirmasiTolak'])->name('admin.konfirmasi_tolak');
    Route::get('/histori', [DashboardController::class, 'histori'])->name('admin.histori');
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/kategori', [KategoriController::class, 'index'])->name('admin.kategori');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('admin.kategori.store');
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('admin.kategori.destroy');
});

// Siswa
Route::prefix('siswa')->middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/dashboard', [AspirasiController::class, 'index'])->name('siswa.dashboard');
    Route::post('/aspirasi', [AspirasiController::class, 'store'])->name('siswa.aspirasi.store');
    Route::get('/riwayat', [AspirasiController::class, 'riwayat'])->name('siswa.riwayat');
});

// Penanggung Jawab
Route::prefix('pj')->middleware(['auth', 'role:penanggung_jawab'])->group(function () {
    Route::get('/dashboard', [PJAspirasiController::class, 'index'])->name('pj.dashboard');
    Route::post('/proses/{id}', [PJAspirasiController::class, 'updateStatus'])->name('pj.proses');
    Route::post('/selesai/{id}', [PJAspirasiController::class, 'selesai'])->name('pj.selesai');
    Route::post('/tolak/{id}', [PJAspirasiController::class, 'tolak'])->name('pj.tolak');
});
