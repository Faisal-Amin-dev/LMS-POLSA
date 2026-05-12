<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = ['course_id', 'dosen_id', 'nama_kelas', 'tahun_akademik'];

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function dosen() {
        return $this->belongsTo(Dosen::class);
    }

    public function mahasiswas() {
        return $this->belongsToMany(Mahasiswa::class, 'classroom_mahasiswa', 'classroom_id', 'mahasiswa_id');
    }
}
