<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use Illuminate\Support\Facades\Hash;

class DosenController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'nidn'     => 'required|unique:users,nidn',
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email', // Butuh email buat login
            'password' => 'required|min:8', // Butuh password buat login
        ]);

        // 2. Simpan ke database (Tabel Users)
        User::create([
            'nidn'     => $request->nidn,
            'name'     => $request->name,
            'email'    => $request->email,
            'username' => strstr($request->email, '@', true),
            'password' => Hash::make($request->password), // Password di-enkripsi
            'role'     => 'dosen', 
            'prodi'    => $request->prodi ?? 'Teknik Informatika', // Default prodi kalau gak diisi
            'source'   => 'manual' 
        ]);

        // 3. Kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Akun Dosen berhasil dibuat!');
    }
    public function index()
    {
        // Ambil semua user yang rolenya dosen
        $dosens = User::where('role', 'dosen')->latest()->get();
        
        return view('admin.dosen', compact('dosens'));
    }
}