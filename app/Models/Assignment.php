<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'classroom_id', 
        'title', 
        'description', 
        'deadline', 
        'file_path'
    ];

    /**
     * Dokumentasi: Relasi ke tabel Submissions.
     * Satu tugas bisa punya banyak pengumpulan dari mahasiswa.
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class, 'assignment_id');
    }

    // Relasi balik ke Kelas
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
