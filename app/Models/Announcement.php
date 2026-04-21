<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    /**
     * Dokumentasi: Daftar kolom yang boleh diisi secara massal (Mass Assignment).
     * Kita izinkan course_id, user_id, dan content.
     */
    protected $fillable = [
        'course_id', 
        'user_id', 
        'content'
    ];

    // Relasi ke User (Penulis Pengumuman)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
