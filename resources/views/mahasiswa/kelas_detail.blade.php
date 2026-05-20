@extends('layouts.mahasiswa')

@section('title', 'Ruang Kelas LMS')
@section('header_title', 'Detail Kelas Kuliah')

@section('content')
    <div class="relative rounded-2xl overflow-hidden shadow-sm mb-6 h-36 sm:h-40 flex items-end p-4 sm:p-6" style="background-color: #FFD700;">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 20px 20px;"></div>
        <div class="relative z-10 w-full flex justify-between items-end">
            <div class="text-slate-900">
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 mb-1">{{ $kelas->course->nama_mk ?? '-' }}</h2>
                <p class="text-xs sm:text-sm font-bold text-slate-800 opacity-80"> Kode Matkul: {{ $kelas->course->kode_mk ?? '-' }} | Rombel: {{ $kelas->nama_kelas }}</p>
            </div>
            <div class="hidden md:block text-right">
                <span class="bg-slate-900 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm block mb-1">
                    Dosen Pengampu
                </span>
                <p class="text-xs font-bold text-slate-800 truncate max-w-xs">{{ $kelas->dosen->nama ?? '-' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white border-b border-slate-200 mb-6 sticky top-0 z-20 overflow-x-auto">
        <nav class="flex space-x-8 px-4" aria-label="Tabs">
            <a href="?tab=beranda" class="border-b-2 py-4 px-1 text-sm font-bold transition-all {{ $activeTab == 'beranda' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-400 hover:text-slate-600 hover:border-slate-300' }}">Beranda</a>
            <a href="?tab=materi" class="border-b-2 py-4 px-1 text-sm font-bold transition-all {{ $activeTab == 'materi' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-400 hover:text-slate-600 hover:border-slate-300' }}">Modul Materi</a>
            <a href="?tab=tugas" class="border-b-2 py-4 px-1 text-sm font-bold transition-all {{ $activeTab == 'tugas' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-400 hover:text-slate-600 hover:border-slate-300' }}">Tugas Praktikum</a>
            <a href="?tab=anggota" class="border-b-2 py-4 px-1 text-sm font-bold transition-all {{ $activeTab == 'anggota' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-400 hover:text-slate-600 hover:border-slate-300' }}">Anggota Kelas</a>
        </nav>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-xl shadow-sm font-bold text-sm">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-xl shadow-sm font-bold text-sm">
            <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
        </div>
    @endif

    <div class="mt-4">
        @if($activeTab == 'beranda')
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <div class="lg:col-span-1 hidden lg:block">
                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm text-xs text-slate-500 leading-relaxed">
                        <h4 class="font-bold text-slate-700 uppercase mb-2">Papan Aliran Kelas</h4>
                        Pantau instruksi terbaru, perubahan materi, dan rilis tugas praktikum langsung dari dosen pengampu Anda melalui linimasa ini.
                    </div>
                </div>
                <div class="lg:col-span-3 space-y-4">
                    @forelse($announcements as $a)
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex gap-4">
                        <div class="h-10 w-10 bg-amber-100 text-amber-700 rounded-full flex items-center justify-center flex-shrink-0 font-bold"><i class="fas fa-bullhorn"></i></div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold text-slate-800 text-sm">Dosen Pengampu</span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $a->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-slate-600 leading-relaxed">{{ $a->content }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400 italic text-xs">
                        Belum ada pengumuman resmi dari dosen pengampu di beranda kelas ini.
                    </div>
                    @endforelse
                </div>
            </div>
        @endif

        @if($activeTab == 'materi')
            <div class="max-w-4xl space-y-4">
                <h3 class="text-base font-bold text-slate-800 mb-2">Berkas Materi & Modul Kuliah</h3>
                @forelse($materials as $m)
                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex justify-between items-center hover:border-slate-300 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="h-11 w-11 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-lg"><i class="fas fa-file-pdf"></i></div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm mb-0.5">{{ $m->title }}</h4>
                            <p class="text-[11px] text-slate-400 font-mono">Diterbitkan pada {{ $m->created_at->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $m->file_path) }}" target="_blank" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-2 rounded-xl hover:bg-blue-100 transition-all">
                        <i class="fas fa-download mr-1"></i> Unduh Berkas
                    </a>
                </div>
                @empty
                <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400 italic text-sm">
                    <i class="fas fa-book text-3xl text-slate-200 block mb-2"></i> Dosen belum mengunggah modul materi kuliah.
                </div>
                @endforelse
            </div>
        @endif

        @if($activeTab == 'tugas')
            <div class="max-w-4xl space-y-4">
                <h3 class="text-base font-bold text-slate-800 mb-2">Lembar Kerja Praktikum Mahasiswa</h3>
                @forelse($assignments as $task)
                @php
                    $submission = $mySubmissions->get($task->id);
                @endphp
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:border-slate-300 transition-all">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                        <div class="flex items-center gap-4">
                            <div class="h-11 w-11 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-lg"><i class="fas fa-clipboard-list"></i></div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm mb-0.5">{{ $task->title }}</h4>
                                <p class="text-[11px] font-mono text-red-500 font-bold">Batas Waktu: {{ \Carbon\Carbon::parse($task->deadline)->translatedFormat('d M Y, H:i') }} WIB</p>
                            </div>
                        </div>

                        <div>
                            @if($submission)
                                @if($submission->grade !== null)
                                    <span class="text-xs font-bold bg-green-100 text-green-700 px-3 py-1.5 rounded-xl block text-center">Nilai: <span class="font-mono text-sm">{{ $submission->grade }}</span></span>
                                @else
                                    <span class="text-xs font-bold bg-blue-100 text-blue-700 px-3 py-1.5 rounded-xl block text-center"><i class="fas fa-check mr-1"></i> Sudah Dikumpul</span>
                                @endif
                            @else
                                <span class="text-xs font-bold bg-amber-100 text-amber-700 px-3 py-1.5 rounded-xl block text-center"><i class="far fa-clock mr-1"></i> Belum Mengumpulkan</span>
                            @endif
                        </div>
                    </div>

                    <div class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100 mb-4">
                        <span class="block font-bold text-slate-700 mb-1 uppercase tracking-wide text-[9px]">Instruksi Kerja:</span>
                        {{ $task->description }}
                    </div>

                    @if(!$submission)
                    <form action="{{ route('mahasiswa.submission.store') }}" method="POST" enctype="multipart/form-data" class="border-t border-slate-100 pt-4 flex flex-col sm:flex-row items-end sm:items-center justify-between gap-4">
                        @csrf
                        <input type="hidden" name="assignment_id" value="{{ $task->id }}">
                        <input type="hidden" name="classroom_id" value="{{ $kelas->id }}">
                        
                        <div class="w-full sm:max-w-md">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Unggah Lembar Jawaban (PDF/ZIP/RAR - Maks 20MB)</label>
                            <input type="file" name="file_jawaban" class="w-full bg-white border border-slate-200 rounded-xl p-1.5 text-xs outline-none focus:border-indigo-500" required>
                        </div>
                        <button type="submit" class="w-full sm:w-auto bg-slate-900 text-white font-bold text-xs px-5 py-2.5 rounded-xl hover:bg-slate-800 shadow-sm transition-all">
                            <i class="fas fa-paper-plane mr-1.5"></i> Serahkan Tugas
                        </button>
                    </form>
                    @else
                    <div class="border-t border-slate-100 pt-3 text-[11px] text-slate-400 flex items-center justify-between font-mono">
                        <span><i class="fas fa-paperclip mr-1"></i> File Anda: <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="text-blue-500 underline hover:text-blue-700">Lihat Jawaban</a></span>
                        <span>Dikumpulkan pada: {{ \Carbon\Carbon::parse($submission->submitted_at)->translatedFormat('d M Y - H:i') }} WIB</span>
                    </div>
                    @endif
                </div>
                @empty
                <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400 italic text-sm">
                    <i class="fas fa-tasks text-3xl text-slate-200 block mb-2"></i> Bersih! Tidak ada tugas praktikum yang dirilis untuk Anda saat ini.
                </div>
                @endforelse
            </div>
        @endif

        @if($activeTab == 'anggota')
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden max-w-2xl">
                <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 text-sm">Daftar Rekan Sejawat Rombel</h3>
                    <span class="bg-indigo-50 text-indigo-600 text-xs font-bold px-2.5 py-1 rounded-lg">{{ $students->count() }} Mahasiswa</span>
                </div>
                <div class="divide-y divide-slate-100 max-h-96 overflow-y-auto">
                    @foreach($students as $s)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50/50 transition-all">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 bg-slate-100 text-slate-600 font-bold text-xs rounded-full flex items-center justify-center uppercase">{{ substr($s->nama, 0, 2) }}</div>
                            <div>
                                <p class="text-sm font-bold text-slate-800 {{ $s->id == $mahasiswa->id ? 'text-blue-600' : '' }}">{{ $s->nama }} {!! $s->id == $mahasiswa->id ? '<span class="text-[10px] font-normal text-slate-400">(Anda)</span>' : '' !!}</p>
                                <p class="text-[11px] font-mono text-slate-400">{{ $s->nim }}</p>
                            </div>
                        </div>
                        <span class="text-[10px] bg-slate-100 px-2 py-0.5 rounded text-slate-500 font-medium font-mono">Kelas {{ $s->kelas }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection