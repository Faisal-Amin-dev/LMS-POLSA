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
    public function index()
    {
        $classrooms = Classroom::with(['dosen', 'course', 'mahasiswas'])->latest()->get();
        $dosens = Dosen::all();
        $courses = Course::all();
        // Kelompokkan mahasiswa per kelas (TI 4A, dll) untuk checkbox
        $mahasiswasGrouped = Mahasiswa::select('kelas', DB::raw('count(*) as total'))->groupBy('kelas')->get();

        return view('admin.kelas', compact('classrooms', 'dosens', 'courses', 'mahasiswasGrouped'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required', 'dosen_id' => 'required', 'target_kelas' => 'required|array'
        ]);

        try {
            DB::beginTransaction();
            $kelas = Classroom::create($request->all());
            // Cari semua ID mahasiswa yang masuk rombongan kelas tersebut
            $mahasiswaIds = Mahasiswa::whereIn('kelas', $request->target_kelas)->pluck('id');
            $kelas->mahasiswas()->attach($mahasiswaIds);
            DB::commit();
            return redirect()->back()->with('success', 'Kelas LMS berhasil dibentuk!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal buat kelas: ' . $e->getMessage());
        }
    }

    public function storeCourse(Request $request)
    {
        $request->validate(['kode_matkul' => 'required|unique:courses,kode_matkul', 'nama_matkul' => 'required']);
        Course::create($request->all());
        return redirect()->back()->with('success', 'Mata Kuliah baru berhasil disimpan!');
    }

    public function destroy($id)
    {
        Classroom::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Kelas berhasil dihapus.');
    }
}