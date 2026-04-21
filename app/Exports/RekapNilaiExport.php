<?php

namespace App\Exports;

use App\Models\Submission;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RekapNilaiExport implements FromCollection, WithHeadings
{
    protected $course_id;

    public function __construct($course_id) {
        $this->course_id = $course_id;
    }

    public function collection() {
        // Mengambil data nilai mahasiswa di kelas tertentu
        return Submission::whereHas('assignment', function($q) {
            $q->where('course_id', $this->course_id);
        })->select('student_id', 'assignment_id', 'grade')->get();
    }

    public function headings(): array {
        return ["ID Mahasiswa", "ID Tugas", "Nilai"];
    }
}