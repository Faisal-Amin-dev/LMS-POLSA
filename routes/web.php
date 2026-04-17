<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MahasiswaController;

// Route untuk halaman login dan logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route untuk tambah data dosen, kelas, dan mahasiswa

Route::post('/admin/dosen/store', [DosenController::class, 'store'])->name('admin.dosen.store');
Route::post('/admin/kelas/store', [KelasController::class, 'store'])->name('admin.kelas.store');
Route::post('/admin/mahasiswa/store', [MahasiswaController::class, 'store'])->name('admin.mahasiswa.store');

// fitur store manual untuk data dosen, kelas, dan mahasiswa sudah dibuat di controller masing-masing. Pastikan route ini sesuai dengan method yang ada di controller dan form action di view.
Route::post('/dosen/store', [DosenController::class, 'store'])->name('dosen.store');
Route::post('/kelas/store', [KelasController::class, 'store'])->name('kelas.store');
Route::post('/mahasiswa/store', [MahasiswaController::class, 'store'])->name('mahasiswa.store');

// Kelompok halaman yang butuh login
Route::middleware(['auth'])->group(function () {
    
    // Khusus Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', function () { return view('admin.dashboard'); })->name('admin.dashboard');
        
        // Tambahkan 3 baris ini:
        Route::get('/admin/dosen', function () { return view('admin.dosen'); })->name('admin.dosen');
        Route::get('/admin/kelas', function () { return view('admin.kelas'); })->name('admin.kelas');
        Route::get('/admin/mahasiswa', function () { return view('admin.mahasiswa'); })->name('admin.mahasiswa');
    });

    // Khusus Dosen
    Route::middleware(['role:dosen'])->group(function () {
        Route::get('/dosen/dashboard', function () { return view('dosen.dashboard'); });
    });

    // Khusus Mahasiswa
    Route::middleware(['role:mahasiswa'])->group(function () {
        Route::get('/mahasiswa/dashboard', function () { return view('mahasiswa.dashboard'); });
    });
});
