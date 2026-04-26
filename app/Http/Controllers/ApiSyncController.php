<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Facades\Hash;

class ApiSyncController extends Controller
{
    public function syncMahasiswa()
    {
        $dummy = [
            ['nim' => '32241015', 'nama' => 'Budi API', 'email' => 'budi@student.polsa.ac.id', 'prodi' => 'Teknik Informatika (D3)', 'kelas' => 'TI 4A', 'semester' => 4],
        ];

        foreach ($dummy as $d) {
            $user = User::updateOrCreate(['email' => $d['email']], [
                'name' => $d['nama'], 'username' => $d['nim'], 'password' => Hash::make($d['nim']), 'role' => 'mahasiswa', 'source' => 'api_siap'
            ]);
            Mahasiswa::updateOrCreate(['nim' => $d['nim']], array_merge($d, ['user_id' => $user->id]));
        }
        return redirect()->back()->with('success', 'Sync Mahasiswa Berhasil!');
    }

    public function syncKelas()
    {
        $dummyCourses = [
            ['kode_matkul' => 'INF101', 'nama_matkul' => 'Pemrograman Web', 'sks' => 3],
            ['kode_matkul' => 'INF102', 'nama_matkul' => 'Basis Data', 'sks' => 4],
        ];
        foreach ($dummyCourses as $c) {
            Course::updateOrCreate(['kode_matkul' => $c['kode_matkul']], $c);
        }
        return redirect()->back()->with('success', 'Sync Mata Kuliah Berhasil!');
    }
}