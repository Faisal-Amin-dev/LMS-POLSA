<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\DosenDashboardController;
use Illuminate\Support\Facades\Route;

// 1. GUEST ROUTES (Bisa diakses tanpa login)
Route::get('/', function () { return view('landing'); });
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Tes (Untuk pastikan Laravel hidup)
Route::get('/tes-nyawa', function() { return "<h1>HALO PAKDHE, SAYA HIDUP!</h1>"; });

// 2. AUTH ROUTES (Harus login dulu)
Route::middleware(['auth'])->group(function () {

    // Group Khusus Admin
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard');
        Route::get('/dosen', function () { return view('admin.dosen'); })->name('dosen');
        Route::get('/kelas', function () { return view('admin.kelas'); })->name('kelas');
        Route::get('/mahasiswa', function () { return view('admin.mahasiswa'); })->name('mahasiswa');
        
        Route::post('/dosen/store', [DosenController::class, 'store'])->name('dosen.store');
        Route::post('/kelas/store', [KelasController::class, 'store'])->name('kelas.store');
        Route::post('/mahasiswa/store', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
    });

    // Group Khusus Dosen
    Route::middleware(['role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');
        Route::get('/kelas/{id}', [DosenDashboardController::class, 'show'])->name('kelas.show');
        Route::post('/materi/store', [MaterialController::class, 'store'])->name('materi.store');
    });

    // Group Khusus Mahasiswa (INI YANG KITA BUAT)
    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [MahasiswaController::class, 'index'])->name('dashboard');
    });

});