<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Dosen;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DosenController extends Controller
{
    public function index()
    {
        // Mengambil data dosen beserta relasi akun (user)
        $dosens = Dosen::with('user')->latest()->get();
        return view('admin.dosen', compact('dosens'));
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
        ]);

        DB::transaction(function () use ($request, $dosen, $user) {
            $user->update([
                'name'     => $request->nama,
                'email'    => $request->email,
                'username' => $request->nidn,
            ]);

            $dosen->update([
                'nidn'            => $request->nidn,
                'nama'            => $request->nama,
                'bidang_keahlian' => $request->bidang_keahlian,
            ]);
        });

        return redirect()->back()->with('success', 'Data Dosen berhasil diperbarui!');
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