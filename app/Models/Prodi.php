<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $fillable = ['nama_prodi', 'kode_prodi'];  

    // Relasi: Satu prodi punya banyak mahasiswa
    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class);
    }
}
