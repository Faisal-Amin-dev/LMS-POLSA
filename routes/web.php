<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ApiSyncController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\DosenDashboardController;
use App\Http\Controllers\MahasiswaDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\AkademikController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KurikulumController;

// 1. GUEST ROUTES
Route::get('/', function () { return view('landing'); });
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 2. AUTH ROUTES
Route::middleware(['auth'])->group(function () {
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // ==========================================
    // GROUP KHUSUS ADMIN (Manajemen Data Pokok)
    // ==========================================
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        
        // 1. Dashboard Utama Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // 2. Data Pokok: Program Studi (Prodi)
        Route::get('/prodi', [ProdiController::class, 'index'])->name('prodi.index');
        Route::post('/prodi/store', [ProdiController::class, 'store'])->name('prodi.store');
        Route::put('/prodi/{id}', [ProdiController::class, 'update'])->name('prodi.update');
        Route::delete('/prodi/{id}', [ProdiController::class, 'destroy'])->name('prodi.destroy');
        Route::post('/prodi/sync', [ProdiController::class, 'sync'])->name('prodi.sync');

        // 3. Data Pokok: Kurikulum (REVISI DOSEN)
        Route::get('/kurikulum', [KurikulumController::class, 'index'])->name('kurikulum.index');
        Route::post('/kurikulum/store', [KurikulumController::class, 'store'])->name('kurikulum.store');
        Route::put('/kurikulum/{id}', [KurikulumController::class, 'update'])->name('kurikulum.update');
        Route::put('/kurikulum/{id}/toggle-status', [KurikulumController::class, 'toggleStatus'])->name('kurikulum.toggle');
        Route::delete('/kurikulum/{id}', [KurikulumController::class, 'destroy'])->name('kurikulum.destroy');

        // 4. Data Pokok: Dosen & Jabatan (REVISI DOSEN)
        Route::get('/dosen', [DosenController::class, 'index'])->name('dosen');
        Route::post('/dosen/store', [DosenController::class, 'store'])->name('dosen.store');
        Route::put('/dosen/{id}', [DosenController::class, 'update'])->name('dosen.update');
        Route::delete('/dosen/{id}', [DosenController::class, 'destroy'])->name('dosen.destroy');
        Route::post('/dosen/sync', [ApiSyncController::class, 'syncDosen'])->name('dosen.sync');
        // Rute Khusus Set Jabatan Struktural Dosen (Dosen, Kaprodi, BPM)
        Route::get('/dosen/jabatan', [DosenController::class, 'indexJabatan'])->name('dosen.jabatan');
        Route::put('/dosen/jabatan/{id}', [DosenController::class, 'updateJabatan'])->name('dosen.updateJabatan');

        // 5. Data Pokok: Mahasiswa
        Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa');
        Route::post('/mahasiswa/store', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
        Route::put('/mahasiswa/{id}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
        Route::delete('/mahasiswa/{id}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');
        Route::post('/mahasiswa/sync', [ApiSyncController::class, 'syncMahasiswa'])->name('mahasiswa.sync');

        // 6. Transaksi Akademik: Mata Kuliah (Courses) & Manajemen Kelas
        Route::get('/kelas', [KelasController::class, 'index'])->name('kelas');
        Route::post('/kelas/store', [KelasController::class, 'store'])->name('kelas.store');
        Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');
        Route::post('/kelas/sync', [ApiSyncController::class, 'syncKelas'])->name('kelas.sync');
        
        // Rute Sub-Entitas Mata Kuliah di dalam Kelas
        Route::post('/course/store', [KelasController::class, 'storeCourse'])->name('course.store');
        Route::put('/course/{id}', [KelasController::class, 'updateCourse'])->name('course.update');
        Route::delete('/course/{id}', [KelasController::class, 'destroyCourse'])->name('course.destroy');

        // 7. Sesi Sistem: Kalender Akademik & Tahun Ajaran
        Route::get('/akademik', [AkademikController::class, 'index'])->name('akademik.index');
        Route::post('/akademik/ganti-tahun', [AkademikController::class, 'gantiTahunAkademik'])->name('akademik.gantiTahun');
        
    });

    // DOSEN
    Route::middleware(['role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');
        Route::get('/kelas/{id}', [DosenDashboardController::class, 'show'])->name('kelas.show');
        Route::get('/jadwal', [DosenDashboardController::class, 'jadwal'])->name('jadwal');
        Route::get('/arsip-nilai', [DosenDashboardController::class, 'arsipNilai'])->name('arsip.nilai');
        Route::get('/export-nilai/{course_id}', [DosenDashboardController::class, 'exportExcel'])->name('export.excel');
        Route::post('/materi/store', [MaterialController::class, 'store'])->name('materi.store');
        Route::post('/announcement', [DosenDashboardController::class, 'storeAnnouncement'])->name('announcement.store');
        Route::post('/assignment/store', [DosenDashboardController::class, 'storeAssignment'])->name('assignment.store');
        Route::post('/kelas/store', [DosenDashboardController::class, 'storeKelas'])->name('kelas.store');
        Route::get('/kelas-diarsip', [DosenDashboardController::class, 'kelasArsip'])->name('kelas.arsip');
    });

    // MAHASISWA
    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');
        Route::get('/agenda', [MahasiswaDashboardController::class, 'agenda'])->name('agenda');
        Route::get('/krs', [MahasiswaDashboardController::class, 'krs'])->name('krs');Route::get('/krs', [App\Http\Controllers\KrsController::class, 'index'])->name('krs.index');
        Route::post('/krs/kontrak', [App\Http\Controllers\KrsController::class, 'store'])->name('krs.store');
        Route::get('/kelas/{id}', [MahasiswaDashboardController::class, 'show'])->name('kelas.show');
        Route::post('/submission', [MahasiswaDashboardController::class, 'storeSubmission'])->name('submission.store');
    });

    // ========================================================
    // GROUP KHUSUS DASHBOARD MONITORING JABATAN STRUKTURAL
    // ========================================================

    // 1. Rute Khusus Peran Ketua Prodi (Kaprodi)
    Route::middleware(['auth', 'role:dosen'])->prefix('kaprodi')->name('kaprodi.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Kaprodi\KaprodiDashboardController::class, 'index'])->name('dashboard');
    });

    // 2. Rute Khusus Peran Badan Penjaminan Mutu (BPM)
    Route::middleware(['auth', 'role:dosen'])->prefix('bpm')->name('bpm.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Bpm\BpmDashboardController::class, 'index'])->name('dashboard');
    });
});