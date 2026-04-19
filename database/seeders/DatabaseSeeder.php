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
        // 1. Buat Akun Admin (TAMBAHAN BARU)
        User::create([
            'username' => 'ADMIN001',
            'name' => 'Administrator Kampus',
            'email' => 'admin@polsa.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Buat Akun Dosen
        $dosen = User::create([
            'username' => 'NIDN001',
            'name' => 'Faisal Amin',
            'email' => 'faisal@polsa.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'dosen',
        ]);

        // 3. Buat Akun Mahasiswa
        $mahasiswa = User::create([
            'username' => 'NIM001',
            'name' => 'Mahasiswa TI',
            'email' => 'mahasiswa@polsa.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa',
        ]);

        // 4. Buat Contoh Mata Kuliah
        Course::create([
            'course_code' => 'TI4A-01',
            'course_name' => 'Pemrograman Mobile Kotlin',
            'teacher_id' => $dosen->id,
        ]);
    }
    
}