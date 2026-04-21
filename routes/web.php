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
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () { // auth dan role admin
        Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard'); // Rute untuk melihat dashboard admin
        Route::get('/dosen', function () { return view('admin.dosen'); })->name('dosen'); // Rute untuk melihat daftar dosen
        Route::get('/kelas', function () { return view('admin.kelas'); })->name('kelas'); // Rute untuk melihat daftar kelas
        Route::get('/mahasiswa', function () { return view('admin.mahasiswa'); })->name('mahasiswa'); // Rute untuk melihat daftar mahasiswa
        
        Route::post('/dosen/store', [DosenController::class, 'store'])->name('dosen.store'); // Rute untuk menyimpan data dosen baru
        Route::post('/kelas/store', [KelasController::class, 'store'])->name('kelas.store'); // Rute untuk menyimpan data kelas baru
        Route::post('/mahasiswa/store', [MahasiswaController::class, 'store'])->name('mahasiswa.store'); // Rute untuk menyimpan data mahasiswa baru
    });

    // Group Khusus Dosen
    Route::middleware(['role:dosen'])->prefix('dosen')->name('dosen.')->group(function () { // auth dan role dosen
        Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard'); // Rute untuk melihat dashboard dosen
        Route::get('/kelas/{id}', [DosenDashboardController::class, 'show'])->name('kelas.show'); // Rute untuk melihat detail kelas
        Route::post('/materi/store', [MaterialController::class, 'store'])->name('materi.store'); // Rute untuk menyimpan materi baru
        Route::post('/announcement', [DosenDashboardController::class, 'storeAnnouncement'])->name('announcement.store'); // Rute untuk menyimpan pengumuman baru
        Route::post('/assignment/store', [DosenDashboardController::class, 'storeAssignment'])->name('assignment.store'); // Rute untuk menyimpan tugas baru
        Route::get('/jadwal', [DosenDashboardController::class, 'jadwal'])->name('jadwal'); // Rute untuk melihat jadwal mengajar
        Route::get('/arsip-nilai', [DosenDashboardController::class, 'arsipNilai'])->name('arsip.nilai'); // Rute untuk melihat arsip nilai mahasiswa
        Route::get('/export-nilai/{course_id}', [DosenDashboardController::class, 'exportExcel'])->name('export.excel'); // Rute untuk mengekspor nilai mahasiswa ke Excel

        // Ini rute buat proses simpan kelas (POST)
        Route::post('/dashboard', [DosenDashboardController::class, 'storeKelas'])->name('kelas.store');
    });

    // Group Khusus Mahasiswa (INI YANG KITA BUAT)
    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () { // auth dan role mahasiswa
        Route::get('/dashboard', [MahasiswaController::class, 'index'])->name('dashboard'); // Rute untuk melihat dashboard mahasiswa
    });

});