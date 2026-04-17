<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'kode_kelas' => 'required|unique:kelas,kode_kelas',
            'nama_kelas' => 'required',
            'prodi' => 'required',
        ]);

        // 2. Simpan ke database
        Kelas::create([
            'kode_kelas' => $request->kode_kelas,
            'nama_kelas' => $request->nama_kelas,
            'prodi' => $request->prodi,
            'source' => 'manual' // Pembeda dengan data dari API SIAP POLSA
        ]);

        // 3. Kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Data Kelas berhasil ditambahkan!');
    }
}
