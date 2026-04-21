<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course; 
use App\Models\Material; 
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Schedule;
use App\Exports\RekapNilaiExport;
use Maatwebsite\Excel\Facades\Excel;


class DosenDashboardController extends Controller
{
    // Fungsi untuk menampilkan halaman depan Dasbor Dosen
    public function index()
    {
        
        $courses = Course::where('teacher_id', auth()->id())->latest()->get(); 
        
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
        $submissions = \App\Models\Submission::whereIn('assignment_id', $assignments->pluck('id'))
                    ->get()
                    ->groupBy(['mahasiswa_id', 'assignment_id']); // Ambil data pengumpulan tugas dan kelompokkan berdasarkan mahasiswa dan tugas

        return view('dosen.kelas_detail', compact('kelas', 'materials', 'announcements', 'assignments', 'students','submissions', 'activeTab'));
        
    }

    public function storeKelas(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'course_code' => 'required|unique:courses',
            'course_name' => 'required',
        ]);

        // 2. Simpan ke database
        Course::create([
            'course_code' => $request->course_code,
            'course_name' => $request->course_name,
            'teacher_id'  => auth()->id(), // Pakai ID dosen yang lagi login
        ]);

        // 3. Balik lagi ke dashboard dengan pesan sukses
        return redirect()->route('dosen.dashboard')->with('success', 'Kelas baru berhasil dibuat!');
    }

    // Fungsi untuk membuat pengumuman baru
    public function storeAnnouncement(Request $request) 
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
            'file_tugas' => 'nullable|file|mimes:pdf,doc,docx,mp4|max:20480',
        ]);

        Assignment::create([
            'course_id' => $request->course_id,
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
        ]);

        return back()->with('success', 'Tugas berhasil dibuat!');
    }
    // Fungsi untuk menampilkan jadwal mengajar dosen
    public function jadwal()
    {
        // Dokumentasi: Mengambil jadwal hanya untuk kelas yang diajar dosen yang login
        $jadwal = Schedule::whereHas('course', function($query) {
            $query->where('teacher_id', auth()->id());
        })->with('course')->orderBy('start_time')->get();

        return view('dosen.jadwal', compact('jadwal'));
    }
    // Fungsi untuk menampilkan arsip nilai mahasiswa
    public function arsipNilai()
    {
        /**
         * Dokumentasi: Mengambil semua mata kuliah yang diampu dosen ini 
         * beserta jumlah mahasiswa dan rata-rata nilainya.
         */
        $courses = Course::where('teacher_id', auth()->id())
                    ->with(['students', 'assignments.submissions'])
                    ->get();

        return view('dosen.arsip_nilai', compact('courses'));
    }
    // Fungsi untuk mengekspor nilai mahasiswa ke Excel
    public function exportExcel($course_id) 
    {
        return Excel::download(new RekapNilaiExport($course_id), 'rekap-nilai.xlsx');
    }

    
}