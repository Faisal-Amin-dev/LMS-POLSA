@extends('layouts.admin')

@section('title', 'Manajemen Kelas')

@section('content')
<div class="p-4">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Data Kelas & Jadwal</h2>
            <p class="text-slate-500 text-sm">Hubungkan Dosen, Mata Kuliah, dan Rombongan Belajar.</p>
        </div>
        <button type="button" data-bs-toggle="modal" data-bs-target="#modalTambahKelas" class="h-11 px-6 bg-slate-800 text-white font-bold rounded-xl shadow-lg hover:bg-slate-700 transition-all flex items-center">
            <i class="fas fa-plus-circle mr-2"></i> Buat Kelas Baru
        </button>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold">
                <tr>
                    <th class="px-6 py-4">Mata Kuliah & Kelas</th>
                    <th class="px-6 py-4">Dosen Pengajar</th>
                    <th class="px-6 py-4">Jadwal</th>
                    <th class="px-6 py-4">Peserta</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($classrooms as $k)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-800">{{ $k->course->nama_matkul ?? 'Matkul Terhapus' }}</div>
                        <div class="text-xs text-blue-600 font-bold uppercase">{{ $k->nama_kelas }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ $k->dosen->nama ?? 'Belum ada Dosen' }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <span class="block font-bold">{{ $k->hari }}</span>
                        <span class="text-xs text-slate-400">{{ $k->jam_mulai }} - {{ $k->jam_selesai }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-bold">
                            {{ $k->mahasiswas->count() }} Mahasiswa
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus kelas ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Belum ada kelas yang dibentuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalTambahKelas" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-3xl border-none shadow-2xl">
            <div class="modal-header border-none p-6 pb-0">
                <h5 class="text-xl font-bold text-slate-800">Pembentukan Kelas LMS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.kelas.store') }}" method="POST">
                @csrf
                <div class="modal-body p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Mata Kuliah</label>
                            <select name="course_id" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/20 outline-none" required>
                                <option value="">-- Pilih Matkul --</option>
                                @foreach($courses as $c)
                                    <option value="{{ $c->id }}">{{ $c->nama_matkul }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Dosen Pengajar</label>
                            <select name="dosen_id" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/20 outline-none" required>
                                <option value="">-- Pilih Dosen --</option>
                                @foreach($dosens as $d)
                                    <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Nama Kelas LMS</label>
                            <input type="text" name="nama_kelas" class="w-full border border-slate-300 px-4 py-2.5 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/20 outline-none" placeholder="Misal: Web Prog - TI 4A" required>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Hari</label>
                            <select name="hari" class="w-full border border-slate-300 px-4 py-2.5 rounded-xl text-sm outline-none" required>
                                <option value="Senin">Senin</option><option value="Selasa">Selasa</option><option value="Rabu">Rabu</option><option value="Kamis">Kamis</option><option value="Jumat">Jumat</option><option value="Sabtu">Sabtu</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Tahun Akademik</label>
                            <input type="text" name="tahun_akademik" class="w-full border border-slate-300 px-4 py-2.5 rounded-xl text-sm outline-none" placeholder="2025/2026" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Jam Mulai</label>
                            <input type="time" name="jam_mulai" class="w-full border border-slate-300 px-4 py-2.5 rounded-xl text-sm outline-none" required>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Jam Selesai</label>
                            <input type="time" name="jam_selesai" class="w-full border border-slate-300 px-4 py-2.5 rounded-xl text-sm outline-none" required>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-3 block">Pilih Rombongan Mahasiswa (Possible Classes):</label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($mahasiswasGrouped as $g)
                            <label class="flex items-center p-3 bg-white border border-slate-200 rounded-xl cursor-pointer hover:border-blue-500 transition-all">
                                <input type="checkbox" name="target_kelas[]" value="{{ $g->kelas }}" class="w-4 h-4 text-blue-600 rounded">
                                <div class="ml-3">
                                    <div class="text-sm font-bold text-slate-700">{{ $g->kelas }}</div>
                                    <div class="text-[10px] text-slate-400 uppercase">{{ $g->total }} Mahasiswa</div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-none p-6 pt-0 flex gap-2">
                    <button type="button" class="flex-1 py-3 text-slate-500 font-bold hover:bg-slate-50 rounded-xl" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-blue-600 text-white rounded-xl font-bold shadow-lg shadow-blue-100 hover:bg-blue-700">Simpan & Daftarkan Mahasiswa</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection