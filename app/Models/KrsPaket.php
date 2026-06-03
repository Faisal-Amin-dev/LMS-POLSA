<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KrsPaket extends Model
{
    protected $table = 'krs_pakets';
    
    protected $fillable = [
        'mahasiswa_id',
        'course_id',
        'tahun_ajaran_id',
        'tahun_masuk_angkatan'
    ];

    // Relasi ke data Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    // Relasi ke data Mata Kuliah
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // Relasi ke data Tahun Ajaran Aktif
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}