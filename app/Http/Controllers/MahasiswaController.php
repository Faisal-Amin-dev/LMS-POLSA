<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
         // Eager loading 'classrooms' agar tidak berat saat load data banyak
        $mahasiswas = Mahasiswa::with('user', 'classrooms')->latest()->get();
        
        // Ambil semua data kelas untuk pilihan di modal
        $classrooms = \App\Models\Classroom::all();

        $query = Mahasiswa::with('user');
        if ($request->prodi_id) { $query->where('prodi_id', $request->prodi_id); }
        if ($request->semester) { $query->where('semester', $request->semester); }

        $mahasiswas = $query->latest()->get();

        $prodis = \App\Models\Prodi::all();

        return view('admin.mahasiswa', compact('mahasiswas', 'classrooms','prodis',));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim'      => 'required|unique:mahasiswa,nim',
            'nama'     => 'required',
            'prodi_id'    => 'required',
            'kelas'    => 'required',
            'semester' => 'required|numeric',
            'email'    => 'required|email|unique:users,email',
        ]);

        try {
            DB::beginTransaction();
            $user = User::create([
                'name'     => $request->nama,
                'username' => $request->nim,
                'email'    => $request->email,
                'password' => Hash::make($request->nim),
                'role'     => 'mahasiswa',
                'source'   => 'local',
            ]);

            Mahasiswa::create([
                'user_id'  => $user->id,
                'nim'      => $request->nim,
                'nama'     => $request->nama,
                'prodi_id'    => $request->prodi_id,
                'kelas'    => $request->kelas,
                'semester' => $request->semester,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Mahasiswa berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $request->validate([
            'nim'           => 'required|unique:mahasiswa,nim,' . $id,
            'nama'          => 'required',
            'email'         => 'required|email|unique:users,email,' . $mahasiswa->user_id,
            'prodi_id'      => 'required',
            'semester'      => 'required|numeric',
            'classroom_ids' => 'nullable|array' // 1. Tambahkan ini untuk Many-to-Many
        ]);

        try {
            DB::beginTransaction();

            // 1. Update Akun Login (Tabel users)
            $user = \App\Models\User::find($mahasiswa->user_id);
            if ($user) {
                $user->update([
                    'name'     => $request->nama,
                    'email'    => $request->email,
                    'username' => $request->nim,
                ]);
            }

            // 2. Update Data Profil (Tabel mahasiswas)
            $mahasiswa->update([
                'nim'      => $request->nim,
                'nama'     => $request->nama,
                'prodi_id'    => $request->prodi_id,
                'semester' => $request->semester,
                // Kolom 'kelas' string bisa dihapus jika sudah pakai Many-to-Many murni
            ]);

            // 3. UPDATE TABEL PIVOT (Kuncinya di sini Pakdhe!)
            // Menghubungkan mahasiswa ke banyak kelas sekaligus
            $mahasiswa->classrooms()->sync($request->input('classroom_ids', []));

            DB::commit();
            return redirect()->back()->with('success', 'Data mahasiswa dan kelas berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $m = Mahasiswa::findOrFail($id);
        if($m->user) { $m->user->delete(); }
        $m->delete();
        return redirect()->back()->with('success', 'Data Mahasiswa dan Akun berhasil dihapus!');
    }
}