<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Course;
use Illuminate\Support\Facades\Hash;

class AnggotaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. MEMASTIKAN USER MAHASISWA ADA (Login Account)
        // updateOrCreate: Jika email sudah ada, dia cuma update. Gak bakal error duplikat!
        $user = User::updateOrCreate(
            ['email' => 'mahasiswa@polsa.ac.id'], // Kunci pencarian
            [
                'username' => 'NIM001',
                'name' => 'Mahasiswa TI',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
            ]
        );

        // 2. MEMASTIKAN PROFIL MAHASISWA ADA (Data Akademik)
        // Kita hubungkan ke user_id yang baru saja dibuat/diupdate di atas
        $mhs = Mahasiswa::updateOrCreate(
            ['user_id' => $user->id], // Kunci pencarian
            [
                'nim' => 'NIM001',
                'nama' => 'Mahasiswa TI',
                'prodi' => 'Teknik Informatika',
                'kelas' => 'TI-4A',
            ]
        );

       /**
         * Dokumentasi: Mengisi data kelas sesuai struktur tabel terbaru.
         * teacher_id diisi 2 (ID Pakdhe Faisal Amin dari tabel users).
         */
        $kelas = Course::updateOrCreate(
            ['id' => 1], // Mengunci ID 1 untuk testing
            [
                'course_code' => 'TI2026-PWL',
                'course_name' => 'Pemrograman Web Lanjut',
                'teacher_id'  => 2, // ID Dosen (Faisal Amin)
            ]
        );

        // 4. MENGHUBUNGKAN MAHASISWA KE KELAS (Tab Anggota)
        // syncWithoutDetaching: Menambah mahasiswa ke kelas tanpa menghapus yang sudah ada.
        // Sangat krusial agar tabel 'enrollments' terisi.
        $kelas->students()->syncWithoutDetaching([$mhs->id]);

        $this->command->info('Berhasil! Mahasiswa NIM001 sekarang sudah masuk di Kelas ID 1.');
    }
}