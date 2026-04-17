<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\DosenDashboardController;


// Route untuk landing page
Route::get('/', function () {
    return view('landing'); 
});

Route::get('/login', function () {
    return view('auth.login'); // Kita akan buat file resources/views/auth/login.blade.php setelah ini
});

// Route untuk halaman login dan logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//ADMIN
// Route untuk tambah data dosen, kelas, dan mahasiswa

Route::post('/admin/dosen/store', [DosenController::class, 'store'])->name('admin.dosen.store');
Route::post('/admin/kelas/store', [KelasController::class, 'store'])->name('admin.kelas.store');
Route::post('/admin/mahasiswa/store', [MahasiswaController::class, 'store'])->name('admin.mahasiswa.store');

// fitur store manual untuk data dosen, kelas, dan mahasiswa sudah dibuat di controller masing-masing. Pastikan route ini sesuai dengan method yang ada di controller dan form action di view.
Route::post('/dosen/store', [DosenController::class, 'store'])->name('dosen.store');
Route::post('/kelas/store', [KelasController::class, 'store'])->name('kelas.store');
Route::post('/mahasiswa/store', [MahasiswaController::class, 'store'])->name('mahasiswa.store');

// DOSEN
// Route untuk memproses upload materi
Route::post('/dosen/materi/store', [MaterialController::class, 'store'])->name('materi.store');

// 1. Rute untuk halaman awal Dasbor Dosen (menampilkan daftar kelas)
Route::get('/dosen/dashboard', [DosenDashboardController::class, 'index'])->name('dosen.dashboard');

// 2. Rute untuk masuk ke spesifik kelas berdasarkan ID-nya
Route::get('/dosen/kelas/{id}', [DosenDashboardController::class, 'show'])->name('dosen.kelas.show');

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
    Route::get('/dosen/dashboard', [DosenDashboardController::class, 'index'])
    ->middleware(['auth', 'role:dosen'])
    ->name('dosen.dashboard');
    });

    Route::get('/dosen/kelas/{id}', [DosenDashboardController::class, 'show'])
    ->middleware(['auth', 'role:dosen'])
    ->name('dosen.kelas.show');

    // Khusus Mahasiswa
    Route::middleware(['role:mahasiswa'])->group(function () {
        Route::get('/mahasiswa/dashboard', function () { return view('mahasiswa.dashboard'); });
    });

