<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Assignment;
use App\Models\Submission;

class ArsipNilaiExport implements FromView, ShouldAutoSize
{
    protected $classrooms;

    // Menerima kiriman lempar data dari Controller
    public function __construct($classrooms)
    {
        $this->classrooms = $classrooms;
    }

    public function view(): View
    {
        // Ambil semua ID tugas dari rombongan kelas aktif dosen ini
        $classroomIds = $this->classrooms->pluck('id');
        $assignmentIds = Assignment::whereIn('classroom_id', $classroomIds)->pluck('id');
        
        // Ambil rekap data submissions mahasiswa
        $submissions = Submission::whereIn('assignment_id', $assignmentIds)
            ->get()
            ->groupBy(['mahasiswa_id', 'assignment_id']);

        // Melempar data ke template khusus Excel
        return view('exports.arsip_nilai_excel', [
            'classrooms' => $this->classrooms,
            'submissions' => $submissions
        ]);
    }
}