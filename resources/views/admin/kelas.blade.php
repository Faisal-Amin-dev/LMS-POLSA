@extends('layouts.admin')

@section('content')
<div class="p-4">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Manajemen Kelas & Matkul</h2>
            <p class="text-slate-500 text-sm">Otomatisasi pembentukan kelas dari API SIAP-POLSA atau input manual.</p>
        </div>
        <div class="flex gap-2">
            <form action="{{ route('admin.kelas.sync') }}" method="POST">
                @csrf
                <button type="submit" class="h-11 px-4 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:bg-blue-700 transition-all flex items-center">
                    <i class="fas fa-sync-alt mr-2"></i> Auto-Sync Kelas
                </button>
            </form>
            <button type="button" data-bs-toggle="modal" data-bs-target="#modalTambahMatkul" class="h-11 px-4 bg-white border border-slate-300 text-slate-700 font-bold rounded-xl hover:bg-slate-50 flex items-center">
                <i class="fas fa-book mr-2"></i> Tambah Matkul
            </button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#modalTambahKelas" class="h-11 px-4 bg-slate-800 text-white font-bold rounded-xl shadow-lg hover:bg-slate-700 flex items-center">
                <i class="fas fa-plus mr-2"></i> Buat Kelas Manual
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm font-bold">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm font-bold">
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
            <ul class="list-disc ml-5 text-sm">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <h3 class="text-lg font-bold text-slate-700 mb-3 mt-8"><i class="fas fa-book-open mr-2"></i>Daftar Mata Kuliah Tersedia</h3>
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden mb-8 max-h-64 overflow-y-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-500 text-[10px] font-bold uppercase tracking-wider sticky top-0">
                <tr>
                    <th class="px-6 py-3">Kode Matkul</th>
                    <th class="px-6 py-3">Nama Matkul</th>
                    <th class="px-6 py-3">SKS</th>
                    <th class="px-6 py-3">Semester</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($courses as $c)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-3 text-sm font-bold text-slate-700">{{ $c->kode_mk }}</td>
                    <td class="px-6 py-3 text-sm text-slate-600">{{ $c->nama_mk }}</td>
                    <td class="px-6 py-3 text-sm text-slate-600">{{ $c->sks }}</td>
                    <td class="px-6 py-3 text-sm text-slate-600">SMT {{ $c->semester }}</td>
                    <td class="px-6 py-3 text-center">
                        <div class="flex justify-center gap-2">
                            <button type="button" class="btn-edit-course text-blue-500 hover:text-blue-700" 
                                data-id="{{ $c->id }}" 
                                data-kode="{{ $c->kode_mk }}" 
                                data-nama="{{ $c->nama_mk }}" 
                                data-sks="{{ $c->sks }}" 
                                data-semester="{{ $c->semester }}" 
                                data-prodi="{{ $c->kurikulum_id ?? '' }}"
                                data-bs-toggle="modal" data-bs-target="#modalEditMatkul">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.course.destroy', $c->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600" onclick="return confirm('Hapus matkul ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>

    <h3 class="text-lg font-bold text-slate-700 mb-3"><i class="fas fa-chalkboard-teacher mr-2"></i>Daftar Kelas LMS Aktif (Sesi Berjalan)</h3>
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-500 text-[10px] font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4">Matkul & Kelas</th>
                    <th class="px-6 py-4">Dosen Pengampu</th>
                    <th class="px-6 py-4">Peserta</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($classrooms as $k)
                <tr class="hover:bg-slate-50 transition-all">
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-800">{{ $k->course->nama_mk ?? '-' }}</div>
                        <div class="text-[10px] text-blue-600 font-bold uppercase bg-blue-50 px-2 py-0.5 rounded-md inline-block mt-1">{{ $k->nama_kelas }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 font-medium">
                        {{ $k->dosen->nama ?? 'Belum ada dosen' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold">
                            {{ $k->mahasiswas->count() }} Mahasiswa
                        </span>
                    </td>
                    <!-- UPDATE REVISI: Penambahan Badge Status Aktif Real-Time -->
                    <td class="px-6 py-4 text-center">
                        @if(($k->status ?? 'aktif') == 'aktif')
                            <span class="px-2.5 py-1 bg-green-50 text-green-600 rounded-lg text-[10px] font-black border border-green-100 uppercase tracking-wider">
                                <i class="fas fa-circle text-[8px] mr-1 align-middle"></i> Aktif
                            </span>
                        @else
                            <span class="px-2.5 py-1 bg-slate-100 text-slate-500 rounded-lg text-[10px] font-black border border-slate-200 uppercase tracking-wider">
                                <i class="fas fa-circle text-[8px] mr-1 align-middle"></i> Non-Aktif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-slate-300 hover:text-red-500 transition-colors" onclick="return confirm('Hapus kelas ini?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                {{-- UPDATE REVISI: Colspan dinaikkan dari 4 menjadi 5 karena ada penambahan kolom status --}}
                <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400 italic font-medium">Belum ada kelas. Klik Auto-Sync atau Buat Manual.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL INPUT MATA KULIAH BARU -->
<div class="modal fade fixed inset-0 z-[1055] hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm" id="modalTambahMatkul" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered relative w-auto pointer-events-none max-w-md mx-auto">
        <div class="modal-content border-none shadow-2xl relative flex flex-col w-full pointer-events-auto bg-white rounded-3xl bg-clip-padding outline-none text-current">
            <div class="modal-header flex flex-shrink-0 items-center justify-between p-6 pb-0 border-none rounded-t-3xl">
                <h5 class="text-xl font-bold text-slate-800">Input Mata Kuliah Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.course.store') }}" method="POST">
                @csrf
                <div class="modal-body relative p-6">
                    <div class="mb-4">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Pilih Program Studi Asal</label>
                        <select name="prodi_id" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                            <option value="">-- Pilih Prodi --</option>
                            @foreach($prodis as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_prodi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Kode Matkul</label>
                        <input type="text" name="kode_mk" placeholder="Contoh: INF101" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
                    </div>
                    <div class="mb-4">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Nama Matkul Lengkap</label>
                        <input type="text" name="nama_mk" placeholder="Contoh: Algoritma Pemrograman" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-2">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">SKS</label>
                            <input type="number" name="sks" placeholder="3" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Semester</label>
                            <input type="number" name="semester" placeholder="1-8" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-none p-6 pt-0 flex gap-2">
                    <button type="button" class="flex-1 py-3 text-slate-500 font-bold hover:bg-slate-50 rounded-xl" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-xl font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">Simpan Matkul</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL PEMBENTUKAN KELAS MANUAL -->
<div class="modal fade fixed inset-0 z-[1055] hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm" id="modalTambahKelas" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-lg modal-dialog-centered relative w-auto pointer-events-none max-w-4xl mx-auto">
        <div class="modal-content border-none shadow-2xl relative flex flex-col w-full pointer-events-auto bg-white rounded-3xl bg-clip-padding outline-none text-current">
            <div class="modal-header border-none p-6 pb-0 rounded-t-3xl">
                <h5 class="text-xl font-bold text-slate-800">Pembentukan Kelas Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.kelas.store') }}" method="POST">
                @csrf
                <div class="modal-body p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Mata Kuliah</label>
                            <select name="course_id" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
                                <option value="">-- Pilih Matkul --</option>
                                @foreach($courses as $c)
                                    <option value="{{ $c->id }}">{{ $c->nama_mk }} (SMT {{ $c->semester }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Dosen Pengajar</label>
                            <select name="dosen_id" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
                                <option value="">-- Pilih Dosen --</option>
                                @foreach($dosens as $d)
                                    <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Nama Kelas LMS</label>
                            <input type="text" name="nama_kelas" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" placeholder="Contoh: TI 4A - PWL" required>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Tahun Akademik</label>
                            <input type="text" name="tahun_akademik" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" value="2025/2026" required>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-3 block text-blue-600">Daftarkan Rombongan Kelas (Otomatis Sedot Mahasiswa):</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($mahasiswasGrouped as $g)
                            <label class="flex items-center p-3 bg-white border border-slate-200 rounded-xl cursor-pointer hover:border-blue-500 transition-all">
                                <input type="checkbox" name="target_kelas[]" value="{{ $g->kelas }}" class="w-4 h-4 text-blue-600 rounded">
                                <div class="ml-3">
                                    <div class="text-xs font-bold text-slate-700">{{ $g->kelas }}</div>
                                    <div class="text-[9px] text-slate-400 uppercase">{{ $g->total }} Mahasiswa</div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        <small class="text-slate-400 mt-2 block">*Centang rombel yang ikut kelas ini (misal TI 4A). Semua mhs di rombel tsb akan otomatis join.</small>
                    </div>
                </div>
                <div class="modal-footer border-none p-6 pt-0 flex gap-2">
                    <button type="button" class="flex-1 py-3 text-slate-500 font-bold hover:bg-slate-50 rounded-xl" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-slate-800 text-white rounded-xl font-bold shadow-lg hover:bg-slate-700 transition-all">Rakit Kelas Manual</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT MATA KULIAH -->
<div class="modal fade fixed inset-0 z-[1055] hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm" id="modalEditMatkul" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered relative w-auto pointer-events-none max-w-md mx-auto">
        <div class="modal-content rounded-3xl border-none shadow-2xl">
            <div class="modal-header border-none p-6 pb-0 rounded-t-3xl">
                <h5 class="text-xl font-bold text-blue-600">Edit Mata Kuliah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditMatkul" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-6">
                    <div class="mb-4">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Kode Matkul</label>
                        <input type="text" name="kode_mk" id="edit_matkul_kode" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
                    </div>
                    <div class="mb-4">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Nama Matkul</label>
                        <input type="text" name="nama_mk" id="edit_matkul_nama" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">SKS</label>
                            <input type="number" name="sks" id="edit_matkul_sks" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Semester</label>
                            <input type="number" name="semester" id="edit_matkul_semester" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-none p-6 pt-0 flex gap-2">
                    <button type="button" class="flex-1 py-3 text-slate-500 font-bold hover:bg-slate-50 rounded-xl" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-xl font-bold shadow-lg hover:bg-blue-700 transition-all">Update Matkul</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection