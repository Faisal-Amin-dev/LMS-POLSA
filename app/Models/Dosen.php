<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    // 1. Mendefinisikan nama tabel secara eksplisit
    // Mencegah Laravel mencari tabel bernama 'dosens'
    protected $table = 'dosen';

    // 2. Mendaftarkan kolom yang boleh diisi lewat form/controller
    // Kolom ini harus sama persis dengan yang ada di migration dan controller
    protected $fillable = [
        'nidn',
        'nama',
        'prodi',
        'source',
    ];
}