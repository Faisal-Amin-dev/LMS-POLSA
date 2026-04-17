<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course; // Ingat, nama model kelas Pakdhe adalah Course
use App\Models\Material; // Model untuk Materi

class DosenController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'nidn' => 'required|unique:dosen,nidn',
            'nama' => 'required',
            'prodi' => 'required',
        ]);

        // 2. Simpan ke database
        Dosen::create([
            'nidn' => $request->nidn,
            'nama' => $request->nama,
            'prodi' => $request->prodi,
            'source' => 'manual' // Pembeda dengan data dari API SIAP POLSA
        ]);

        // 3. Kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Data Dosen berhasil ditambahkan!');
    }
}
    


    

