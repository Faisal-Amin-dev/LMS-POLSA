@extends('layouts.mahasiswa')

@section('title', 'Agenda Kuliah')
@section('header_title', 'Tugas Praktikum Mendatang')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-xl font-bold text-slate-800"><i class="fas fa-hourglass-half text-amber-500 mr-2"></i>Agenda & Batas Waktu Tugas Kuliah</h2>
            <p class="text-sm text-slate-500">Daftar lembar kerja praktikum aktif yang diurutkan berdasarkan sisa waktu pengerjaan terdekat.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-100 bg-slate-50/30">
                        <th class="px-6 py-4">Mata Kuliah</th>
                        <th class="px-6 py-4">Judul Tugas Praktikum</th>
                        <th class="px-6 py-4">Tenggat Waktu (Deadline)</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($agenda as $task)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="block font-bold text-slate-800 text-sm">{{ $task->classroom->course->nama_mk ?? '-' }}</span>
                            <span class="text-[10px] text-blue-600 font-bold bg-blue-50 px-2 py-0.5 rounded-md uppercase inline-block mt-1">
                                Rombel: {{ $task->classroom->nama_kelas ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-700 text-xs">
                            {{ $task->title }}
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600 font-mono font-bold text-red-500">
                            <i class="far fa-clock text-slate-400 mr-1.5"></i>
                            {{ \Carbon\Carbon::parse($task->deadline)->translatedFormat('d M Y - H:i') }} WIB
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('mahasiswa.kelas.show', ['id' => $task->classroom_id, 'tab' => 'tugas']) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 text-xs font-bold transition-all bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-xl">
                                Kerjakan Tugas <i class="fas fa-chevron-right ml-1.5 text-[10px]"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center text-slate-400 italic font-medium">
                            <div class="text-3xl text-slate-300 mb-2"><i class="fas fa-calendar-check"></i></div>
                            Bebas hambatan! Tidak ada agenda tugas praktikum aktif dalam waktu dekat ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection