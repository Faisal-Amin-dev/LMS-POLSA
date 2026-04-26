<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    // Karena nama tabel default Laravel selalu pakai 's', kita pastikan saja
    protected $table = 'dosens';

    protected $fillable = [
        'user_id',
        'nidn',
        'nama',
        'bidang_keahlian',
    ];

    // Relasi: Satu dosen punya satu akun user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}