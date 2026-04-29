<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Course;
use App\Models\Classroom;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ApiSyncController extends Controller
{
    // ==========================================
    // 1. FUNGSI SYNC MAHASISWA
    // ==========================================
    public function syncMahasiswa()
    {
        $dummy = [
            ['nim' => '32241015', 'nama' => 'Budi API', 'email' => 'budi@student.polsa.ac.id', 'prodi' => 'Teknik Informatika (D3)', 'kelas' => 'TI 4A', 'semester' => 4],
            ['nim' => '32241016', 'nama' => 'Ani API', 'email' => 'ani@student.polsa.ac.id', 'prodi' => 'Teknik Informatika (D3)', 'kelas' => 'TI 4A', 'semester' => 4],
        ];

        foreach ($dummy as $d) {
            $user = User::updateOrCreate(['email' => $d['email']], [
                'name' => $d['nama'], 'username' => $d['nim'], 'password' => Hash::make($d['nim']), 'role' => 'mahasiswa', 'source' => 'api_siap'
            ]);
            Mahasiswa::updateOrCreate(['nim' => $d['nim']], array_merge($d, ['user_id' => $user->id]));
        }
        return redirect()->back()->with('success', 'Sync Mahasiswa Berhasil!');
    }

    // ==========================================
    // 2. FUNGSI SYNC DOSEN (INI YANG BIKIN ERROR KALAU HILANG)
    // ==========================================
    public function syncDosen()
    {
        // SIMULASI DATA DOSEN DARI API SIAP-POLSA
        $apiResponse = [
            [
                'nidn' => '0521049001',
                'nama' => 'Bpk. Hendro, M.Kom',
                'bidang_keahlian' => 'Rekayasa Perangkat Lunak',
                'email' => 'hendro@dosen.polsa.ac.id'
            ],
            [
                'nidn' => '0521049002',
                'nama' => 'Ibu Siti, M.T',
                'bidang_keahlian' => 'Sistem Basis Data',
                'email' => 'siti@dosen.polsa.ac.id'
            ],
            [
                'nidn' => '0612038501',
                'nama' => 'Dr. Eko Waluyo, M.T.',
                'bidang_keahlian' => 'Mobile Development',
                'email' => 'eko@polsa.ac.id'
            ]
        ];

        try {
            DB::beginTransaction();
            $countNew = 0;
            $countUpdate = 0;

            foreach ($apiResponse as $data) {
                $userDosen = User::updateOrCreate(
                    ['email' => $data['email']],
                    [
                        'name' => $data['nama'],
                        'username' => $data['nidn'],
                        'password' => Hash::make($data['nidn']),
                        'role' => 'dosen',
                        'source' => 'api_siap'
                    ]
                );

                $dosenExisting = Dosen::where('nidn', $data['nidn'])->first();
                if ($dosenExisting) {
                    $dosenExisting->update([
                        'nama' => $data['nama'],
                        'bidang_keahlian' => $data['bidang_keahlian'],
                    ]);
                    $countUpdate++;
                } else {
                    Dosen::create([
                        'user_id' => $userDosen->id,
                        'nidn' => $data['nidn'],
                        'nama' => $data['nama'],
                        'bidang_keahlian' => $data['bidang_keahlian'],
                    ]);
                    $countNew++;
                }
            }

            DB::commit();
            return redirect()->back()->with('success', "Sync Dosen Berhasil! $countNew data baru ditambahkan, $countUpdate diperbarui.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal Sync Dosen: ' . $e->getMessage());
        }
    }

    // ==========================================
    // 3. FUNGSI SYNC KELAS & MATKUL
    // ==========================================
    public function syncKelas()
    {
        // SIMULASI DATA JADWAL DARI SIAP-POLSA
        $apiResponse = [
            [
                'kode_mk' => 'TI204', 'nama_mk' => 'Pemrograman Web Lanjut', 'sks' => 3, 'prodi' => 'Teknik Informatika (D3)', 'semester' => 4,
                'dosen_nidn' => '0521049001', 'dosen_nama' => 'Bpk. Hendro, M.Kom',
                'kode_mk' => 'TI 4A', 'hari' => 'Senin', 'jam_mulai' => '08:00', 'jam_selesai' => '10:30', 'tahun' => '2025/2026'
            ],
            [
                'kode_mk' => 'TI205', 'nama_mk' => 'Sistem Basis Data', 'sks' => 4, 'prodi' => 'Teknik Informatika (D3)', 'semester' => 4,
                'dosen_nidn' => '0521049002', 'dosen_nama' => 'Ibu Siti, M.T',
                'kode_mk' => 'TI 4B', 'hari' => 'Sabtu', 'jam_mulai' => '13:00', 'jam_selesai' => '16:00', 'tahun' => '2025/2026'
            ]
        ];

        try {
            DB::beginTransaction();
            foreach ($apiResponse as $data) {

            // 1. Sync Mata Kuliah
            $course = Course::updateOrCreate(
                ['kode_mk' => $data['kode_mk']], 
                ['nama_mk' => $data['nama_mk'], 'sks' => $data['sks'], 'prodi' => $data['prodi'], 'semester' => $data['semester']]
            );

            // 2. Sync Dosen
            $userDosen = User::firstOrCreate(
                ['username' => $data['dosen_nidn']],
                ['name' => $data['dosen_nama'], 'email' => strtolower(str_replace(' ', '', $data['dosen_nama'])) . '@dosen.polsa.ac.id', 'password' => Hash::make($data['dosen_nidn']), 'role' => 'dosen', 'source' => 'api_siap']
            );
            $dosen = Dosen::firstOrCreate(['nidn' => $data['dosen_nidn']], ['user_id' => $userDosen->id, 'nama' => $data['dosen_nama']]);

            // 3. HUBUNGKAN DOSEN KE MATKUL
            $dosen->courses()->syncWithoutDetaching([$course->id]);

            // 4. Bentuk Kelas LMS
            $classroom = Classroom::updateOrCreate(
                [
                    'course_id' => $course->id, 
                    'nama_kelas' => $data['kode_mk'] . ' - ' . $data['nama_mk'],
                    'tahun_akademik' => $data['tahun']
                ],
                [
                    'dosen_id' => $dosen->id, 'hari' => $data['hari'], 'jam_mulai' => $data['jam_mulai'], 'jam_selesai' => $data['jam_selesai']
                ]
            );

            // 5. OTOMATIS SEDOT MAHASISWA
            $mahasiswaIds = Mahasiswa::where('kelas', $data['kode_mk'])->pluck('id');
            $classroom->mahasiswas()->syncWithoutDetaching($mahasiswaIds);
        }
        DB::commit();
        return redirect()->back()->with('success', 'Auto-Sync Berhasil! Kelas sudah dirakit lengkap dengan Dosen & Mahasiswa.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal Auto-Sync: ' . $e->getMessage());
        }
    }
}