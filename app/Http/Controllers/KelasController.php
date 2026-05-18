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
    // ==========================================
    // 1. TAMPILKAN HALAMAN
    // ==========================================
    public function index(Request $request)
    {
        $query = Classroom::with(['dosen', 'course', 'mahasiswas']);
        
        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_kelas', 'like', "%$search%")
                  ->orWhereHas('course', function($q) use ($search) { 
                      $q->where('nama_mk', 'like', "%$search%"); 
                  });
        }

        $classrooms = $query->latest()->get();
        $dosens = Dosen::all();
        $prodis = \App\Models\Prodi::all();
        $courses = Course::latest()->get(); // Diurutkan dari yang terbaru
        // Kelompokkan mahasiswa per kelas untuk checkbox di modal
        $mahasiswasGrouped = Mahasiswa::select('kelas', DB::raw('count(*) as total'))->groupBy('kelas')->get();

        return view('admin.kelas', compact('classrooms', 'dosens', 'prodis', 'courses', 'mahasiswasGrouped'));
    }

   // ==========================================
    // 3. RAKIT KELAS MANUAL (VERSI FIX GABUNGAN)
    // ==========================================
    public function store(Request $request)
    {
        // 1. Validasi data esensial LMS Online (Bebas hari & jam fisik)
        $request->validate([
            'course_id' => 'required',
            'dosen_id' => 'required',
            'nama_kelas' => 'required|string|max:255',
            'tahun_akademik' => 'required|string',
        ]);

        try {
            // Gunakan Database Transaction agar jika salah satu proses gagal, data tidak rusak/setengah masuk
            \Illuminate\Support\Facades\DB::beginTransaction();

            // 2. Siapkan array data gabungan dengan menyisipkan nilai null pada kolom fisik lama
            $dataKelas = array_merge($request->except('target_kelas'), [
                'status' => 'aktif',
                'hari' => null,
                'jam_mulai' => null,
                'jam_selesai' => null,
            ]);

            // A. Buat Kelas LMS Baru
            $kelas = \App\Models\Classroom::create($dataKelas);

            // B. Hubungkan Dosen dengan Matkul yang dipilih (Agar masuk ke daftar matkul di Profil Dosen)
            $dosen = \App\Models\Dosen::find($request->dosen_id);
            if ($dosen) {
                // syncWithoutDetaching mencegah relasi matkul lama milik dosen terhapus
                $dosen->courses()->syncWithoutDetaching([$request->course_id]);
            }

            // C. OTOMATISASI ROMBEL: Sedot Mahasiswa berdasarkan Rombongan kelas yang dicentang
            if ($request->has('target_kelas')) {
                $mahasiswaIds = \App\Models\Mahasiswa::whereIn('kelas', $request->target_kelas)->pluck('id');
                $kelas->mahasiswas()->attach($mahasiswaIds);
            }

            // Jika semua langkah A, B, C sukses tanpa interupsi, kunci data ke database
            \Illuminate\Support\Facades\DB::commit();
            
            return redirect()->back()->with('success', 'Kelas LMS Manual berhasil dirakit dan rombel otomatis disedot!');

        } catch (\Exception $e) {
            // Jika ada satu saja yang gagal/error, batalkan semua proses di atas agar database tetap bersih
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->with('error', 'Gagal buat kelas manual: ' . $e->getMessage());
        }
    }

    // ==========================================
    // 4. UPDATE DATA KELAS
    // ==========================================
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

            // Pastikan relasi dosen dan matkulnya juga tetap aman jika diubah
            $dosen = Dosen::find($request->dosen_id);
            if ($dosen) {
                $dosen->courses()->syncWithoutDetaching([$request->course_id]);
            }

            return redirect()->back()->with('success', 'Data kelas berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update kelas: ' . $e->getMessage());
        }
    }

    // ==========================================
    // 5. HAPUS KELAS
    // ==========================================
    public function destroy($id)
    {
        Classroom::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Kelas beserta seluruh pesertanya berhasil dihapus.');
    }

    // ==========================================
    // UPDATE MATA KULIAH
    // ==========================================
    public function updateCourse(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $request->validate([
            'kode_mk'  => 'required|unique:courses,kode_mk,' . $id,
            'nama_mk'  => 'required',
            'sks'      => 'required|numeric',
            'prodi_id'    => 'required',
            'semester' => 'required|numeric',
        ]);

        $course->update($request->all());
        return redirect()->back()->with('success', 'Mata kuliah berhasil diperbarui!');
    }

    // ==========================================
    // HAPUS MATA KULIAH
    // ==========================================
    public function destroyCourse($id)
    {
        try {
            $course = Course::findOrFail($id);
            // Cek apakah matkul ini sudah dipakai di kelas manapun
            if ($course->classrooms()->count() > 0) {
                return redirect()->back()->with('error', 'Matkul tidak bisa dihapus karena sudah memiliki kelas aktif!');
            }
            
            $course->delete();
            return redirect()->back()->with('success', 'Mata kuliah berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus matkul: ' . $e->getMessage());
        }
    }
}