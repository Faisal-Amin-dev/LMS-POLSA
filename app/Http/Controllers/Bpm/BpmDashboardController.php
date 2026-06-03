<?php

namespace App\Http\Controllers\Bpm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BpmDashboardController extends Controller
{
    public function index()
    {
        // TANGKAPAN REVISI DOSEN: BPM memantau audit mutu global seluruh prodi kampus Polsa
        $auditGlobal = DB::table('classrooms')
            ->join('courses', 'classrooms.course_id', '=', 'courses.id')
            ->join('dosens', 'classrooms.dosen_id', '=', 'dosens.id')
            ->join('tahun_ajarans', 'classrooms.tahun_ajaran_id', '=', 'tahun_ajarans.id')
            ->where('classrooms.tahun_ajaran_id', session('tahun_ajaran_id'))
            ->select('classrooms.*', 'courses.nama_mk', 'dosens.nama as nama_dosen')
            ->orderBy('classrooms.created_at', 'desc')
            ->get();

        // Hitung statistik kilat untuk widget dashboard BPM
        $totalKelas = $auditGlobal->count();
        $sudahUploadRps = $auditGlobal->whereNotNull('file_rps')->count();
        $belumUploadRps = $totalKelas - $sudahUploadRps;

        return view('bpm.dashboard', compact('auditGlobal', 'totalKelas', 'sudahUploadRps', 'belumUploadRps'));
    }
}