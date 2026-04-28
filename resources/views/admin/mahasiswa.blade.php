@extends('layouts.admin')

@section('content')
<div class="p-4">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Data Mahasiswa</h2>
            <p class="text-slate-500 text-sm">Kelola data mahasiswa POLSA dan sinkronisasi akun login.</p>
        </div>
        <div class="flex gap-2">
            <form action="{{ route('admin.mahasiswa.sync') }}" method="POST">
                @csrf
                <button type="submit" class="h-11 px-4 bg-indigo-600 text-white font-bold rounded-xl shadow-lg hover:bg-indigo-700 transition-all flex items-center">
                    <i class="fas fa-sync-alt mr-2"></i> Sync API
                </button>
            </form>
            <button type="button" data-bs-toggle="modal" data-bs-target="#modalTambahMahasiswa" class="h-11 px-4 bg-yellow-400 text-slate-900 font-bold rounded-xl shadow-lg hover:bg-yellow-500 transition-all flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Manual
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-500 text-[10px] font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4">Mahasiswa</th>
                    <th class="px-6 py-4">Prodi & Kelas</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($mahasiswas as $mhs)
                <tr class="hover:bg-slate-50 transition-all">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                                {{ strtoupper(substr($mhs->nama, 0, 1)) }}
                            </div>
                            <div class="ml-3">
                                <div class="font-bold text-slate-800">{{ $mhs->nama }}</div>
                                <div class="text-xs text-slate-400 font-mono">{{ $mhs->nim }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-slate-600 font-medium">{{ $mhs->prodi }}</div>
                        <div class="text-[10px] text-blue-600 font-bold uppercase bg-blue-50 px-2 py-0.5 rounded-md inline-block mt-1">{{ $mhs->kelas }} - SMT {{ $mhs->semester }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-500">
                        {{ $mhs->user->email ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-3">
                            <button type="button" class="btn-edit-mahasiswa text-blue-500 hover:text-blue-700" 
                                data-id="{{ $mhs->id }}" 
                                data-nim="{{ $mhs->nim }}" 
                                data-nama="{{ $mhs->nama }}" 
                                data-prodi="{{ $mhs->prodi }}" 
                                data-kelas="{{ $mhs->kelas }}" 
                                data-semester="{{ $mhs->semester }}"
                                data-email="{{ $mhs->user->email ?? '' }}"
                                data-bs-toggle="modal" data-bs-target="#modalEditMahasiswa">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.mahasiswa.destroy', $mhs->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-slate-300 hover:text-red-500 transition-colors" onclick="return confirm('Hapus mahasiswa ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-12 text-center text-slate-400 italic font-medium">Belum ada data mahasiswa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalEditMahasiswa" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3xl border-none shadow-2xl">
            <div class="modal-header border-none p-6 pb-0">
                <h5 class="text-xl font-bold text-slate-800">Edit Data Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditMahasiswa" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-6">
                    <div class="mb-3">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">NIM</label>
                        <input type="text" name="nim" id="edit_nim" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
                        <small class="text-blue-500 text-[10px] mt-1 block">*Mengubah NIM akan merubah username login.</small>
                    </div>
                    <div class="mb-3">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Email Mahasiswa</label>
                        <input type="email" name="email" id="edit_email" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Nama Lengkap</label>
                        <input type="text" name="nama" id="edit_nama" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Prodi</label>
                            <input type="text" name="prodi" id="edit_prodi" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Kelas</label>
                            <input type="text" name="kelas" id="edit_kelas" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Semester</label>
                        <input type="number" name="semester" id="edit_semester" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
                    </div>
                </div>
                <div class="modal-footer border-none p-6 pt-0 flex gap-2">
                    <button type="button" class="flex-1 py-3 text-slate-500 font-bold hover:bg-slate-50 rounded-xl" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition-all">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.btn-edit-mahasiswa');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                // Set endpoint update
                document.getElementById('formEditMahasiswa').action = `/admin/mahasiswa/${id}`;
                
                // Masukkan data ke input modal
                document.getElementById('edit_nim').value = this.getAttribute('data-nim');
                document.getElementById('edit_email').value = this.getAttribute('data-email');
                document.getElementById('edit_nama').value = this.getAttribute('data-nama');
                document.getElementById('edit_prodi').value = this.getAttribute('data-prodi');
                document.getElementById('edit_kelas').value = this.getAttribute('data-kelas');
                document.getElementById('edit_semester').value = this.getAttribute('data-semester');
            });
        });
    });
</script>
@endsection