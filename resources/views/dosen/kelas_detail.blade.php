@extends('layouts.dosen')

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
            <div class="hidden md:block">
                <span class="bg-slate-900 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
                    Tahun Akademik: {{ $kelas->tahun_akademik }}
                </span>
            </div>
        </div>
    </div>

    <div class="bg-white border-b border-slate-200 mb-6 sticky top-0 z-20 overflow-x-auto">
        <nav class="flex space-x-8 px-4" aria-label="Tabs">
            <a href="?tab=beranda" class="border-b-2 py-4 px-1 text-sm font-bold transition-all {{ $activeTab == 'beranda' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-400 hover:text-slate-600 hover:border-slate-300' }}">Beranda</a>
            <a href="?tab=materi" class="border-b-2 py-4 px-1 text-sm font-bold transition-all {{ $activeTab == 'materi' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-400 hover:text-slate-600 hover:border-slate-300' }}">Modul Materi</a>
            <a href="?tab=tugas" class="border-b-2 py-4 px-1 text-sm font-bold transition-all {{ $activeTab == 'tugas' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-400 hover:text-slate-600 hover:border-slate-300' }}">Tugas Praktikum</a>
            <a href="?tab=anggota" class="border-b-2 py-4 px-1 text-sm font-bold transition-all {{ $activeTab == 'anggota' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-400 hover:text-slate-600 hover:border-slate-300' }}">Anggota Kelas</a>
            <a href="?tab=nilai" class="border-b-2 py-4 px-1 text-sm font-bold transition-all {{ $activeTab == 'nilai' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-400 hover:text-slate-600 hover:border-slate-300' }}">Buku Nilai</a>
        </nav>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-xl shadow-sm font-bold text-sm">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif

    <div class="mt-4">
        
        @if($activeTab == 'beranda')
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <div class="lg:col-span-1 hidden lg:block">
                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Info Kelas</h4>
                        <p class="text-xs text-slate-600 font-medium leading-relaxed">Gunakan ruang ini untuk berkomunikasi dua arah secara asinkronus bersama mahasiswa.</p>
                    </div>
                </div>
                <div class="lg:col-span-3 space-y-4">
                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                        <form action="{{ route('dosen.announcement.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="classroom_id" value="{{ $kelas->id }}">
                            <textarea name="content" rows="2" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-sm outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="Umumkan sesuatu kepada mahasiswa di ruang kelas ini..." required></textarea>
                            <div class="flex justify-end mt-2">
                                <button type="submit" class="bg-slate-900 text-white font-bold text-xs px-4 py-2 rounded-xl hover:bg-slate-800 transition-all">Posting Pengumuman</button>
                            </div>
                        </form>
                    </div>

                    @forelse($announcements as $a)
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex gap-4 hover:border-slate-300 transition-all">
                        <div class="h-10 w-10 bg-amber-100 text-amber-700 rounded-full flex items-center justify-center flex-shrink-0 font-bold"><i class="fas fa-bullhorn"></i></div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold text-slate-800 text-sm">Dosen Pengampu</span>
                                <span class="text-[10px] text-slate-400">{{ $a->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-slate-600 leading-relaxed">{{ $a->content }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400 italic text-xs">
                        Belum ada untaian pengumuman di beranda kelas ini.
                    </div>
                    @endforelse
                </div>
            </div>

        @elseif($activeTab == 'materi')
            <div class="max-w-4xl space-y-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-slate-800">Modul Kuliah & Referensi</h3>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modalUploadMateri" class="bg-slate-900 text-white font-bold text-xs px-4 py-2.5 rounded-xl hover:bg-slate-800 transition-all flex items-center shadow-sm">
                        <i class="fas fa-upload mr-2"></i> Unggah Modul Baru
                    </button>
                </div>

                @forelse($materials as $m)
                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex justify-between items-center hover:border-slate-300 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="h-11 w-11 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-lg"><i class="fas fa-file-pdf"></i></div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm mb-0.5">{{ $m->title }}</h4>
                            <p class="text-[11px] text-slate-400">Diupload {{ $m->created_at->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $m->file_path) }}" target="_blank" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-2 rounded-xl hover:bg-blue-100 transition-all">
                        <i class="fas fa-download mr-1"></i> Unduh Modul
                    </a>
                </div>
                @empty
                <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400 italic text-sm">
                    <i class="fas fa-book text-3xl text-slate-200 block mb-2"></i> Belum ada materi kuliah yang diunggah.
                </div>
                @endforelse
            </div>

        @elseif($activeTab == 'tugas')
            <div class="max-w-4xl space-y-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-slate-800">Daftar Lembar Kerja Praktikum</h3>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modalBuatTugas" class="bg-slate-900 text-white font-bold text-xs px-4 py-2.5 rounded-xl hover:bg-slate-800 transition-all flex items-center shadow-sm">
                        <i class="fas fa-plus mr-2"></i> Rilis Tugas Baru
                    </button>
                </div>

                @forelse($assignments as $task)
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:border-slate-300 transition-all">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center gap-4">
                            <div class="h-11 w-11 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-lg"><i class="fas fa-clipboard-list"></i></div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm mb-0.5">{{ $task->title }}</h4>
                                <p class="text-[11px] font-mono text-red-500 font-bold">Deadline: {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y, H:i') }} WIB</p>
                            </div>
                        </div>
                        <a href="?tab=nilai" class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-2 rounded-xl hover:bg-indigo-100 transition-all">
                            Periksa Pengumpulan <i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-100">{{ $task->description }}</p>
                </div>
                @empty
                <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400 italic text-sm">
                    <i class="fas fa-tasks text-3xl text-slate-200 block mb-2"></i> Belum ada tugas praktikum yang dirilis untuk mahasiswa.
                </div>
                @endforelse
            </div>

        @elseif($activeTab == 'anggota')
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden max-w-2xl">
                <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 text-sm">Daftar Mahasiswa Terdaftar</h3>
                    <span class="bg-indigo-50 text-indigo-600 text-xs font-bold px-2.5 py-1 rounded-lg">{{ $students->count() }} Orang</span>
                </div>
                <div class="divide-y divide-slate-100 max-h-96 overflow-y-auto">
                    @forelse($students as $s)
                    <div class="p-4 flex items-center gap-3 hover:bg-slate-50 transition-all">
                        <div class="h-9 w-9 bg-slate-100 text-slate-600 font-bold text-xs rounded-full flex items-center justify-center uppercase">{{ substr($s->nama, 0, 2) }}</div>
                        <div>
                            <p class="text-sm font-bold text-slate-800 mb-0.5">{{ $s->nama }}</p>
                            <p class="text-[11px] font-mono text-slate-400">{{ $s->nim }} | Kelas Asal: {{ $s->kelas }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="p-6 text-center text-slate-400 italic text-xs">Belum ada mahasiswa di kelas ini. Daftarkan rombel lewat dashboard Admin.</p>
                    @endforelse
                </div>
            </div>

        @elseif($activeTab == 'nilai')
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Matriks Lembar Penilaian Praktikum</h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">Nilai real-time terekam berdasarkan tugas yang dikumpulkan mahasiswa.</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100 text-slate-500 font-bold">
                                <th class="px-6 py-4 font-bold">NIM & Mahasiswa</th>
                                @foreach($assignments as $task)
                                    <th class="px-6 py-4 font-bold text-center border-l border-slate-100">{{ Str::limit($task->title, 15) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($students as $s)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800">{{ $s->nama }}</div>
                                    <div class="text-[10px] font-mono text-slate-400 mt-0.5">{{ $s->nim }}</div>
                                </td>
                                @foreach($assignments as $task)
                                    @php
                                        // Membaca map submission bertingkat dari Controller
                                        $submission = $submissions[$s->id][$task->id] ?? null;
                                        $record = $submission ? $submission->first() : null;
                                    @endphp
                                    <td class="px-6 py-4 text-center border-l border-slate-100 font-mono">
                                        @if($record && $record->nilai)
                                            <span class="text-green-600 font-bold text-sm">{{ $record->nilai }}</span>
                                        @elseif($record)
                                            <span class="text-amber-500 font-bold text-[10px] bg-amber-50 px-2 py-1 rounded">Belum Dinilai</span>
                                        @else
                                            <span class="text-slate-300 font-bold">—</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ count($assignments) + 1 }}" class="px-6 py-12 text-center text-slate-400 italic">Belum ada peserta kelas untuk dinilai.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>

    <div class="modal fade" id="modalUploadMateri" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3xl border-none shadow-2xl">
                <div class="modal-header border-none p-6 pb-0">
                    <h5 class="text-lg font-bold text-slate-800">Unggah Modul Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('dosen.materi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="classroom_id" value="{{ $kelas->id }}">
                    <div class="modal-body p-6">
                        <div class="mb-4">
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Judul Modul Materi</label>
                            <input type="text" name="title" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Misal: Pertemuan 1 - Pengenalan Sintaks" required>
                        </div>
                        <div class="mb-2">
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Berkas File (PDF/Docx/Zip - Maks 20MB)</label>
                            <input type="file" name="file_materi" class="w-full bg-white border border-slate-300 px-4 py-2 rounded-xl text-xs" required>
                        </div>
                    </div>
                    <div class="modal-footer border-none p-6 pt-0 flex gap-2">
                        <button type="button" class="flex-1 py-2.5 text-slate-500 font-bold hover:bg-slate-50 rounded-xl text-xs" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="flex-1 bg-slate-900 text-white py-2.5 rounded-xl font-bold hover:bg-slate-800 transition-all text-xs">Simpan Modul</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalBuatTugas" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3xl border-none shadow-2xl">
                <div class="modal-header border-none p-6 pb-0">
                    <h5 class="text-lg font-bold text-slate-800">Rilis Tugas Praktikum Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('dosen.assignment.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="classroom_id" value="{{ $kelas->id }}">
                    <div class="modal-body p-6">
                        <div class="mb-4">
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Judul Tugas</label>
                            <input type="text" name="title" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Misal: Praktikum 2 - Membuat Relasi Database" required>
                        </div>
                        <div class="mb-4">
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Instruksi Soal / Deskripsi Kerja</label>
                            <textarea name="description" rows="3" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Tulis rincian tugas dan aturan pengumpulan..." mercantile required></textarea>
                        </div>
                        <div class="mb-2">
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Batas Waktu Pengumpulan (Deadline)</label>
                            <input type="datetime-local" name="deadline" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500" required>
                        </div>
                    </div>
                    <div class="modal-footer border-none p-6 pt-0 flex gap-2">
                        <button type="button" class="flex-1 py-2.5 text-slate-500 font-bold hover:bg-slate-50 rounded-xl text-xs" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="flex-1 bg-slate-900 text-white py-2.5 rounded-xl font-bold hover:bg-slate-800 transition-all text-xs">Rilis Tugas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection