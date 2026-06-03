@extends('layouts.dosen') 

@section('title', 'Beranda Dosen')
@section('header_title', 'Kelas Saya')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Daftar Kelas LMS Aktif</h2>
            <p class="text-slate-500 text-sm">Pilih ruang kelas untuk mulai mengelola modul, pengumuman, dan tugas praktikum.</p>
        </div>
       
        <button type="button" data-bs-toggle="modal" data-bs-target="#modalBuatKelas" class="text-slate-900 font-bold py-2.5 px-4 rounded-xl shadow-md flex items-center transition-all hover:bg-yellow-500" style="background-color: #FFD700;">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <!-- FIX NO MISTAKE: Backslash pengganggu pada atribut SVG telah dibersihkan -->
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Buat Kelas LMS Manual
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-xl shadow-sm font-bold">
            {{ session('success') }}
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
                <div class="mb-4">
                    <span class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold uppercase tracking-wide">
                        Grup/Rombel: {{ $kelas->nama_kelas }}
                    </span>
                    <p class="text-xs text-slate-400 mt-3 flex items-center">
                        <i class="fas fa-users mr-1.5"></i> {{ $kelas->mahasiswas->count() }} Mahasiswa Terdaftar
                    </p>
                </div>

                <a href="{{ route('dosen.kelas.show', $kelas->id) }}" class="w-full text-center bg-slate-900 text-white py-2.5 rounded-xl text-xs font-bold hover:bg-slate-800 transition-all block shadow-sm shadow-slate-200">
                    Buka Ruang Kelas <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-1 md:col-span-2 lg:col-span-3 bg-white border border-slate-200 rounded-2xl p-12 text-center text-slate-400 italic font-medium">
            <i class="fas fa-folder-open text-4xl mb-3 block text-slate-300"></i>
            Belum ada kelas aktif di semester ini. Silakan buat kelas manual di atas.
        </div>
        @endforelse
    </div>

    <!-- ========================================================= -->
    <!-- REVISI DOSEN: SEKSI MONITORING SPESIAL JABATAN KAPRODI    -->
    <!-- ========================================================= -->
    @if(isset($dosen) && $dosen->jabatan == 'Kaprodi')
    <div class="mt-12 bg-slate-900 rounded-3xl p-6 md:p-8 shadow-xl text-white">
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 bg-amber-500/20 text-amber-400 rounded-xl flex items-center justify-center text-lg border border-amber-500/30 shadow-inner">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black tracking-tight text-white">Akses Kaprodi: Monitoring Dokumen Mutu</h3>
                        <p class="text-slate-400 text-xs mt-0.5">Memantau unggahan berkas Kontrak Kuliah & RPS seluruh dosen internal program studi Anda.</p>
                    </div>
                </div>
                <div class="bg-white/10 px-4 py-2 rounded-2xl border border-white/5 text-right flex flex-col justify-center shrink-0">
                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Tahun Ajaran Sesi</span>
                    <span class="text-xs font-mono font-bold text-amber-400">{{ session('tahun_akademik_nama') ?? 'Sesi Hack' }}</span>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-white/10 bg-white/5">
            <table class="w-full text-left border-collapse text-sm text-slate-300">
                <thead>
                    <tr class="bg-white/5 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-white/10">
                        <th class="px-6 py-4">Mata Kuliah / Ruang Kelas</th>
                        <th class="px-6 py-4">Dosen Pengampu</th>
                        <th class="px-6 py-4 text-center">Status RPS & Kontrak</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($monitoringProdi as $m)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4 font-bold text-white">
                            {{ $m->nama_mk }}
                            <div class="text-[10px] text-blue-400 font-mono font-normal mt-0.5">{{ $m->kode_mk }} - Kelas {{ $m->nama_kelas }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-300 font-medium">{{ $m->nama_dosen }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($m->file_rps)
                                <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-bold border border-green-500/30">
                                    <i class="fas fa-check-circle mr-1"></i> RPS Aman
                                </span>
                            @else
                                <span class="px-3 py-1 bg-rose-500/20 text-rose-400 rounded-full text-xs font-bold border border-rose-500/30 animate-pulse">
                                    <i class="fas fa-times-circle mr-1"></i> Belum Upload RPS
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-slate-500 italic">
                            <i class="fas fa-clipboard-list text-2xl mb-2 text-slate-600 block"></i>
                            Belum ada rombel kelas daring yang berjalan di prodi Anda untuk sesi semester ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- ========================================================= -->
    <!-- REVISI DOSEN: SEKSI AUDIT MUTU GLOBAL JABATAN BPMI        -->
    <!-- ========================================================= -->
    @if(isset($dosen) && $dosen->jabatan == 'BPM')
    <div class="mt-12 space-y-6">
        <!-- CARD STATISTIK GLOBAL BPMI -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Kelas LMS Kampus</p>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $statBpm['total'] }} Kelas</h3>
                </div>
                <div class="h-12 w-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-lg shadow-inner"><i class="fas fa-university"></i></div>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">RPS Terkumpul (Valid)</p>
                    <h3 class="text-2xl font-black text-green-600 mt-1">{{ $statBpm['sudah'] }} Berkas</h3>
                </div>
                <div class="h-12 w-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-lg shadow-inner"><i class="fas fa-file-invoice"></i></div>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">RPS Belum Dilengkapi</p>
                    <h3 class="text-2xl font-black text-rose-600 mt-1">{{ $statBpm['belum'] }} Kelas</h3>
                </div>
                <div class="h-12 w-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center text-lg shadow-inner"><i class="fas fa-exclamation-circle"></i></div>
            </div>
        </div>

        <!-- TABEL KENDALI AUDIT MUTU INTERNAL -->
        <div class="bg-slate-900 text-white rounded-3xl p-6 md:p-8 shadow-xl">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 bg-purple-500/20 text-purple-400 rounded-xl flex items-center justify-center text-lg border border-purple-500/30 shadow-inner">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black tracking-tight text-white">Otoritas BPMI: Audit Mutu Academic Global</h3>
                        <p class="text-slate-400 text-xs mt-0.5">Memantau standarisasi capaian pembelajaran daring di seluruh program studi Polsa.</p>
                    </div>
                </div>
                <div class="bg-purple-500/10 text-purple-300 border border-purple-500/20 px-3.5 py-1.5 rounded-full text-xs font-bold font-mono">
                    <i class="fas fa-eye mr-1"></i> Mode Super-Audit
                </div>
            </div>

            <div class="overflow-x-auto rounded-2xl border border-white/10 bg-white/5">
                <table class="w-full text-left border-collapse text-sm text-slate-300">
                    <thead>
                        <tr class="bg-white/5 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-white/10">
                            <th class="px-6 py-4">Program Studi</th>
                            <th class="px-6 py-4">Mata Kuliah / Ruang Kelas</th>
                            <th class="px-6 py-4">Dosen Pengampu</th>
                            <th class="px-6 py-4 text-center">Status Audit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($auditBpmGlobal as $b)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4">
                                <span class="bg-purple-500/20 text-purple-300 border border-purple-500/30 text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase">
                                    {{ $b->nama_prodi }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-white">
                                {{ $b->nama_mk }}
                                <div class="text-[10px] text-blue-400 font-mono font-normal mt-0.5">{{ $b->kode_mk }} - Kelas {{ $b->nama_kelas }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-300 font-medium">{{ $b->nama_dosen }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($b->file_rps)
                                    <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-bold border border-green-500/30">
                                        <i class="fas fa-shield-check mr-1"></i> Sesuai Standar
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-rose-500/20 text-rose-400 rounded-full text-xs font-bold border border-rose-500/30 animate-pulse">
                                        <i class="fas fa-chart-bar mr-1"></i> Perlu Audit
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500 italic">
                                <i class="fas fa-folder-open text-2xl mb-2 text-slate-600 block"></i>
                                Belum ada kelas perkuliahan aktif yang terdata di sistem kampus semester ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL BUAT KELAS MANUAl -->
    <div class="modal fade" id="modalBuatKelas" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3xl border-none shadow-2xl">
                <div class="modal-header border-none p-6 pb-0">
                    <h5 class="text-xl font-bold text-slate-800">Rakit Kelas LMS Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('dosen.kelas.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-6">
                        <div class="alert alert-info text-xs bg-blue-50 text-blue-800 border-0 rounded-xl mb-4 font-medium">
                            <i class="fas fa-info-circle mr-1"></i> Kelas LMS ini akan berjalan secara online & asinkronus (bebas ikatan hari/jam fisik).
                        </div>
                        
                        <div class="mb-4">
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Mata Kuliah Induk</label>
                            <select name="course_id" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500" required>
                                <option value="">-- Pilih Matakuliah --</option>
                                @foreach($courses as $c)
                                    <option value="{{ $c->id }}">{{ $c->nama_mk }} ({{ $c->kode_mk }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Nama Kelas LMS / Kode Grup</label>
                            <input type="text" name="nama_kelas" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Contoh: TI 4A - Praktikum" required>
                        </div>

                        <div class="mb-2">
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Tahun Akademik</label>
                            <input type="text" name="tahun_akademik" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500" value="2025/2026" required>
                        </div>
                    </div>
                    <div class="modal-footer border-none p-6 pt-0 flex gap-2">
                        <button type="button" class="flex-1 py-3 text-slate-500 font-bold hover:bg-slate-50 rounded-xl" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="flex-1 bg-slate-900 text-white py-3 rounded-xl font-bold hover:bg-slate-800 transition-all">Terbitkan Kelas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection