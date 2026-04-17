<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    // 1. Mendefinisikan nama tabel secara eksplisit
    // Mencegah Laravel mencari tabel bernama 'mahasiswas'
    protected $table = 'mahasiswa';

    // 2. Mendaftarkan kolom yang boleh diisi lewat form/controller
    // Kolom ini harus sama persis dengan yang ada di migration dan controller
    protected $fillable = [
        'nim',
        'nama',
        'prodi',
        'source',
    ];
}
