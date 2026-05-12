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
                        {{-- UBAHAN 1: Menampilkan nama prodi dari relasi --}}
                        <div class="text-sm text-slate-600 font-medium">{{ $mhs->prodi->nama_prodi ?? 'Belum ada Prodi' }}</div>
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
                                {{-- UBAHAN 2: Mengirim ID prodi, bukan teks prodi --}}
                                data-prodi-id="{{ $mhs->prodi_id }}" 
                                data-semester="{{ $mhs->semester }}"
                                data-email="{{ $mhs->user->email ?? '' }}"
                                data-classrooms="{{ $mhs->classrooms->pluck('id') }}"
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

<div class="modal fade" id="modalTambahMahasiswa" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3xl border-none shadow-2xl">
            <div class="modal-header border-none p-6 pb-0">
                <h5 class="text-xl font-bold text-slate-800">Tambah Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.mahasiswa.store') }}" id="formTambahMahasiswa" method="POST">
                @csrf
                <div class="modal-body p-6">
                    <div class="mb-3">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">NIM</label>
                        <input type="text" name="nim" id="nim" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
                        <small class="text-blue-500 text-[10px] mt-1 block">*Pastikan NIM unik dan benar.</small>
                    </div>
                    <div class="mb-3">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Email Mahasiswa</label>
                        <input type="email" name="email" id="email" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Nama Lengkap</label>
                        <input type="text" name="nama" id="nama" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Prodi</label>
                            {{-- UBAHAN 3: Form Tambah menggunakan Dropdown Select --}}
                            <select name="prodi_id" id="prodi_id" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
                                <option value="">-- Pilih Prodi --</option>
                                @foreach($prodis as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama_prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Kelas</label>
                            <input type="text" name="kelas" id="kelas" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Semester</label>
                        <input type="number" name="semester" id="semester" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
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
                            {{-- UBAHAN 4: Form Edit menggunakan Dropdown Select --}}
                            <select name="prodi_id" id="edit_prodi_id" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
                                <option value="">-- Pilih Prodi --</option>
                                @foreach($prodis as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama_prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-span-2">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Daftar Kelas Mahasiswa</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 bg-slate-50 p-4 rounded-xl border border-slate-200">
                                @foreach($classrooms as $cls)
                                    <div class="flex items-center p-2 hover:bg-white rounded-lg transition-all">
                                        <input type="checkbox" name="classroom_ids[]" value="{{ $cls->id }}" 
                                            class="classroom-checkbox w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500" 
                                            id="edit_cls_{{ $cls->id }}">
                                        <label for="edit_cls_{{ $cls->id }}" class="ml-2 text-sm text-slate-700 cursor-pointer">
                                            {{ $cls->nama_kelas }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
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
                
                // Masukkan data teks ke input modal
                document.getElementById('edit_nim').value = this.getAttribute('data-nim');
                document.getElementById('edit_email').value = this.getAttribute('data-email');
                document.getElementById('edit_nama').value = this.getAttribute('data-nama');
                
                // UBAHAN 5: Memilih ID Prodi pada dropdown saat Edit ditekan
                document.getElementById('edit_prodi_id').value = this.getAttribute('data-prodi-id');
                
                document.getElementById('edit_semester').value = this.getAttribute('data-semester');

                // === TAMBAHAN UNTUK MANY-TO-MANY ===
                
                // 1. Ambil data ID kelas dari atribut data-classrooms (berupa array JSON)
                const classrooms = JSON.parse(this.getAttribute('data-classrooms'));

                // 2. Reset semua checkbox kelas di modal edit agar tidak ada sisa centang sebelumnya
                const allCheckboxes = document.querySelectorAll('#modalEditMahasiswa .classroom-checkbox');
                allCheckboxes.forEach(cb => cb.checked = false);

                // 3. Centang checkbox yang ID-nya ada dalam daftar kelas mahasiswa tersebut
                classrooms.forEach(classId => {
                    const checkbox = document.querySelector(`#modalEditMahasiswa input[value="${classId}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
            });
        });
    });
</script>
@endsection