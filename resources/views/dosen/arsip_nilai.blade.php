@extends('layouts.dosen')

@section('title', 'Arsip Nilai')
@section('header_title', 'Arsip & Rekapitulasi Nilai')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center no-print">
        <h2 class="text-xl font-bold text-slate-800">Daftar Rekapitulasi Matakuliah</h2>
        <div class="flex gap-2">
            <button onclick="window.print()" class="bg-white border border-slate-300 text-slate-700 px-4 py-2 rounded-lg text-sm font-bold shadow-sm hover:bg-slate-50 transition-all flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" stroke-width="2"/></svg>
                Cetak PDF
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($courses as $course)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden break-inside-avoid">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">{{ $course->course_code }}</span>
                        <h3 class="text-lg font-black text-slate-900">{{ $course->course_name }}</h3>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-400">Total Mahasiswa</p>
                        <p class="text-lg font-bold text-slate-800">{{ $course->students->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <p class="text-[10px] text-slate-400 uppercase font-bold">Tugas Dibuat</p>
                        <p class="text-xl font-black text-slate-800">{{ $course->assignments->count() }}</p>
                    </div>
                    <div class="bg-yellow-50 p-3 rounded-xl border border-yellow-100">
                        <p class="text-[10px] text-yellow-600 uppercase font-bold">Rata-rata Kelas</p>
                        <p class="text-xl font-black text-yellow-700">85.4</p> </div>
                </div>

                <div class="flex gap-2 no-print">
                    <a href="{{ route('dosen.kelas.show', ['id' => $course->id, 'tab' => 'nilai']) }}" class="flex-1 text-center bg-slate-900 text-white py-2 rounded-lg text-xs font-bold hover:bg-slate-800 transition-all">
                        Lihat Detail Nilai
                    </a>
                    <a href="{{ route('dosen.export.excel', $course->id) }}" class="bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="2"/></svg>
                        Ekspor Excel
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
    /* CSS Khusus Print agar tampilan rapi di kertas A4 */
    @media print {
        .no-print, .sidebar, .navbar { display: none !important; }
        .content { margin: 0 !important; padding: 0 !important; }
        body { background: white !important; }
        .break-inside-avoid { page-break-inside: avoid; }
    }
</style>
@endsection