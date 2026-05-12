<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function index()
    {
        $prodis = \App\Models\Prodi::all();
        return view('admin.prodi', compact('prodis'));
    }

    public function sync()
    {
        // Simulasi data dari API Kampus
        $dummyApiData = [
            ['kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika'],
            ['kode_prodi' => 'AK', 'nama_prodi' => 'Akuntansi'],
            ['kode_prodi' => 'AN', 'nama_prodi' => 'Administrasi Niaga'],
        ];

        foreach ($dummyApiData as $data) {
            \App\Models\Prodi::updateOrCreate(
                ['kode_prodi' => $data['kode_prodi']],
                ['nama_prodi' => $data['nama_prodi']]
            );
        }

        return redirect()->back()->with('success', 'Data Prodi berhasil disinkronkan dari API!');
    }
    // Simpan Prodi Baru (Manual)
    public function store(Request $request)
    {
        $request->validate([
            'kode_prodi' => 'required|unique:prodis,kode_prodi',
            'nama_prodi' => 'required'
        ]);

        \App\Models\Prodi::create($request->all());

        return redirect()->back()->with('success', 'Data Prodi berhasil ditambah secara manual!');
    }

    // Update Prodi
    public function update(Request $request, $id)
    {
        $prodi = \App\Models\Prodi::findOrFail($id);
        $prodi->update($request->all());

        return redirect()->back()->with('success', 'Data Prodi berhasil diperbarui!');
    }

    // Hapus Prodi
    public function destroy($id)
    {
        $prodi = \App\Models\Prodi::findOrFail($id);
        $prodi->delete();

        return redirect()->back()->with('success', 'Data Prodi berhasil dihapus!');
    }
}
