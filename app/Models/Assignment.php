<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
     protected $fillable = ['course_id', 'title', 'description', 'file_attachment', 'deadline'];

     public function Course() {
        return $this->belongsTo(Course::class);
     }

   
}
