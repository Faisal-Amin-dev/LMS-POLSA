<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;

class MaterialController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Input
        // Memastikan judul diisi, dan file yang diupload sesuai format
        $request->validate([
            'course_id' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,mp4|max:10240', // max 10MB
            'link_url' => 'nullable|url'
        ]);

        // 2. Siapkan wadah kosong untuk nama file
        $fileName = null;

        // 3. Proses Upload File (Jika dosen mengunggah file)
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            
            // Buat nama file unik agar tidak bentrok (contoh: 169123123_Modul1.pdf)
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Simpan file tersebut ke folder: storage/app/public/materials
            $file->storeAs('public/materials', $fileName);
        }

        // 4. Simpan ke Database
        Material::create([
            'course_id' => $request->course_id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $fileName, // Menyimpan nama filenya saja ke DB
            'link_url' => $request->link_url,
        ]);

        // 5. Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Materi berhasil diposting!');
    }
}