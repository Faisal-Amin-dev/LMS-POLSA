<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    // Dokumentasi: Daftar kolom yang boleh diisi (Mass Assignment)
    protected $fillable = ['course_id', 'day', 'start_time', 'end_time', 'room'];

    /**
     * Dokumentasi Relasi: 
     * Setiap jadwal adalah milik (belongsTo) satu Mata Kuliah (Course).
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}