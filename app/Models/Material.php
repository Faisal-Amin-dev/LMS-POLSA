<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
  protected $fillable = ['course_id', 'title', 'description', 'file_path'];

    public function Course() {
        return $this->belongsTo(Course::class);
    }
}
