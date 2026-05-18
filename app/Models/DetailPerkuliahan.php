<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPerkuliahan extends Model
{
    protected $table = 'detail_perkuliahans';
    protected $fillable = ['mahasiswa_id', 'classroom_id', 'tahun_akademik', 'status_krs'];

    public function mahasiswa() {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function classroom() {
        return $this->belongsTo(Classroom::class);
    }
}