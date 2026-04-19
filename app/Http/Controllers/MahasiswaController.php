<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Controller: MahasiswaDashboard
 * Deskripsi: Mengelola tampilan utama ala Google Classroom.
 * Fokus: Tugas (Classwork), Materi (Resources), dan Integrasi Drive.
 */
class MahasiswaController extends Controller
{
    public function index()
    {
        // Simulasi data dari SIAP POLSA & Integrasi GDrive
        $data = [
            'akademik' => [
                'tugas_pending' => 3,
                'semester'      => 'Semester 4 - TI',
                'status_spp'    => 'LUNAS',
                'drive_folder'  => 'https://drive.google.com/...' // Link folder kelas di Drive
            ],
            
            // Fitur Utama: Tugas Mendatang (Deadlines)
            'tugas_terbaru' => [
                [
                    'judul'    => 'Praktikum 5: Integrasi API',
                    'matkul'   => 'Pemrograman Web II',
                    'deadline' => 'Besok, 23:59',
                    'status'   => 'Belum Mengumpulkan',
                    'urgensi'  => 'high'
                ],
                [
                    'judul'    => 'Laporan Jaringan Nirkabel',
                    'matkul'   => 'Jaringan Komputer',
                    'deadline' => '3 Hari lagi',
                    'status'   => 'Draft',
                    'urgensi'  => 'medium'
                ],
            ],

            // Fitur: Materi & Resource (Persiapan Integrasi GDrive)
            'materi_terbaru' => [
                ['judul' => 'Modul 4: Normalisasi Database', 'tipe' => 'PDF', 'link' => '#'],
                ['judul' => 'Slide Arsitektur Jaringan', 'tipe' => 'GSlide', 'link' => '#'],
            ],

            // Paket Mata Kuliah (List Kelas)
            'daftar_kelas' => [
                ['nama' => 'Manajemen Basis Data', 'dosen' => 'Pak Budi', 'kode' => 'MBD-04'],
                ['nama' => 'Pemrograman Web II', 'dosen' => 'Ibu Sari', 'kode' => 'PW2-04'],
                ['nama' => 'Jaringan Komputer', 'dosen' => 'Pak Andi', 'kode' => 'JK-04'],
            ]
        ];

        return view('mahasiswa.dashboard', compact('data'));
    }
}