<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KaprodiDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // 1. Cari data dosen yang sedang login untuk mendeteksi prodi yang dipimpinnya
        $kaprodi = DB::table('dosens')->where('user_id', $user->id)->first();

        // 2. Monitoring Kelas: Ambil semua kelas di prodinya pada tahun ajaran aktif saat ini
        $monitoringKelas = DB::table('classrooms')
            ->join('courses', 'classrooms.course_id', '=', 'courses.id')
            ->join('kurikulums', 'courses.kurikulum_id', '=', 'kurikulums.id')
            ->join('dosens', 'classrooms.dosen_id', '=', 'dosens.id')
            ->where('kurikulums.prodi_id', $kaprodi->prodi_id) // Mengunci internal prodi saja
            ->where('classrooms.tahun_ajaran_id', session('tahun_ajaran_id'))
            ->select('classrooms.*', 'courses.nama_mk', 'courses.kode_mk', 'dosens.nama as nama_dosen')
            ->get();

        return view('kaprodi.dashboard', compact('kaprodi', 'monitoringKelas'));
    }
}