@extends('layouts.mahasiswa')

@section('title', 'Beranda Mahasiswa')
@section('header_title', 'Kelas Saya')

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800">Ruang Kelas LMS Aktif</h2>
        <p class="text-slate-500 text-sm">Selamat datang kembali! Pilih ruang kelas untuk melihat pengumuman, modul materi, dan tugas praktikum.</p>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-xl shadow-sm font-bold text-sm">
            <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($classrooms as $kelas)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col justify-between hover:shadow-md transition-all">
            <div class="p-5 relative" style="background-color: #FFD700;">
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 15px 15px;"></div>
                <span class="bg-slate-900 text-white text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider float-right relative z-10">
                    {{ $kelas->tahun_akademik }}
                </span>
                <div class="relative z-10 mt-2">
                    <h3 class="text-xl font-black text-slate-900 line-clamp-1 mb-1">{{ $kelas->course->nama_mk ?? '-' }}</h3>
                    <p class="text-xs font-bold text-slate-800 opacity-70 font-mono">{{ $kelas->course->kode_mk ?? '-' }}</p>
                </div>
            </div>

            <div class="p-5 flex-1 bg-white flex flex-col justify-between">
                <div class="mb-4 space-y-2">
                    <span class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold uppercase tracking-wide inline-block">
                        Grup: {{ $kelas->nama_kelas }}
                    </span>
                    <p class="text-xs text-slate-500 font-medium">
                        <i class="fas fa-user-tie mr-1.5 text-slate-400"></i> Dosen: {{ $kelas->dosen->nama ?? 'Belum ditentukan' }}
                    </p>
                </div>

                <a href="{{ route('mahasiswa.kelas.show', $kelas->id) }}" class="w-full text-center bg-slate-900 text-white py-2.5 rounded-xl text-xs font-bold hover:bg-slate-800 transition-all block shadow-sm shadow-slate-200">
                    Masuk Kelas LMS <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white border border-slate-200 rounded-2xl p-12 text-center text-slate-400 italic font-medium">
            <i class="fas fa-folder-open text-4xl mb-3 block text-slate-300"></i>
            Anda belum terdaftar di kelas aktif manapun untuk semester ini.
        </div>
        @endforelse
    </div>
@endsection