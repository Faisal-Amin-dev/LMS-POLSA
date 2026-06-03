<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Classroom;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Material;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MahasiswaDashboardController extends Controller
{
   // ==========================================
    // 1. BERANDA / DASHBOARD UTAMA MAHASISWA
    // ==========================================
    public function index()
    {
        $user = Auth::user();
        
        // Ambil data detail mahasiswa yang sedang login
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        // REVISI DOSEN: Ambil kelas perkuliahan (classrooms) yang HANYA berstatus aktif
        // DAN terkunci ketat pada tahun_ajaran_id berjalan saat ini (Mencegah histori campur aduk)
        $classrooms = $mahasiswa->classrooms()
            ->where('classrooms.status', 'aktif')
            ->where('classrooms.tahun_ajaran_id', session('tahun_ajaran_id')) // Kunci Sesi Aktif
            ->with(['course', 'dosen'])
            ->orderBy('classrooms.created_at', 'desc')
            ->get();

        return view('mahasiswa.dashboard', compact('mahasiswa', 'classrooms'));
    }

/// ==========================================
    // 2. DETAIL RUANG KELAS LMS (Stream, Materi, Tugas)
    // ==========================================
    public function show(Request $request, $id)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        // REVISI DOSEN: Pastikan kelas yang dibuka COCOK dengan tahun ajaran berjalan
        $kelas = $mahasiswa->classrooms()
            ->where('classrooms.id', $id)
            ->where('classrooms.tahun_ajaran_id', session('tahun_ajaran_id')) // Kunci Sesi Aktif
            ->with(['course', 'dosen'])
            ->firstOrFail(); // Jika tidak cocok atau kelas semester lalu, otomatis melempar error 404

        $activeTab = $request->get('tab', 'beranda');

        $announcements = Announcement::where('classroom_id', $id)->latest()->get();
        $materials = Material::where('classroom_id', $id)->latest()->get();
        $assignments = Assignment::where('classroom_id', $id)->orderBy('deadline', 'asc')->get();
        $students = $kelas->mahasiswas()->orderBy('nama', 'asc')->get();

        $mySubmissions = Submission::where('student_id', $mahasiswa->id)
            ->whereIn('assignment_id', $assignments->pluck('id'))
            ->get()
            ->keyBy('assignment_id'); 

        return view('mahasiswa.kelas_detail', compact('kelas', 'activeTab', 'announcements', 'materials', 'assignments', 'students', 'mySubmissions', 'mahasiswa'));
    }

    // 3. AGENDA TUGAS (To-Do List Deadline Mendatang)
    public function agenda()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        // Ambil ID semua kelas aktif milik mahasiswa ini
        $classroomIds = $mahasiswa->classrooms()->where('status', 'aktif')->pluck('classrooms.id');

        // Cari tugas dari kelas-kelas tersebut yang deadlinenya belum lewat
        $agenda = Assignment::whereIn('classroom_id', $classroomIds)
                    ->where('deadline', '>=', now())
                    ->with(['classroom.course'])
                    ->orderBy('deadline', 'asc')
                    ->get();

        return view('mahasiswa.agenda', compact('agenda'));
    }

    // 4. KELAS DIARSIP (Semester Lalu)
    public function kelasArsip()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        // Ambil kelas dengan status arsip
        $classrooms = $mahasiswa->classrooms()->where('status', 'arsip')->with(['course', 'dosen'])->latest()->get();

        return view('mahasiswa.kelas_arsip', compact('classrooms'));
    }

 // 5. FUNGSI UNGGAH/KIRIM JAWABAN TUGAS PRAKTIKUM
    public function storeSubmission(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'classroom_id' => 'required',
            'file_jawaban' => 'required|mimes:pdf,doc,docx,zip,rar,png,jpg,jpeg|max:20480',
        ]);

        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        // PERBAIKAN: Ubah mahasiswa_id menjadi student_id
        $existingSubmission = Submission::where('assignment_id', $request->assignment_id)
                                        ->where('student_id', $mahasiswa->id)
                                        ->first();

        if ($existingSubmission) {
            return redirect()->back()->with('error', 'Anda sudah mengumpulkan tugas ini sebelumnya.');
        }

        $filePath = null;
        if ($request->hasFile('file_jawaban')) {
            $file = $request->file('file_jawaban');
            $filename = $mahasiswa->nim . '_' . $request->assignment_id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('submissions', $filename, 'public');
        }

        // PERBAIKAN: Sesuaikan nama kolom dengan database bahasa Inggris
        Submission::create([
            'assignment_id' => $request->assignment_id,
            'student_id' => $mahasiswa->id,    // Sesuai DB Pakdhe
            'file_path' => $filePath,
            'submitted_at' => now(),           // Sesuai DB Pakdhe (bukan waktu_pengumpulan)
            'grade' => null,                   // Sesuai DB Pakdhe (bukan nilai)
            'status' => 'turned_in',           // Sesuai enum DB Pakdhe
        ]);

        return redirect()->route('mahasiswa.kelas.show', ['id' => $request->classroom_id, 'tab' => 'tugas'])
                         ->with('success', 'Kerja bagus! File jawaban tugas berhasil dikirim ke Dosen.');
    }

    // ==========================================
    // 6. KRS & INFO AKADEMIK (SISTEM PAKET)
    // ==========================================
    public function krs()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        // Sedot kelas aktif untuk dijadikan daftar paket matkul di KRS
        $classrooms = $mahasiswa->classrooms()->where('status', 'aktif')->with(['course', 'dosen'])->get();

        // Hitung total SKS otomatis dari paket
        $totalSKS = 0;
        foreach($classrooms as $k) {
            $totalSKS += $k->course->sks ?? 0;
        }

        return view('mahasiswa.krs', compact('mahasiswa', 'classrooms', 'totalSKS'));
    }
}