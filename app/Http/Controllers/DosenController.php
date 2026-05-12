<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Dosen;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Course;

class DosenController extends Controller
{
    public function index()
    {
        // Mengambil data dosen beserta relasi akun (user) dan matkul yang diampu
        $dosens = Dosen::with(['user', 'courses'])->latest()->get();
        
        // Mengambil semua matkul yang sudah terdaftar di sistem (hasil API atau input manual)
        $courses = Course::orderBy('nama_mk', 'asc')->get(); 

        // Mengambil semua prodi untuk filter atau informasi tambahan
        $prodis = \App\Models\Prodi::all();
        

        return view('admin.dosen', compact('dosens','prodis', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nidn'  => 'required|unique:dosens,nidn',
            'nama'  => 'required',
            'email' => 'required|email|unique:users,email',
            'bidang_keahlian' => 'nullable',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->nama,
                'email'    => $request->email,
                'username' => $request->nidn, 
                'password' => Hash::make($request->nidn), // Password default = NIDN
                'role'     => 'dosen',
                'source'   => 'local', 
            ]);

            Dosen::create([
                'user_id'         => $user->id,
                'nidn'            => $request->nidn,
                'nama'            => $request->nama,
                'bidang_keahlian' => $request->bidang_keahlian,
            ]);
        });

        return redirect()->back()->with('success', 'Data Dosen dan Akun berhasil dibuat! Password default adalah NIDN.');
    }

    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);
        $user = User::findOrFail($dosen->user_id);

        $request->validate([
            'nidn'  => 'required|unique:dosens,nidn,' . $dosen->id,
            'nama'  => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'bidang_keahlian' => 'nullable',
            'course_ids' => 'nullable|array', // Tambahkan validasi untuk array matkul
        ]);

        DB::transaction(function () use ($request, $dosen, $user) {
            // 1. Update data login di tabel users
            $user->update([
                'name'     => $request->nama,
                'email'    => $request->email,
                'username' => $request->nidn,
            ]);

            // 2. Update data profil di tabel dosens
            $dosen->update([
                'nidn'            => $request->nidn,
                'nama'            => $request->nama,
                'bidang_keahlian' => $request->bidang_keahlian,
            ]);

            // 3. UPDATE TABEL PIVOT (Kuncinya di sini Pakdhe!)
            // sync() akan mencocokkan data di tabel 'course_dosen' dengan pilihan di modal
            if ($request->has('course_ids')) {
                $dosen->courses()->sync($request->course_ids);
            } else {
                // Jika tidak ada yang dicentang, kosongkan relasinya
                $dosen->courses()->sync([]);
            }
        });

        return redirect()->back()->with('success', 'Data Dosen dan Mata Kuliah berhasil diperbarui!');
    }
    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);
        
        DB::transaction(function () use ($dosen) {
            $userId = $dosen->user_id;
            $dosen->delete();
            User::destroy($userId);
        });

        return redirect()->back()->with('success', 'Profil Dosen dan akun sistem berhasil dihapus selamanya.');
    }
}