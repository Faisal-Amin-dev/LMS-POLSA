<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{ 
    protected $fillable = ['course_code', 'course_name', 'teacher_id'];

    public function teacher() {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function assignments() {
        return $this->hasMany(Assignment::class);
    }
    public function materials() {
        return $this->hasMany(Material::class);
    }

}
