<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
  protected $fillable = ['classroom_id', 'title', 'description', 'file_path'];

    public function Classroom() {
        return $this->belongsTo(Classroom::class);
    }
}
