<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    // Kunci nama tabel ke 'mahasiswa'
    protected $table = 'mahasiswa';

    protected $fillable = [
        'nim',
        'nama',
        'prodi',
        'kelas',
        'semester',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classrooms() {
    return $this->belongsToMany(Classroom::class, 'classroom_mahasiswa', 'mahasiswa_id', 'classroom_id');
}

}