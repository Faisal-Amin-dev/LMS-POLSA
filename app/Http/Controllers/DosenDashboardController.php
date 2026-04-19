<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course; 
use App\Models\Material; 
use App\Models\Announcement;
use App\Models\Assignment;


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
        $kelas = Course::findOrFail($id); // Ambil data kelas berdasarkan ID
        $activeTab = request()->query('tab', 'beranda'); // Tentukan tab aktif, default ke 'beranda'
        $materials = Material::where('course_id', $id)->latest()->get();
        $announcements = Announcement::where('course_id', $id)->latest()->get(); // Pengambilan pengumuman
        $assignments = Assignment::where('course_id', $id)->latest()->get(); // Ambil data tugas
        $students = $kelas->students; // Ambil data mahasiswa yang terdaftar di kelas ini

        return view('dosen.kelas_detail', compact('kelas', 'materials', 'announcements', 'assignments', 'students', 'activeTab'));
        
    }

    // Fungsi untuk membuat pengumuman baru
    public function storeAnnouncement(Request $request, $id)
    {
        $request->validate([
            'course_id' => 'required',
            'content' => 'required|string',
        ]);

        Announcement::create([
            'course_id' => $request->course_id,
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Pengumuman berhasil dibagikan!');
    }
    // Fungsi untuk membuat tugas baru
    public function storeAssignment(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date',
        ]);

        Assignment::create([
            'course_id' => $request->course_id,
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
        ]);

        return back()->with('success', 'Tugas berhasil dibuat!');
    }

    
}