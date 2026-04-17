<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course; // Pastikan model ini dipanggil
use App\Models\Material; // Pastikan model ini dipanggil

class DosenDashboardController extends Controller
{
    // Fungsi untuk menampilkan halaman depan Dasbor Dosen
    public function index()
    {
        $courses = Course::all(); 
        return view('dosen.dashboard', compact('courses'));
    }

    // Fungsi untuk masuk ke dalam Ruang Kelas
    public function show($id)
    {
        $kelas = Course::findOrFail($id);
        $materials = Material::where('course_id', $id)->latest()->get();

        return view('dosen.kelas_detail', compact('kelas', 'materials'));
    }
}