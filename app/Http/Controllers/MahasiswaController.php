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
        $query = Mahasiswa::with('user');
        if ($request->prodi) { $query->where('prodi', $request->prodi); }
        if ($request->semester) { $query->where('semester', $request->semester); }

        $mahasiswas = $query->latest()->get();
        $daftarProdi = [
            'Teknik Informatika (D3)', 'Administrasi Bisnis (D3)', 
            'Akuntansi (D3)', 'TRPL (S1 Terapan)', 'Bisnis Digital (S1 Terapan)'
        ];

        return view('admin.mahasiswa', compact('mahasiswas', 'daftarProdi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim'      => 'required|unique:mahasiswa,nim',
            'nama'     => 'required',
            'prodi'    => 'required',
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
                'prodi'    => $request->prodi,
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
        $request->validate(['nama'=>'required','prodi'=>'required','kelas'=>'required','semester'=>'required']);
        try {
            DB::beginTransaction();
            $m = Mahasiswa::findOrFail($id);
            $m->update($request->only(['nama', 'prodi', 'kelas', 'semester']));
            if($m->user) { $m->user->update(['name' => $request->nama]); }
            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
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