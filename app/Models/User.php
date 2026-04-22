<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
        'nidn',   
        'prodi',  
        'source',
    ];

    // Relasi jika dia Dosen (punya banyak matkul)
    public function taughtCourses() {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    // Relasi jika dia Mahasiswa (mengikuti banyak matkul)
    public function enrollments() {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // Relasi ke Mahasiswa (jika user ini adalah mahasiswa)
    public function mahasiswa() {
        return $this->hasOne(Mahasiswa::class);
    }
}
