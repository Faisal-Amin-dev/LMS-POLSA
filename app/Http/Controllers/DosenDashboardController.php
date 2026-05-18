<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course; 
use App\Models\Material; 
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Dosen;
use App\Models\Submission;
use App\Exports\ArsipNilaiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class DosenDashboardController extends Controller
{
    // 1. BERANDA DOSEN (Mirip Google Classroom Home)
    public function index()
    {
        $user = auth()->user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return redirect('/')->with('error', 'Data dosen tidak ditemukan.');
        }

        // KONEKSI KE ADMIN: Hanya mengambil kelas yang statusnya 'aktif' (Belum diarsip Admin)
        $classrooms = Classroom::where('dosen_id', $dosen->id)
                                ->where('status', 'aktif')
                                ->with('course')
                                ->latest()
                                ->get();

        // Tetap kirim data courses untuk modal select tambah kelas manual
        $courses = $dosen->courses; 

        return view('dosen.dashboard', compact('dosen', 'courses', 'classrooms'));
    }

    // 2. MASUK RUANG KELAS DETAIL
    public function show($id)
    {
        // Ambil data dari Classroom, bukan Course abstract lagi
        $kelas = Classroom::with('course')->findOrFail($id); 
        $activeTab = request()->query('tab', 'beranda'); 
        
        // Mengambil konten berdasarkan Classroom ID
        $materials = Material::where('classroom_id', $id)->latest()->get();
        $announcements = Announcement::where('classroom_id', $id)->latest()->get(); 
        $assignments = Assignment::where('classroom_id', $id)->latest()->get(); 
        
        // KONEKSI KE ADMIN: Mengambil rombongan mahasiswa yang disedot oleh Admin ke kelas ini
        $students = $kelas->mahasiswas; 
        
        $submissions = Submission::whereIn('assignment_id', $assignments->pluck('id'))
                    ->get()
                    ->groupBy(['mahasiswa_id', 'assignment_id']); 

        return view('dosen.kelas_detail', compact('kelas', 'materials', 'announcements', 'assignments', 'students', 'submissions', 'activeTab'));
    }

    // 3. BUAT KELAS MANUAL (Sudah dibersihkan dari Hari, Jam, Ruangan)
    public function storeKelas(Request $request)
    {
        // Validasi hanya menyisakan data esensial LMS Online
        $request->validate([
            'course_id' => 'required',
            'nama_kelas' => 'required|string|max:255',
            'tahun_akademik' => 'required|string',
        ]);

        $dosen = Dosen::where('user_id', auth()->id())->first();

        Classroom::create([
            'course_id' => $request->course_id,
            'dosen_id' => $dosen->id,
            'nama_kelas' => $request->nama_kelas,
            'tahun_akademik' => $request->tahun_akademik,
            'status' => 'aktif', // Otomatis aktif saat dibuat
        ]);

        return redirect()->back()->with('success', 'Kelas LMS baru berhasil dirakit!');
    }

    // 4. MENGUBAH JADWAL MENJADI AGENDA TUGAS (Fitur Google Classroom "To Review")
    public function jadwal()
    {
        $user = auth()->user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan.');
        }

        // Ambil semua ID kelas aktif milik dosen ini
        $classroomIds = Classroom::where('dosen_id', $dosen->id)
                                 ->where('status', 'aktif')
                                 ->pluck('id');

        // Mengambil daftar tugas dari kelas-kelas tersebut yang diurutkan berdasarkan deadline terdekat
        $jadwal = Assignment::whereIn('classroom_id', $classroomIds)
                            ->with('classroom.course')
                            ->orderBy('deadline', 'asc')
                            ->get();

        // Catatan: Variabel tetap dikirim sebagai $jadwal agar Pakdhe tidak perlu merubah compact di web.php
        return view('dosen.jadwal', compact('jadwal'));
    }

    // 5. ARSIP NILAI
    public function arsipNilai(Request $request)
    {
        $user = auth()->user();
        $dosen = \App\Models\Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan.');
        }

        // Ambil seluruh kelas yang diampu beserta data matkul, tugas, dan mahasiswanya
        $classrooms = \App\Models\Classroom::where('dosen_id', $dosen->id)
            ->with(['course', 'assignments', 'mahasiswas'])
            ->get();

        // Kumpulkan semua ID tugas dari kelas-kelas dosen ini
        $assignmentIds = \App\Models\Assignment::whereIn('classroom_id', $classrooms->pluck('id'))->pluck('id');

        // Ambil semua data pengumpulan tugas lalu kelompokkan berdasarkan mahasiswa dan tugas
        $submissions = \App\Models\Submission::whereIn('assignment_id', $assignmentIds)
            ->get()
            ->groupBy(['mahasiswa_id', 'assignment_id']);

        // Logika Excel Export
        if ($request->get('type') == 'excel') {
            return Excel::download(new ArsipNilaiExport($classrooms), 'Arsip_Nilai_' . $dosen->nama . '.xlsx');
        }

        return view('dosen.arsip_nilai', compact('classrooms', 'submissions'));
    }

    // 6. POSTING ANNOUNCEMENT
    public function storeAnnouncement(Request $request) 
    {
        $request->validate([
            'classroom_id' => 'required',
            'content' => 'required|string',
        ]);

        Announcement::create([
            'classroom_id' => $request->classroom_id,
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Pengumuman berhasil disebarkan ke kelas!');
    }

    // 7. POSTING TUGAS BARU
    public function storeAssignment(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date',
            'file_tugas' => 'nullable|file|mimes:pdf,doc,docx,mp4|max:20480',
        ]);

        Assignment::create([
            'classroom_id' => $request->classroom_id,
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
        ]);

        return back()->with('success', 'Tugas praktikum berhasil diterbitkan!');
    }

    // Fungsi menampilkan ruang kelas masa lalu (Arsip Semester)
    public function kelasArsip()
    {
        $user = auth()->user();
        $dosen = \App\Models\Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return redirect('/')->with('error', 'Data dosen tidak ditemukan.');
        }

        // Hanya menyaring kelas milik dosen tersebut yang statusnya sudah 'arsip'
        $classrooms = \App\Models\Classroom::where('dosen_id', $dosen->id)
                                ->where('status', 'arsip')
                                ->with('course')
                                ->latest()
                                ->get();

        return view('dosen.kelas_arsip', compact('dosen', 'classrooms'));
    }
}