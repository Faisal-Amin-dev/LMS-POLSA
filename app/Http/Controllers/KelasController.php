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
        $courses = Course::latest()->get(); // Diurutkan dari yang terbaru
        // Kelompokkan mahasiswa per kelas untuk checkbox di modal
        $mahasiswasGrouped = Mahasiswa::select('kelas', DB::raw('count(*) as total'))->groupBy('kelas')->get();

        return view('admin.kelas', compact('classrooms', 'dosens', 'courses', 'mahasiswasGrouped'));
    }

    // ==========================================
    // 2. SIMPAN MATA KULIAH MANUAL
    // ==========================================
    public function storeCourse(Request $request)
    {
        $request->validate([
            'kode_mk'  => 'required|unique:courses,kode_mk',
            'nama_mk'  => 'required',
            'sks'      => 'required|numeric',
            'prodi'    => 'required',
            'semester' => 'required|numeric',
        ]);

        Course::create($request->all());
        return redirect()->back()->with('success', 'Mata Kuliah baru berhasil disimpan!');
    }

    // ==========================================
    // 3. RAKIT KELAS MANUAL
    // ==========================================
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required', 
            'dosen_id' => 'required', 
            'nama_kelas' => 'required',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'tahun_akademik' => 'required',
            'target_kelas' => 'nullable|array' // Rombongan mahasiswa (bisa dikosongkan)
        ]);

        try {
            DB::beginTransaction();

            // A. Buat Kelasnya (kecuali target_kelas karena bukan kolom di tabel classroom)
            $kelas = Classroom::create($request->except('target_kelas'));

            // B. Hubungkan Dosen dengan Matkul yang dipilih (Agar masuk ke Profil Dosen)
            $dosen = Dosen::find($request->dosen_id);
            if ($dosen) {
                // syncWithoutDetaching mencegah matkul lama terhapus
                $dosen->courses()->syncWithoutDetaching([$request->course_id]);
            }

            // C. Masukkan Mahasiswa berdasarkan Rombongan yang dicentang
            if ($request->has('target_kelas')) {
                $mahasiswaIds = Mahasiswa::whereIn('kelas', $request->target_kelas)->pluck('id');
                $kelas->mahasiswas()->attach($mahasiswaIds);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Kelas LMS Manual berhasil dirakit!');
        } catch (\Exception $e) {
            DB::rollBack();
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
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
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
            'prodi'    => 'required',
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