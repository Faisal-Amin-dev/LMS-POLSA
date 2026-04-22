<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course; 

class KelasController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi (Ganti 'kelas' jadi 'courses')
        $request->validate([
            'course_code' => 'required|unique:courses,course_code', // Cek ke tabel courses kolom course_code
            'course_name' => 'required',
            'prodi' => 'required',
            'teacher_id' => 'required|exists:users,id', // Pastikan dosennya ada di tabel users
        ]);

        // 2. Simpan (Gunakan Model Course)
        Course::create([
            'course_code' => $request->course_code,
            'course_name' => $request->course_name,
            'prodi' => $request->prodi,
            'teacher_id'  => $request->teacher_id,
        ]);

        return redirect()->back()->with('success', 'Data Kelas berhasil ditambahkan!');
    }

    public function index()
    {
        // 1. Ambil semua kelas beserta data dosennya (relasi teacher)
        $courses = Course::with('teacher')->latest()->get();

        // 2. Ambil juga daftar dosen untuk pilihan di Modal Tambah Kelas
        $dosens = User::where('role', 'dosen')->get();

        // 3. Kirim keduanya ke view
        return view('admin.kelas', compact('courses', 'dosens'));
    }
}
