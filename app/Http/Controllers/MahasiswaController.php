<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;       
use App\Models\Mahasiswa;   
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\DB;  


class MahasiswaController extends Controller
{
   // app/Http/Controllers/MahasiswaController.php
    public function indexAdmin() // Untuk nampilin tabel di Admin
    {
        $mahasiswas = \App\Models\Mahasiswa::latest()->get();
        return view('admin.mahasiswa', compact('mahasiswas'));
    }

   // Fungsi simpan data (Store)
    public function store(Request $request)
    {
        $request->validate([
            'nim'    => 'required|unique:mahasiswa,nim',
            'nama'   => 'required',
            'email'  => 'required|email|unique:users,email',
            'prodi'  => 'required',
            'kelas'  => 'required',
        ]);

        // Gunakan Transaction biar kalau satu gagal, semua batal (aman!)
        DB::transaction(function () use ($request) {
            // 1. Buat User baru untuk login
            $user = User::create([
                'name'     => $request->nama,
                'email'    => $request->email,
                'username' => $request->nim, 
                'password' => Hash::make($request->nim), 
                'role'     => 'mahasiswa',
            ]);

            // 2. Buat Mahasiswa baru yang terhubung ke User
            Mahasiswa::create([
                'user_id' => $user->id,
                'nim'     => $request->nim,
                'nama'    => $request->nama,
                'prodi'   => $request->prodi,
                'kelas'   => $request->kelas,
            ]);
        });

        return redirect()->back()->with('success', 'Data Mahasiswa dan Akun berhasil dibuat! Password default adalah NIM.');
    }
}