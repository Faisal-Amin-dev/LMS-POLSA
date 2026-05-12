@extends('layouts.admin')

@section('content')
<div class="p-4">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Master Data Prodi</h2>
            <p class="text-slate-500 text-sm">Kelola daftar Program Studi di kampus.</p>
        </div>
        <div class="flex gap-2">
            <form action="{{ route('admin.prodi.sync') }}" method="POST">
                @csrf
                <button type="submit" class="h-11 px-4 bg-indigo-600 text-white font-bold rounded-xl shadow-lg hover:bg-indigo-700 transition-all flex items-center">
                    <i class="fas fa-sync-alt mr-2"></i> Sync API
                </button>
            </form>
            <button type="button" data-bs-toggle="modal" data-bs-target="#modalTambahProdi" class="h-11 px-4 bg-yellow-400 text-slate-900 font-bold rounded-xl shadow-lg hover:bg-yellow-500 transition-all flex items-center">
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
                    <th class="px-6 py-4">Kode Prodi</th>
                    <th class="px-6 py-4">Nama Program Studi</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($prodis as $p)
                <tr class="hover:bg-slate-50 transition-all">
                    <td class="px-6 py-4 font-mono font-bold text-indigo-600">{{ $p->kode_prodi }}</td>
                    <td class="px-6 py-4 text-slate-800 font-medium">{{ $p->nama_prodi }}</td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-3">
                            <button type="button" class="btn-edit-prodi text-blue-500 hover:text-blue-700"
                                data-id="{{ $p->id }}"
                                data-kode="{{ $p->kode_prodi }}"
                                data-nama="{{ $p->nama_prodi }}"
                                data-bs-toggle="modal" data-bs-target="#modalEditProdi">
                                <i class="fas fa-edit"></i>
                            </button>
                            
                            <form action="{{ route('admin.prodi.destroy', $p->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-slate-300 hover:text-red-500 transition-colors" onclick="return confirm('Hapus prodi ini? Data mahasiswa yang terhubung mungkin akan ikut terdampak.')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-6 py-12 text-center text-slate-400 italic font-medium">Belum ada data prodi. Klik Sync API untuk mengambil data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalTambahProdi" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3xl border-none shadow-2xl">
            <div class="modal-header border-none p-6 pb-0">
                <h5 class="text-xl font-bold text-slate-800">Tambah Data Prodi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.prodi.store') }}" method="POST">
                @csrf
                <div class="modal-body p-6">
                    <div class="mb-3">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Kode Prodi</label>
                        <input type="text" name="kode_prodi" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required placeholder="Contoh: TI">
                    </div>
                    <div class="mb-3">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Nama Program Studi</label>
                        <input type="text" name="nama_prodi" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required placeholder="Contoh: Teknik Informatika">
                    </div>
                </div>
                <div class="modal-footer border-none p-6 pt-0 flex gap-2">
                    <button type="button" class="flex-1 py-3 text-slate-500 font-bold hover:bg-slate-50 rounded-xl" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition-all">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditProdi" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3xl border-none shadow-2xl">
            <div class="modal-header border-none p-6 pb-0">
                <h5 class="text-xl font-bold text-slate-800">Edit Data Prodi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditProdi" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-6">
                    <div class="mb-3">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Kode Prodi</label>
                        <input type="text" name="kode_prodi" id="edit_kode_prodi" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Nama Program Studi</label>
                        <input type="text" name="nama_prodi" id="edit_nama_prodi" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm" required>
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
        const editButtons = document.querySelectorAll('.btn-edit-prodi');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                
                // Set endpoint form Edit
                document.getElementById('formEditProdi').action = `/admin/prodi/${id}`;
                
                // Isi form dengan data yang ada
                document.getElementById('edit_kode_prodi').value = this.getAttribute('data-kode');
                document.getElementById('edit_nama_prodi').value = this.getAttribute('data-nama');
            });
        });
    });
</script>
@endsection