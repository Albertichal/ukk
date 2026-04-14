<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\PantauController;
use App\Http\Controllers\Admin\RuanganController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PJ\AspirasiController as PJAspirasiController;
use App\Http\Controllers\Siswa\AspirasiController;
use App\Http\Controllers\Admin\PengaturanController;

// Auth
Route::get('/', fn () => redirect()->route('login'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/sedang-dikerjakan/{id}', [DashboardController::class, 'sedangDikerjakan'])->name('admin.sedang_dikerjakan');
    Route::post('/selesai/{id}', [DashboardController::class, 'selesai'])->name('admin.selesai');
    Route::post('/tolak/{id}', [DashboardController::class, 'tolak'])->name('admin.tolak');
    Route::get('/histori', [DashboardController::class, 'histori'])->name('admin.histori');

    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/kategori', [KategoriController::class, 'index'])->name('admin.kategori');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('admin.kategori.store');
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('admin.kategori.destroy');

    Route::get('/ruangan', [RuanganController::class, 'index'])->name('admin.ruangan');
    Route::post('/ruangan', [RuanganController::class, 'store'])->name('admin.ruangan.store');
    Route::post('/ruangan/{id}', [RuanganController::class, 'update'])->name('admin.ruangan.update');
    Route::delete('/ruangan/{id}', [RuanganController::class, 'destroy'])->name('admin.ruangan.destroy');

    Route::get('/kelas', [KelasController::class, 'index'])->name('admin.kelas');
    Route::post('/kelas', [KelasController::class, 'store'])->name('admin.kelas.store');
    Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])->name('admin.kelas.destroy');

    Route::get('/pantau', [PantauController::class, 'index'])->name('admin.pantau');
    Route::get('/pantau/{id}', [PantauController::class, 'show'])->name('admin.pantau.show');
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('admin.pengaturan');
    Route::post('/pengaturan', [PengaturanController::class, 'update'])->name('admin.pengaturan.update');
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
    Route::post('/terima/{id}', [PJAspirasiController::class, 'terima'])->name('pj.terima');
    Route::post('/selesai/{id}', [PJAspirasiController::class, 'selesai'])->name('pj.selesai');
    Route::post('/tidak-mampu/{id}', [PJAspirasiController::class, 'tidakMampu'])->name('pj.tidak_mampu');
    Route::post('/tolak/{id}', [PJAspirasiController::class, 'tolak'])->name('pj.tolak');
});
