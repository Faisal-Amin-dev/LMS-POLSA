<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = ['assignment_id', 'student_id', 'file_path', 'grade', 'status', 'submitted_at'];

    public function Assignment() {
        return $this->belongsTo(Assignment::class);
    }
}
