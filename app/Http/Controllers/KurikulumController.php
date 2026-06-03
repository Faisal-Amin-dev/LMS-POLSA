<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KurikulumController extends Controller
{
    // 1. Tampilkan Halaman Utama Kurikulum
    public function index()
    {
        // Ambil semua data kurikulum beserta nama prodinya
        $kurikulums = DB::table('kurikulums')
            ->join('prodis', 'kurikulums.prodi_id', '=', 'prodis.id')
            ->select('kurikulums.*', 'prodis.nama_prodi')
            ->orderBy('kurikulums.created_at', 'desc')
            ->get();

        // Ambil data prodi untuk pilihan di Modal Tambah Data
        $prodis = DB::table('prodis')->get();

        return view('admin.kurikulum', compact('kurikulums', 'prodis'));
    }

    // 2. Simpan Kurikulum Baru
    public function store(Request $request)
    {
        $request->validate([
            'prodi_id' => 'required',
            'nama_kurikulum' => 'required|string|max:255',
        ]);

        DB::table('kurikulums')->insert([
            'prodi_id' => $request->prodi_id,
            'nama_kurikulum' => $request->nama_kurikulum,
            'is_aktif' => false, // Default tidak aktif saat baru dibuat
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Kurikulum baru berhasil ditambahkan sebagai data pokok!');
    }

    // 3. Update Nama Kurikulum
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kurikulum' => 'required|string|max:255',
        ]);

        DB::table('kurikulums')->where('id', $id)->update([
            'nama_kurikulum' => $request->nama_kurikulum,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Nama kurikulum berhasil diperbarui!');
    }

    // 4. Logika Sakti: Toggle Status Aktif (Hanya boleh ada 1 kurikulum aktif per prodi)
    public function toggleStatus($id)
    {
        $kurikulum = DB::table('kurikulums')->where('id', $id)->first();

        if (!$kurikulum) {
            return redirect()->back()->with('error', 'Data kurikulum tidak ditemukan!');
        }

        // Jika kurikulum mau diaktifkan (is_aktif saat ini false)
        if (!$kurikulum->is_aktif) {
            // Matikan dulu semua kurikulum lain yang ada di prodi yang sama
            DB::table('kurikulums')
                ->where('prodi_id', $kurikulum->prodi_id)
                ->update(['is_aktif' => false]);

            // Baru aktifkan kurikulum ini
            DB::table('kurikulums')->where('id', $id)->update(['is_aktif' => true]);
            $pesan = 'Kurikulum berhasil diset AKTIF untuk program studi tersebut!';
        } else {
            // Jika mau dinonaktifkan biasa
            DB::table('kurikulums')->where('id', $id)->update(['is_aktif' => false]);
            $pesan = 'Status aktif kurikulum berhasil dinonaktifkan!';
        }

        return redirect()->back()->with('success', $pesan);
    }

    // 5. Hapus Kurikulum
    public function destroy($id)
    {
        DB::table('kurikulums')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data kurikulum berhasil dihapus dari sistem!');
    }
}