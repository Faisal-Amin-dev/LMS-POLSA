<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    // Pastikan nama tabelnya benar
    protected $table = 'dosens';

    // Daftarkan kolom yang boleh diisi
    protected $fillable = [
        'user_id',
        'nidn',
        'nama',
        'bidang_keahlian', // <- Penting, pastikan ini ada
    ];

    // Relasi ke Akun Login (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ==========================================
    // INI DIA FUNGSI YANG BIKIN ERROR KALAU HILANG
    // ==========================================
    // Relasi Many-to-Many ke Mata Kuliah (Course)
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_dosen');
    }
}