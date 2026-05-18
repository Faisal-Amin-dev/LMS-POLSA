@extends('layouts.dosen')

@section('title', 'Arsip Nilai')
@section('header_title', 'Arsip & Rekapitulasi Nilai Matriks')

@section('content')
<div class="space-y-8">
    <div class="flex justify-between items-center no-print">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Rekapitulasi Nilai Matriks</h2>
            <p class="text-sm text-slate-500">Daftar buku nilai kumulatif dari seluruh ruang kelas LMS yang Anda ampu.</p>
        </div>
        <div class="flex gap-2">
            <button onclick="window.print()" class="bg-white border border-slate-300 text-slate-700 px-4 py-2.5 rounded-xl text-xs font-bold shadow-sm hover:bg-slate-50 transition-all flex items-center">
                <i class="fas fa-print mr-2"></i> Cetak PDF / Dokumen
            </button>
            <a href="{{ route('dosen.arsip.nilai', ['type' => 'excel']) }}" class="bg-emerald-600 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-sm hover:bg-emerald-700 transition-all flex items-center">
                <i class="fas fa-file-excel mr-2"></i> Export Excel
            </a>
        </div>
    </div>

    @foreach($classrooms as $kelas)
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden break-inside-avoid">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <div class="flex flex-col sm:flex-row justify-between items-start gap-2">
                <div>
                    <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded uppercase tracking-wider font-mono">{{ $kelas->course->kode_mk ?? '-' }}</span>
                    <h3 class="text-lg font-black text-slate-900 mt-1">{{ $kelas->course->nama_mk ?? '-' }}</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Grup Kelas / Rombel: <span class="font-bold text-slate-600">{{ $kelas->nama_kelas }}</span> | Semester: {{ $kelas->course->semester ?? '-' }}</p>
                </div>
                <div class="text-left sm:text-right bg-white px-4 py-2 rounded-xl border border-slate-100 shadow-sm">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Total Peserta</p>
                    <p class="text-xl font-black text-slate-800">{{ $kelas->mahasiswas->count() }} Mahasiswa</p>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            @php
                // Array penampung nilai per kolom tugas untuk kalkulasi statistik di bawah
                $colScores = [];
                foreach($kelas->assignments as $task) {
                    $colScores[$task->id] = [];
                }
                $rowAverages = [];
            @endphp

            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-200 text-slate-500 font-bold">
                        <th class="px-4 py-3.5 font-bold text-center w-12">No</th>
                        <th class="px-6 py-3.5 font-bold min-w-[200px]">Nama Mahasiswa / NIM</th>
                        
                        @foreach($kelas->assignments as $task)
                            <th class="px-4 py-3.5 font-bold text-center border-l border-slate-100 min-w-[120px]">{{ Str::limit($task->title, 15) }}</th>
                        @endforeach
                        
                        <th class="px-6 py-3.5 font-bold text-center border-l border-slate-200 bg-yellow-50/50 text-slate-900 min-w-[100px]">Rata-rata</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kelas->mahasiswas as $index => $mhs)
                        @php 
                            $studentScores = []; 
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-4 py-4 text-center font-medium text-slate-400 font-mono">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $mhs->nama }}</div>
                                <div class="text-[10px] font-mono text-slate-400 mt-0.5">{{ $mhs->nim }}</div>
                            </td>

                            @foreach($kelas->assignments as $task)
                                @php
                                    $submissionGroup = $submissions[$mhs->id][$task->id] ?? null;
                                    $record = $submissionGroup ? $submissionGroup->first() : null;
                                    $nilai = ($record && $record->nilai !== null) ? $record->nilai : null;
                                    
                                    if($nilai !== null) {
                                        $studentScores[] = $nilai;
                                        $colScores[$task->id][] = $nilai; 
                                    }
                                @endphp
                                <td class="px-4 py-4 text-center border-l border-slate-100 font-mono">
                                    @if($nilai !== null)
                                        <span class="font-bold text-slate-700">{{ $nilai }}</span>
                                    @else
                                        <span class="text-slate-300">—</span>
                                    @endif
                                </td>
                            @endforeach

                            @php
                                $avgRow = count($studentScores) > 0 ? round(array_sum($studentScores) / count($studentScores), 2) : 0;
                                if(count($studentScores) > 0) $rowAverages[] = $avgRow;
                            @endphp
                            <td class="px-6 py-4 text-center border-l border-slate-200 bg-yellow-50/30 font-mono font-bold text-slate-900">
                                {{ $avgRow }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $kelas->assignments->count() + 3 }}" class="px-6 py-12 text-center text-slate-400 italic">Belum ada mahasiswa atau penugasan praktikum di dalam ruang kelas ini.</td>
                        </tr>
                    @endforelse

                    @if($kelas->mahasiswas->count() > 0 && $kelas->assignments->count() > 0)
                        <tr class="bg-slate-50/30 border-t-2 border-slate-200 font-medium text-slate-700">
                            <td colspan="2" class="px-6 py-3 font-bold text-right uppercase tracking-wider text-[10px] text-slate-400">Nilai Tertinggi</td>
                            @foreach($kelas->assignments as $task)
                                <td class="px-4 py-3 text-center border-l border-slate-100 font-mono font-bold text-blue-600">
                                    {{ count($colScores[$task->id]) > 0 ? max($colScores[$task->id]) : '—' }}
                                </td>
                            @endforeach
                            <td class="px-6 py-3 text-center border-l border-slate-200 bg-yellow-50/40 font-mono font-bold text-blue-600">
                                {{ count($rowAverages) > 0 ? max($rowAverages) : 0 }}
                            </td>
                        </tr>

                        <tr class="bg-slate-50/30 font-medium text-slate-700">
                            <td colspan="2" class="px-6 py-3 font-bold text-right uppercase tracking-wider text-[10px] text-slate-400">Nilai Terendah</td>
                            @foreach($kelas->assignments as $task)
                                <td class="px-4 py-3 text-center border-l border-slate-100 font-mono font-bold text-red-500">
                                    {{ count($colScores[$task->id]) > 0 ? min($colScores[$task->id]) : '—' }}
                                </td>
                            @endforeach
                            <td class="px-6 py-3 text-center border-l border-slate-200 bg-yellow-50/40 font-mono font-bold text-red-500">
                                {{ count($rowAverages) > 0 ? min($rowAverages) : 0 }}
                            </td>
                        </tr>

                        <tr class="bg-slate-100/60 font-bold text-slate-900 border-b border-slate-200">
                            <td colspan="2" class="px-6 py-3.5 font-bold text-right uppercase tracking-wider text-[10px] text-slate-500">Nilai Rata-rata Kelas</td>
                            @foreach($kelas->assignments as $task)
                                @php
                                    $colAvg = count($colScores[$task->id]) > 0 ? round(array_sum($colScores[$task->id]) / count($colScores[$task->id]), 2) : 0;
                                @endphp
                                <td class="px-4 py-3.5 text-center border-l border-slate-200 font-mono font-black text-indigo-600">
                                    {{ $colAvg ?: '—' }}
                                </td>
                            @endforeach
                            @php
                                $totalClassAvg = count($rowAverages) > 0 ? round(array_sum($rowAverages) / count($rowAverages), 2) : 0;
                            @endphp
                            <td class="px-6 py-3.5 text-center border-l border-slate-200 bg-yellow-100/40 font-mono font-black text-amber-700">
                                {{ $totalClassAvg }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
</div>

<style>
    @media print {
        .no-print { display: none !important; }
        body { background: white !important; padding: 0 !important; }
        .break-inside-avoid { page-break-inside: avoid; }
    }
</style>
@endsection