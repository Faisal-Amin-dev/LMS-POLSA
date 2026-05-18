@extends('layouts.dosen') 

@section('title', 'Kelas Diarsip')
@section('header_title', 'Arsip Kuliah')

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-700"><i class="fas fa-archive mr-2 text-slate-400"></i>Kelas LMS Diarsip</h2>
        <p class="text-slate-500 text-sm">Daftar kelas dari semester terdahulu. Kelas diarsip bersifat *read-only* (hanya dapat dilihat nilainya).</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($classrooms as $kelas)
        <div class="bg-slate-50 rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col justify-between opacity-75 hover:opacity-100 transition-all">
            <div class="p-5 bg-slate-300 relative">
                <span class="bg-slate-700 text-white text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider float-right">
                    {{ $kelas->tahun_akademik }}
                </span>
                <div class="mt-2">
                    <h3 class="text-xl font-black text-slate-800 truncate mb-1">{{ $kelas->course->nama_mk ?? '-' }}</h3>
                    <p class="text-xs font-bold text-slate-600 font-mono">{{ $kelas->course->kode_mk ?? '-' }}</p>
                </div>
            </div>

            <div class="p-5 flex-1 bg-white flex flex-col justify-between">
                <div class="mb-4">
                    <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold uppercase">
                        Grup: {{ $kelas->nama_kelas }}
                    </span>
                    <p class="text-xs text-slate-400 mt-3">
                        <i class="fas fa-archive mr-1"></i> Kelas ini telah resmi ditutup oleh Akademik.
                    </p>
                </div>

                <a href="{{ route('dosen.kelas.show', $kelas->id) }}" class="w-full text-center bg-slate-600 text-white py-2.5 rounded-xl text-xs font-bold hover:bg-slate-700 transition-all block">
                    Lihat Rekap Nilai & Materi <i class="fas fa-search ml-1"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white border border-slate-200 rounded-2xl p-12 text-center text-slate-400 italic font-medium">
            <i class="fas fa-box-open text-4xl mb-3 block text-slate-300"></i>
            Belum ada arsip kelas dari semester lama.
        </div>
        @endforelse
    </div>
@endsection