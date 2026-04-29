<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    // 1. Mendefinisikan nama tabel secara eksplisit
    // Mencegah Laravel mencari tabel bernama 'kelass'
    protected $table = 'kelas';

    // 2. Mendaftarkan kolom yang boleh diisi lewat form/controller
    // Kolom ini harus sama persis dengan yang ada di migration dan controller
    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'prodi',
        'source',
    ];
}
