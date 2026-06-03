<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\Dosen;
use App\Models\Course;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    // ========================================================
    // 1. TAMPILKAN HALAMAN UTAMA (TERKUNCI TAHUN AJARAN AKTIF)
    // ========================================================
    public function index(Request $request)
    {
        // REVISI DOSEN: Mengunci daftar kelas hanya pada tahun ajaran aktif agar tidak numpuk histori
        $query = Classroom::with(['dosen', 'course', 'mahasiswas'])
            ->where('tahun_ajaran_id', session('tahun_ajaran_id'));
        
        // Pencarian data kelas
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($mainQuery) use ($search) {
                $mainQuery->where('nama_kelas', 'like', "%$search%")
                          ->orWhereHas('course', function($q) use ($search) { 
                              $q->where('nama_mk', 'like', "%$search%"); 
                          });
            });
        }

        $classrooms = $query->latest()->get();
        $dosens = Dosen::all();
        $prodis = \App\Models\Prodi::all();
        
        // Menampilkan daftar mata kuliah yang terikat kurikulum prodi
        $courses = Course::latest()->get(); 
        
        // Kelompokkan mahasiswa per kelas untuk checkbox rombel di modal
        $mahasiswasGrouped = Mahasiswa::select('kelas', DB::raw('count(*) as total'))
            ->groupBy('kelas')
            ->get();

        return view('admin.kelas', compact('classrooms', 'dosens', 'prodis', 'courses', 'mahasiswasGrouped'));
    }

    // ========================================================
    // 2. RAKIT KELAS PERKULIAHAN (CLASSROOM GENERATE)
    // ========================================================
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'dosen_id' => 'required',
            'nama_kelas' => 'required|string',
            'tahun_akademik' => 'required',
        ]);

        // KUNCI UTAMA: Ambil ID Sesi aktif yang sedang berjalan dari session middleware
        $tahunAjaranId = session('tahun_ajaran_id'); 

        // Proses simpan ke tabel classrooms
        // (Sesuaikan dengan query Eloquent/Query Builder asli bawaan proyek Pakdhe)
        \App\Models\Classroom::create([
            'course_id'       => $request->course_id,
            'dosen_id'        => $request->dosen_id,
            'nama_kelas'      => $request->nama_kelas,
            'tahun_akademik'  => $request->tahun_akademik, // Mengisi teks string "2025/2026"
            'tahun_ajaran_id' => $tahunAjaranId,          // <-- WAJIB DISUNTIKKAN AGAR MUNCUL DI MONITORING!
            'status'          => 'aktif',                 // Mengunci status agar langsung aktif
        ]);

        // Selesaikan juga proses sinkronisasi rombel mahasiswa target_kelas jika ada di bawahnya...

        return redirect()->back()->with('success', 'Kelas LMS baru berhasil dirakit dan dikunci pada Sesi Akademik Aktif!');
    }
    
    // ========================================================
    // 3. TAMBAH MATA KULIAH BARU (LANGKAH 4: RELASI KE KURIKULUM)
    // ========================================================
    public function storeCourse(Request $request)
    {
        $request->validate([
            'prodi_id' => 'required',
            'kode_mk' => 'required|string|max:50|unique:courses,kode_mk',
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8', // Validasi input semester dari form UI
        ]);

        // Cari Kurikulum Pokok yang sedang AKTIF di Prodi terpilih
        $kurikulumAktif = DB::table('kurikulums')
            ->where('prodi_id', $request->prodi_id)
            ->where('is_aktif', true)
            ->first();

        // Sistem Pengunci Keamanan Data Pokok jika kurikulum belum aktif
        if (!$kurikulumAktif) {
            return redirect()->back()->with('error', 'Gagal menambah Mata Kuliah! Kurikulum aktif untuk Program Studi ini belum diset oleh Admin/Kaprodi. Silakan aktifkan kurikulum terlebih dahulu di menu Data Kurikulum.');
        }

        // FIX NO MISTAKE: Menyuntikkan input 'semester' dan 'prodi' sisa arsitektur lama
        DB::table('courses')->insert([
            'kurikulum_id' => $kurikulumAktif->id,
            'kode_mk' => $request->kode_mk,
            'nama_mk' => $request->nama_mk,
            'sks' => $request->sks,
            'semester' => $request->semester, // <-- KUNCI SOLUSI: Data disuntikkan ke database di sini
            'prodi' => '-',                  // Mengamankan kolom prodi sisa database lama jika belum di-set nullable
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Mata Kuliah berhasil didaftarkan di bawah naungan ' . $kurikulumAktif->nama_kurikulum);
    }

    // ========================================================
    // 4. UPDATE DATA KELAS PERKULIAHAN
    // ========================================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'course_id' => 'required',
            'dosen_id' => 'required',
            'nama_kelas' => 'required',
            'tahun_akademik' => 'required',
        ]);

        try {
            $kelas = Classroom::findOrFail($id);
            $kelas->update($request->all());

            $dosen = Dosen::find($request->dosen_id);
            if ($dosen) {
                $dosen->courses()->syncWithoutDetaching([$request->course_id]);
            }

            return redirect()->back()->with('success', 'Data kelas berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update kelas: ' . $e->getMessage());
        }
    }

    // ========================================================
    // 5. HAPUS KELAS PERKULIAHAN
    // ========================================================
    public function destroy($id)
    {
        Classroom::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Kelas beserta seluruh peserta di dalamnya berhasil dihapus.');
    }

    // ========================================================
    // 6. UPDATE MATA KULIAH
    // ========================================================
    public function updateCourse(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $request->validate([
            'kode_mk'  => 'required|unique:courses,kode_mk,' . $id,
            'nama_mk'  => 'required',
            'sks'      => 'required|numeric',
            'prodi_id' => 'required',
        ]);

        $course->update($request->except('prodi_id'));
        return redirect()->back()->with('success', 'Mata kuliah berhasil diperbarui!');
    }

    // ========================================================
    // 7. HAPUS MATA KULIAH
    // ========================================================
    public function destroyCourse($id)
    {
        try {
            $course = Course::findOrFail($id);
            
            // Validasi Relasi: Mencegah error database cascading yang berantakan
            if ($course->classrooms()->count() > 0) {
                return redirect()->back()->with('error', 'Mata kuliah tidak bisa dihapus karena sudah memiliki kelas aktif di LMS!');
            }
            
            $course->delete();
            return redirect()->back()->with('success', 'Mata kuliah berhasil dihapus dari sistem.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus mata kuliah: ' . $e->getMessage());
        }
    }
}