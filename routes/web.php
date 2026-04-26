<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ApiSyncController; // Tambahkan ini
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\DosenDashboardController;
use App\Http\Controllers\MahasiswaDashboardController;
use Illuminate\Support\Facades\Route;

// ---------------------------------------------------------
// 1. GUEST ROUTES (Bisa diakses tanpa login)
// ---------------------------------------------------------
Route::get('/', function () { return view('landing'); });
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Tes (Untuk pastikan Laravel hidup)
Route::get('/tes-nyawa', function() { return "<h1>HALO PAKDHE, SAYA HIDUP!</h1>"; });

// ---------------------------------------------------------
// 2. AUTH ROUTES (Harus login dulu)
// ---------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    // ==========================================
    // GROUP KHUSUS ADMIN (Manajemen Data)
    // ==========================================
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        
        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Manajemen Dosen (CRUD Lengkap + Sync API)
        Route::get('/dosen', [DosenController::class, 'index'])->name('dosen'); 
        Route::post('/dosen/store', [DosenController::class, 'store'])->name('dosen.store'); 
        Route::put('/dosen/{id}', [DosenController::class, 'update'])->name('dosen.update');
        Route::delete('/dosen/{id}', [DosenController::class, 'destroy'])->name('dosen.destroy');
        Route::post('/dosen/sync', [ApiSyncController::class, 'syncDosen'])->name('dosen.sync');

        // Manajemen Mahasiswa (CRUD Lengkap + Sync API)
        Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa'); 
        Route::post('/mahasiswa/store', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
        Route::put('/mahasiswa/{id}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
        Route::delete('/mahasiswa/{id}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');
        Route::post('/mahasiswa/sync', [ApiSyncController::class, 'syncMahasiswa'])->name('mahasiswa.sync');

        // Manajemen Kelas
        Route::get('/kelas', [KelasController::class, 'index'])->name('kelas');
        Route::post('/kelas/store', [KelasController::class, 'store'])->name('kelas.store');
        Route::post('/kelas/sync', [ApiSyncController::class, 'syncKelas'])->name('kelas.sync');

        // Matkul dan rute hapus kelas
        Route::post('/course/store', [KelasController::class, 'storeCourse'])->name('course.store');
        Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');
    });

    // ==========================================
    // GROUP KHUSUS DOSEN (Aktivitas Mengajar)
    // ==========================================
    Route::middleware(['role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');
        Route::get('/kelas/{id}', [DosenDashboardController::class, 'show'])->name('kelas.show');
        Route::get('/jadwal', [DosenDashboardController::class, 'jadwal'])->name('jadwal');
        Route::get('/arsip-nilai', [DosenDashboardController::class, 'arsipNilai'])->name('arsip.nilai');
        Route::get('/export-nilai/{course_id}', [DosenDashboardController::class, 'exportExcel'])->name('export.excel');
        
        // Simpan konten pembelajaran
        Route::post('/materi/store', [MaterialController::class, 'store'])->name('materi.store');
        Route::post('/announcement', [DosenDashboardController::class, 'storeAnnouncement'])->name('announcement.store');
        Route::post('/assignment/store', [DosenDashboardController::class, 'storeAssignment'])->name('assignment.store');
    });

    // ==========================================
    // GROUP KHUSUS MAHASISWA (Aktivitas Belajar)
    // ==========================================
    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');
    });

});