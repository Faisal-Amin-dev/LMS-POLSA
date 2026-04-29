<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';

    // Daftarkan semua kolom yang boleh diisi lewat form
    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks',
        'prodi',
        'semester',
        'teacher_id',
    ];

    // Relasi ke Kelas (Satu Matkul bisa punya banyak jadwal kelas)
    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }
}