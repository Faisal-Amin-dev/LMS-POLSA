<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi Input
        $request->validate([
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
            'password' => 'nullable|min:6|confirmed', // Validasi minimal 6 karakter & kecocokan konfirmasi
        ]);

        // 2. Proses Upload Foto Profil (Jika ada foto baru yang diunggah)
        if ($request->hasFile('foto')) {
            // Hapus foto lama di folder storage jika ada, biar tidak menumpuk di server
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            // Simpan foto baru ke folder 'storage/app/public/avatars'
            $path = $request->file('foto')->store('avatars', 'public');
            $user->foto = $path;
        }

        // 3. Proses Ganti Password (Jika kolom password diisi)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // 4. Simpan ke Database
        $user->save();

        return redirect()->back()->with('success', 'Profil dan akun keamanan Anda berhasil diperbarui!');
    }
}