<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course; 
use App\Models\Material; 
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Schedule;
use App\Exports\ArsipNilaiExport;
use Maatwebsite\Excel\Facades\Excel;


class DosenDashboardController extends Controller
{
    // Fungsi untuk menampilkan halaman depan Dasbor Dosen
    public function index()
    {
        $user = auth()->user();
        // Ambil data dosen yang sedang login
        $dosen = \App\Models\Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return redirect('/')->with('error', 'Data dosen tidak ditemukan.');
        }

        // CARA BENAR: Ambil matkul lewat relasi (Pivot Table course_dosen)
        $courses = $dosen->courses; 

        // Ambil kelas yang diajar dosen ini
        $classrooms = \App\Models\Classroom::where('dosen_id', $dosen->id)->with('course')->get();

        return view('dosen.dashboard', compact('dosen', 'courses', 'classrooms'));
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
        $request->validate([
            'course_id' => 'required',
            'nama_kelas' => 'required',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'tahun_akademik' => 'required',
        ]);

        $dosen = \App\Models\Dosen::where('user_id', auth()->id())->first();

        \App\Models\Classroom::create([
            'course_id' => $request->course_id,
            'dosen_id' => $dosen->id,
            'nama_kelas' => $request->nama_kelas,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'tahun_akademik' => $request->tahun_akademik,
        ]);

        return redirect()->back()->with('success', 'Kelas berhasil dibuat!');
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
        $user = auth()->user();
        $dosen = \App\Models\Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan.');
        }

        // Ubah nama variabel dari $schedules menjadi $jadwal
        $jadwal = \App\Models\Classroom::with('course')
            ->where('dosen_id', $dosen->id)
            ->orderBy('hari', 'asc') 
            ->orderBy('jam_mulai', 'asc')
            ->get();

        // Pastikan di sini namanya 'jadwal' (tanpa tanda $)
        return view('dosen.jadwal', compact('jadwal'));
    }
    // Fungsi untuk menampilkan arsip nilai mahasiswa
    public function arsipNilai(Request $request)
    {
        $user = auth()->user();
        $dosen = \App\Models\Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan.');
        }

        // 1. Data utama untuk tampilan & export
        $classrooms = \App\Models\Classroom::where('dosen_id', $dosen->id)
            ->with('course')
            ->get();

        $courses = $dosen->courses;

        // 2. LOGIKA EXCEL: Jika ada request 'export', jalankan fungsi download
        if ($request->get('type') == 'excel') {
            // Ganti 'ArsipNilaiExport' dengan nama class export yang pernah Pakdhe buat
            return Excel::download(new ArsipNilaiExport($classrooms), 'Arsip_Nilai_' . $dosen->nama . '.xlsx');
        }

        // 3. Jika tidak klik export, tampilkan halaman seperti biasa
        return view('dosen.arsip_nilai', compact('classrooms', 'courses'));
    }

    
}