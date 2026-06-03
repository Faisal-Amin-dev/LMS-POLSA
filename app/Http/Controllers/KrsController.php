<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswa;
use App\Models\KrsPaket;

class KrsController extends Controller
{
    // 1. Tampilkan Halaman Pengambilan KRS Paket
    public function index()
    {
        $user = Auth::user();
        // Cari data detail mahasiswa yang sedang login beserta data prodinya
        $mahasiswa = DB::table('mahasiswa')->where('user_id', $user->id)->first();

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data profil mahasiswa belum lengkap.');
        }

        // TANGKAPAN REVISI DOSEN: Cari Kurikulum yang sedang AKTIF di prodi mahasiswa tersebut
        $kurikulumAktif = DB::table('kurikulums')
            ->where('prodi_id', $mahasiswa->prodi_id)
            ->where('is_aktif', true)
            ->first();

        $courses = collect();
        if ($kurikulumAktif) {
            // Ambil daftar mata kuliah yang sah terdaftar di bawah kurikulum aktif tersebut
            $courses = DB::table('courses')
                ->where('kurikulum_id', $kurikulumAktif->id)
                ->get();
        }

        // Ambil histori mata kuliah yang SUDAH dikontrak mahasiswa pada tahun ajaran aktif ini
        $krsSudahDiambil = DB::table('krs_pakets')
            ->join('courses', 'krs_pakets.course_id', '=', 'courses.id')
            ->where('krs_pakets.mahasiswa_id', $mahasiswa->id)
            ->where('krs_pakets.tahun_ajaran_id', session('tahun_ajaran_id'))
            ->select('courses.*', 'krs_pakets.id as krs_id')
            ->get();

        return view('mahasiswa.krs', compact('mahasiswa', 'kurikulumAktif', 'courses', 'krsSudahDiambil'));
    }

    // 2. Simpan Transaksi KRS Paket (Grouping Angkatan & Sesi Terkunci)
    public function store(Request $request)
    {
        $request->validate([
            'course_ids' => 'required|array|min:1',
        ]);

        $user = Auth::user();
        $mahasiswa = DB::table('mahasiswa')->where('user_id', $user->id)->first();

        // Mengambil potongan tahun masuk dari NIM atau field tahun_masuk (Contoh default: diambil dari angkatan kodingan lokal)
        // Kita simpan angkatannya untuk grouping kebutuhan monitoring Kaprodi/BPM
        $angkatanTahunMasuk = $mahasiswa->tahun_masuk ?? date('Y');

        try {
            DB::beginTransaction();

            foreach ($request->course_ids as $courseId) {
                // Cek cegah draf ganda (biar mhs tidak mengontrak matkul yang sama dua kali di semester ini)
                $cekGanda = DB::table('krs_pakets')
                    ->where('mahasiswa_id', $mahasiswa->id)
                    ->where('course_id', $courseId)
                    ->where('tahun_ajaran_id', session('tahun_ajaran_id'))
                    ->exists();

                if (!$cekGanda) {
                    DB::table('krs_pakets')->insert([
                        'mahasiswa_id' => $mahasiswa->id,
                        'course_id' => $courseId,
                        'tahun_ajaran_id' => session('tahun_ajaran_id'), // Terkunci Sesi Aktif!
                        'tahun_masuk_angkatan' => $angkatanTahunMasuk, // Kunci Grouping Angkatan!
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'KRS Paket Semester ini Berhasil Dikontrak! Data Anda sah masuk antrean rombel.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses KRS: ' . $e->getMessage());
        }
    }
}