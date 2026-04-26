<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Course;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
{
    // 1. Buat Akun Admin
    \App\Models\User::create([
        'username' => 'ADMIN001',
        'name'     => 'Administrator Kampus',
        'email'    => 'admin@polsa.ac.id',
        'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        'role'     => 'admin',
        'source'   => 'local',
    ]);

    // 2. Buat Contoh Mata Kuliah (Sesuai kolom baru: nama_mk, kode_mk, sks, prodi)
    \App\Models\Course::create([
        'kode_mk' => 'TI4A-KOTLIN',
        'nama_mk' => 'Pemrograman Mobile Kotlin',
        'sks'     => 3,
        'prodi'   => 'Teknik Informatika',
    ]);

    // 3. (Opsional) Buat Contoh Dosen Dummy
    $userDosen = \App\Models\User::create([
        'username' => '0612038501',
        'name'     => 'Dr. Eko Waluyo, M.T.',
        'email'    => 'eko@polsa.ac.id',
        'password' => \Illuminate\Support\Facades\Hash::make('0612038501'),
        'role'     => 'dosen',
        'source'   => 'local',
    ]);

    \App\Models\Dosen::create([
        'user_id'         => $userDosen->id,
        'nidn'            => '0612038501',
        'nama'            => 'Dr. Eko Waluyo, M.T.',
        'bidang_keahlian' => 'Mobile Development',
    ]);
}
    
}