@extends('layouts.admin')

@section('title', 'Manajemen Kurikulum')
@section('header_title', 'Data Pokok Kurikulum')

@section('content')
<div class="container mx-auto">
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r-xl text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Manajemen Kurikulum</h2>
            <p class="text-slate-500 text-sm">Set status aktif/tidak aktif acuan kurikulum untuk membatasi paket Mata Kuliah prodi.</p>
        </div>
        <button type="button" data-bs-toggle="modal" data-bs-target="#modalTambahKurikulum" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow transition-all flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Kurikulum
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-xs font-bold uppercase tracking-wider">
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Program Studi</th>
                    <th class="px-6 py-4">Nama Kurikulum</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                @forelse($kurikulums as $index => $k)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-4 font-medium text-slate-500">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $k->nama_prodi }}</td>
                    <td class="px-6 py-4 font-mono text-indigo-600 font-bold">{{ $k->nama_kurikulum }}</td>
                    <td class="px-6 py-4 text-center">
                        <form action="{{ route('admin.kurikulum.toggle', $k->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-3 py-1.5 rounded-full text-xs font-bold transition-all {{ $k->is_aktif ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                {!! $k->is_aktif ? '<i class="fas fa-check-circle mr-1"></i> Aktif' : '<i class="fas fa-times-circle mr-1"></i> Non-Aktif' !!}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-center flex justify-center gap-2">
                        <form action="{{ route('admin.kurikulum.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kurikulum ini? Semua relasi matkul di dalamnya bisa terpengaruh.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">
                        <i class="fas fa-book-open text-3xl mb-2 block text-slate-300"></i>
                        Belum ada data kurikulum pokok yang didaftarkan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade fixed inset-0 z-[1055] hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm" 
     id="modalTambahKurikulum" 
     tabindex="-1" 
     data-bs-backdrop="false" 
     aria-labelledby="modalTambahKurikulumLabel" 
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3xl border-none shadow-2xl">
            <div class="modal-header border-none p-6 pb-0">
                <h5 class="text-xl font-bold text-slate-800">Tambah Kurikulum Pokok</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.kurikulum.store') }}" method="POST">
                @csrf
                <div class="modal-body p-6">
                    <div class="mb-4">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Pilih Program Studi</label>
                        <select name="prodi_id" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                            <option value="">-- Pilih Prodi --</option>
                            @foreach($prodis as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_prodi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Nama / Kode Kurikulum</label>
                        <input type="text" name="nama_kurikulum" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Contoh: Kurikulum Merdeka 2026" required>
                    </div>
                </div>
                <div class="modal-footer border-none p-6 pt-0 flex gap-2">
                    <button type="button" class="flex-1 py-3 text-slate-500 font-bold hover:bg-slate-50 rounded-xl" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition-all">Simpan Kurikulum</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection