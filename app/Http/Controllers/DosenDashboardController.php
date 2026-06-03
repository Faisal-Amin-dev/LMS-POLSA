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
   public function index()
    {
        $user = Auth::user();
        $dosen = \DB::table('dosens')->where('user_id', $user->id)->first();

        // 1. Ambil kelas aktif yang diampu dosen itu sendiri
        $classrooms = \App\Models\Classroom::where('dosen_id', $dosen->id)
            ->where('tahun_ajaran_id', session('tahun_ajaran_id'))
            ->where('status', 'aktif')
            ->with('course')
            ->orderBy('created_at', 'desc')
            ->get();

        $courses = \App\Models\Course::latest()->get();

        // 2. FITUR KAPRODI: Monitor prodi sendiri (Menggunakan leftJoin agar data aman tampil)
        $monitoringProdi = collect();
        if ($dosen && $dosen->jabatan == 'Kaprodi') {
            $monitoringProdi = \DB::table('classrooms')
                ->leftJoin('courses', 'classrooms.course_id', '=', 'courses.id')
                ->leftJoin('kurikulums', 'courses.kurikulum_id', '=', 'kurikulums.id')
                ->leftJoin('dosens', 'classrooms.dosen_id', '=', 'dosens.id')
                ->where('kurikulums.prodi_id', $dosen->prodi_id)
                ->where('classrooms.tahun_ajaran_id', session('tahun_ajaran_id'))
                ->select('classrooms.*', 'courses.nama_mk', 'courses.kode_mk', 'dosens.nama as nama_dosen')
                ->get();
        }

        // 3. FITUR BPMI: Audit Global Seluruh Kampus Polsa (Menggunakan leftJoin agar kelas lama ikut muncul)
        $auditBpmGlobal = collect();
        $statBpm = ['total' => 0, 'sudah' => 0, 'belum' => 0];
        
        if ($dosen && $dosen->jabatan == 'BPM') {
            $auditBpmGlobal = \DB::table('classrooms')
                ->leftJoin('courses', 'classrooms.course_id', '=', 'courses.id')
                ->leftJoin('dosens', 'classrooms.dosen_id', '=', 'dosens.id')
                ->leftJoin('kurikulums', 'courses.kurikulum_id', '=', 'kurikulums.id')
                ->leftJoin('prodis', 'kurikulums.prodi_id', '=', 'prodis.id')
                ->where('classrooms.tahun_ajaran_id', session('tahun_ajaran_id'))
                ->select('classrooms.*', 'courses.nama_mk', 'courses.kode_mk', 'dosens.nama as nama_dosen', 'prodis.nama_prodi')
                ->orderBy('prodis.nama_prodi', 'asc')
                ->get();

            // Hitung kalkulasi angka widget statistik
            $statBpm['total'] = $auditBpmGlobal->count();
            $statBpm['sudah'] = $auditBpmGlobal->whereNotNull('file_rps')->count();
            $statBpm['belum'] = $statBpm['total'] - $statBpm['sudah'];
        }

        return view('dosen.dashboard', compact('dosen', 'classrooms', 'courses', 'monitoringProdi', 'auditBpmGlobal', 'statBpm'));
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