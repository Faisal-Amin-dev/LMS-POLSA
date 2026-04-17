@extends('layouts.admin')

@section('title', 'Data Kelas')
@section('header_title', 'Manajemen Data Kelas')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Daftar Kelas Praktikum</h2>
            <p class="text-slate-500 text-sm">Kelola data kelas untuk plotting jadwal dan mahasiswa.</p>
        </div>
        
        <button type="button" data-bs-toggle="modal" data-bs-target="#modalTambahKelas" class="text-slate-900 font-bold py-2 px-4 rounded-lg shadow-sm flex items-center transition-colors" style="background-color: #FFD700;">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Kelas 
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-dismissible fade show border-0 shadow-sm text-dark mb-4" style="background-color: #FFF3CD;" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">No</th>
                        <th class="px-6 py-4 font-semibold">Kode Kelas</th>
                        <th class="px-6 py-4 font-semibold">Nama Kelas</th>
                        <th class="px-6 py-4 font-semibold">Program Studi</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <p class="text-lg font-medium text-slate-600">Belum ada data kelas</p>
                                <p class="text-sm mt-1">Silakan sinkronisasi dari SIAP POLSA atau tambahkan secara manual.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('modals')
<div class="modal fade" id="modalTambahKelas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0" style="background-color: #FFD700;">
                <h5 class="modal-title fw-bold text-dark">Form Tambah Kelas Praktikum</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('kelas.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kode Kelas</label>
                        <input type="text" name="kode_kelas" class="form-control rounded-3" placeholder="Contoh: TI-1A" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Kelas</label>
                        <input type="text" name="nama_kelas" class="form-control rounded-3" placeholder="Contoh: Teknik Informatika 1A" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Program Studi</label>
                        <select name="prodi" class="form-select rounded-3" required>
                            <option value="">Pilih Prodi</option>
                            <option value="Teknik Informatika">Teknik Informatika</option>
                            <option value="Akuntansi">Akuntansi</option>
                            <option value="Administrasi Bisnis">Administrasi Bisnis</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark rounded-pill px-4">Simpan Kelas</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush