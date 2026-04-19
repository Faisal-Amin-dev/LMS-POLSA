@extends('layouts.dosen')

@section('title', 'Ruang Kelas')
@section('header_title', 'Ruang Kelas Praktikum')

@section('content')
    <div class="relative rounded-2xl overflow-hidden shadow-sm mb-6 h-36 sm:h-48 flex items-end p-4 sm:p-6" style="background-color: #FFD700;">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 20px 20px;"></div>
        <div class="relative z-10 w-full flex justify-between items-end">
            <div class="text-slate-900">
                <h1 class="text-xl sm:text-3xl font-bold mb-1">{{ $kelas->name ?? 'Mata Kuliah' }}</h1>
                <p class="text-sm sm:text-lg font-medium">Kelas: {{ $kelas->id }}</p>
            </div>
            <div class="hidden md:block">
                <span class="bg-slate-900 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                    Aktif
                </span>
            </div>
        </div>
    </div>

    <div class="bg-white border-b border-slate-200 mb-6 sticky top-0 z-20 overflow-x-auto no-scrollbar">
        <nav class="flex space-x-6 sm:space-x-8 px-4 sm:px-6" aria-label="Tabs">
            @php
                $tabs = [
                    'beranda' => 'Beranda',
                    'materi' => 'Materi & Modul',
                    'tugas' => 'Tugas Praktikum',
                    'anggota' => 'Anggota',
                    'nilai' => 'Buku Nilai'
                ];
            @endphp
            @foreach($tabs as $key => $label)
                <a href="?tab={{ $key }}" 
                   class="{{ $activeTab == $key ? 'border-slate-900 text-slate-900 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700' }} border-b-4 whitespace-nowrap py-4 px-1 text-sm transition-colors">
                    {{ $label }}
                </a>
            @endforeach
        </nav>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="hidden lg:block lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <h3 class="font-bold text-slate-800 mb-3 text-sm">Informasi Kelas</h3>
                <div class="space-y-3">
                    <div class="flex items-center text-xs text-slate-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2"/></svg>
                        32 Mahasiswa Terdaftar
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3">

            @if($activeTab == 'beranda')
                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
                        <form action="{{ route('dosen.announcement.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $kelas->id }}">
                            <div class="flex items-start gap-3">
                                <div class="hidden sm:block shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-slate-800 flex items-center justify-center text-white font-bold text-sm">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <textarea name="content" rows="1" class="w-full border-none focus:ring-0 text-sm sm:text-base placeholder:text-slate-400 resize-none p-2" placeholder="Umumkan sesuatu ke kelas Anda..." required></textarea>
                                    <div class="flex justify-end mt-3 pt-3 border-t border-slate-100">
                                        <button type="submit" class="text-slate-900 font-bold py-2 px-6 rounded-lg text-sm transition-colors" style="background-color: #FFD700;">
                                            Bagikan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="space-y-4">
                        @forelse($announcements as $info)
                            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 sm:p-5">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 text-xs font-bold">{{ substr($info->user->name, 0, 1) }}</div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-800">{{ $info->user->name }}</h4>
                                        <p class="text-[10px] text-slate-400 uppercase tracking-tighter">{{ $info->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <p class="text-slate-700 text-sm sm:text-base whitespace-pre-line leading-relaxed">{{ $info->content }}</p>
                            </div>
                        @empty
                            <div class="text-center py-10 bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl">
                                <p class="text-slate-400 text-sm italic">Belum ada pengumuman untuk kelas ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            @elseif($activeTab == 'materi')
                <div>
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                        <div>
                            <h2 class="text-xl font-bold text-slate-800">Modul Praktikum</h2>
                            <p class="text-sm text-slate-500">Materi referensi untuk mahasiswa.</p>
                        </div>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#modalTambahMateri" class="w-full sm:w-auto text-slate-900 font-bold py-2 px-4 rounded-lg shadow-sm flex justify-center items-center" style="background-color: #FFD700;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Posting Materi Baru
                        </button>
                    </div>

                    <div class="space-y-4">
                        @forelse($materials as $materi)
                            <div class="bg-white border border-slate-200 p-4 sm:p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow flex flex-col sm:flex-row gap-4 sm:items-center justify-between">
                                <div class="flex items-start gap-4">
                                    <div class="{{ $materi->file_path ? 'bg-red-50 text-red-500' : 'bg-blue-50 text-blue-500' }} p-3 rounded-lg shrink-0">
                                        @if($materi->file_path)
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-width="2"/></svg>
                                        @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" stroke-width="2"/></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-slate-800 text-base sm:text-lg">{{ $materi->title }}</h3>
                                        <p class="text-sm text-slate-500 mt-1">{{ $materi->description ?? 'Tidak ada deskripsi' }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    @if($materi->file_path)
                                        <a href="{{ Storage::url('materials/' . $materi->file_path) }}" download class="w-full sm:w-auto text-center border border-slate-300 text-slate-700 py-2 px-4 rounded-lg text-sm font-semibold">Unduh PDF</a>
                                    @endif
                                    @if($materi->link_url)
                                        <a href="{{ $materi->link_url }}" target="_blank" class="w-full sm:w-auto text-center bg-slate-900 text-white py-2 px-4 rounded-lg text-sm font-semibold">Buka Tautan</a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50">
                                <p class="text-slate-500 text-sm">Belum ada modul diupload.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            @elseif($activeTab == 'tugas')
                <div>
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                        <div>
                            <h2 class="text-xl font-bold text-slate-800">Tugas Praktikum</h2>
                            <p class="text-sm text-slate-500">Kelola penugasan dan pantau pengumpulan mahasiswa.</p>
                        </div>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#modalTambahTugas" class="w-full sm:w-auto text-slate-900 font-bold py-2 px-4 rounded-lg shadow-sm flex justify-center items-center" style="background-color: #FFD700;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Buat Tugas Baru
                        </button>
                    </div>

                    <div class="space-y-4">
                        @forelse($assignments as $tugas)
                            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                <div class="p-4 sm:p-5 flex flex-col sm:flex-row gap-4 justify-between items-start">
                                    <div class="flex items-start gap-4">
                                        <div class="bg-slate-100 text-slate-600 p-3 rounded-lg shrink-0">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-width="2" stroke-linecap="round"/></svg>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-slate-800 text-base sm:text-lg">{{ $tugas->title }}</h3>
                                            <p class="text-xs text-red-500 font-bold uppercase tracking-wider mb-2">
                                                Tenggat: {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y, H:i') }} WIB
                                            </p>
                                            <p class="text-sm text-slate-500 line-clamp-2">{{ $tugas->description }}</p>
                                        </div>
                                    </div>
                                    <div class="w-full sm:w-auto flex flex-row sm:flex-col gap-2 items-center sm:items-end justify-between border-t sm:border-t-0 pt-3 sm:pt-0 mt-2 sm:mt-0">
                                        <div class="text-left sm:text-right">
                                            <p class="text-2xl font-black text-slate-800">0/32</p>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase">Diserahkan</p>
                                        </div>
                                        <a href="#" class="bg-slate-900 text-white text-xs font-bold py-2 px-4 rounded-lg hover:bg-slate-800 transition-colors">
                                            Periksa Tugas
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12 bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl">
                                <p class="text-slate-400 text-sm italic">Belum ada tugas praktikum yang dibuat.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

           
            @elseif($activeTab == 'anggota')
                <div class="space-y-8 animate-fadeIn">
                    
                    <section>
                        <div class="flex justify-between items-center border-b border-slate-900 pb-2 mb-4">
                            <h2 class="text-2xl font-bold text-slate-900">Pengajar</h2>
                        </div>
                        <div class="flex items-center gap-4 p-2">
                            <div class="h-10 w-10 rounded-full bg-slate-800 flex items-center justify-center text-white font-bold">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="font-medium text-slate-800">{{ Auth::user()->name }}</span>
                        </div>
                    </section>

                    <section>
                        <div class="flex justify-between items-center border-b border-slate-900 pb-2 mb-4">
                            <h2 class="text-2xl font-bold text-slate-900">Mahasiswa</h2>
                            <span class="text-sm font-bold text-slate-500">{{ $students->count() }} Siswa</span>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @forelse($students as $mhs)
                                <div class="flex items-center justify-between py-3 px-2 hover:bg-slate-50 transition-colors rounded-lg">
                                    <div class="flex items-center gap-4">
                                        <div class="h-9 w-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-xs font-bold">
                                            {{ substr($mhs->nama, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm sm:text-base font-semibold text-slate-800 leading-none mb-1">{{ $mhs->nama }}</p>
                                            <p class="text-[10px] sm:text-xs text-slate-500 font-mono">{{ $mhs->nim }}</p>
                                        </div>
                                    </div>
                                    
                                    <button class="text-slate-400 hover:text-slate-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2" stroke-linecap="round"/></svg>
                                    </button>
                                </div>
                            @empty
                                <div class="text-center py-10">
                                    <p class="text-slate-400 text-sm italic">Belum ada mahasiswa yang bergabung di kelas ini.</p>
                                </div>
                            @endforelse
                        </div>
                    </section>
                </div>
            @endif
           
@endsection

@push('modals')
    <div class="modal fade" id="modalTambahMateri" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0" style="background-color: #FFD700;">
                    <h5 class="modal-title fw-bold text-dark">Posting Materi Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('dosen.materi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $kelas->id }}">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul Materi</label>
                            <input type="text" name="title" class="form-control" placeholder="Contoh: Modul 1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">File (PDF/Video)</label>
                            <input type="file" name="file_path" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Link (YouTube/Drive)</label>
                            <input type="url" name="link_url" class="form-control" placeholder="https://...">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn text-slate-900 font-bold" style="background-color: #FFD700;">Posting</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalTambahTugas" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0" style="background-color: #FFD700;">
                    <h5 class="modal-title fw-bold text-dark">Buat Penugasan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('dosen.assignment.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $kelas->id }}">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul Tugas</label>
                            <input type="text" name="title" class="form-control rounded-3" placeholder="Contoh: Laporan Praktikum Modul 1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Instruksi Tugas</label>
                            <textarea name="description" class="form-control rounded-3" rows="4" placeholder="Tuliskan detail apa yang harus dikerjakan mahasiswa..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tenggat Waktu (Deadline)</label>
                            <input type="datetime-local" name="deadline" class="form-control rounded-3" required>
                            <div class="form-text text-xs text-slate-400 mt-1 italic">Mahasiswa tetap bisa mengumpulkan setelah waktu ini, namun akan ditandai "Terlambat".</div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-slate-50">
                        <button type="button" class="btn border-slate-300 text-slate-600 rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn text-slate-900 font-bold rounded-pill px-4" style="background-color: #FFD700;">Tugaskan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush