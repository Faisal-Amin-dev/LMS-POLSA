<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;

class AkademikController extends Controller
{
    // Menampilkan halaman utama menu KRS & Akademik
    public function index()
    {
        // Mengambil tahun akademik yang saat ini masih memiliki kelas aktif
        $tahunAktif = Classroom::where('status', 'aktif')->distinct()->pluck('tahun_akademik')->first() ?? 'Belum ada kelas aktif';
        
        return view('admin.akademik', compact('tahunAktif'));
    }

    // Fungsi Eksekusi Ganti Tahun & Arsip Otomatis
    public function gantiTahunAkademik(Request $request)
    {
        // 1. Validasi input dari form admin
        $request->validate([
            'tahun_lama' => 'required',
            'tahun_baru' => 'required'
        ]);

        $tahunLama = $request->tahun_lama;
        $tahunBaru = $request->tahun_baru;

        // 2. Cari semua kelas di tahun ajaran lama yang statusnya masih 'aktif', lalu ubah jadi 'arsip'
        Classroom::where('tahun_akademik', $tahunLama)
                 ->where('status', 'aktif')
                 ->update(['status' => 'arsip']);

        // Tambahan: Pakdhe juga bisa otomatis membuatkan log perpindahan semester di sini jika diperlukan nanti

        return redirect()->back()->with('success', "Tahun akademik $tahunLama berhasil ditutup dan diarsip! Sekarang siap memasuki tahun ajaran $tahunBaru.");
    }
}