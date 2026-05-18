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

    public function assignments()
    {
        return $this->hasMany(\App\Models\Assignment::class, 'classroom_id');
    }

    public function materials()
    {
        return $this->hasMany(\App\Models\Material::class, 'classroom_id');
    }

    public function announcements()
    {
        return $this->hasMany(\App\Models\Announcement::class, 'classroom_id');
    }
}
