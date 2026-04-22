@extends('layouts.admin')

@section('title', 'Data Mahasiswa')
@section('header_title', 'Manajemen Data Mahasiswa')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Daftar Mahasiswa</h2>
            <p class="text-slate-500 text-sm">Kelola data mahasiswa yang mengikuti kegiatan praktikum.</p>
        </div>
        
        <button type="button" data-bs-toggle="modal" data-bs-target="#modalTambahMahasiswa" class="text-slate-900 font-bold py-2 px-4 rounded-lg shadow-sm flex items-center transition-colors" style="background-color: #FFD700;">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Mahasiswa 
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
                        <th class="px-6 py-4 font-semibold">NIM</th>
                        <th class="px-6 py-4 font-semibold">Nama Lengkap</th>
                        <th class="px-6 py-4 font-semibold">Program Studi</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @forelse($mahasiswas as $index => $mhs)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-slate-800">{{ $mhs->nim }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $mhs->nama }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $mhs->prodi }} ({{ $mhs->kelas }})</td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-red-600 hover:text-red-900">Hapus</button>
                        </td>
                    </tr>
                @empty
                    @endforelse
            </tbody>
            </table>
        </div>
    </div>
@endsection

@push('modals')
<div class="modal fade" id="modalTambahMahasiswa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0" style="background-color: #FFD700;">
                <h5 class="modal-title fw-bold text-dark">Form Tambah Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('admin.mahasiswa.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">NIM</label>
                        <input type="text" name="nim" class="form-control rounded-3" placeholder="Masukkan NIM Mahasiswa" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control rounded-3" placeholder="Masukkan Nama Lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email Mahasiswa</label>
                        <input type="email" name="email" class="form-control rounded-3" placeholder="email@student.polsa.ac.id" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Program Studi</label>
                        <select name="prodi" class="form-select rounded-3" required>
                            <option value="">Pilih Prodi</option>
                            <option value="Teknik Informatika">Teknik Informatika</option>
                            <option value="Akuntansi">Akuntansi</option>
                            <option value="Administrasi Bisnis">Administrasi Bisnis</option>
                            <option value="Teknik Rekayasa Perangkat Lunak">Teknik Rekayasa Perangkat Lunak</option>
                            <option value="Bisnis Digital">Bisnis Digital</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kelas</label>
                        <input type="text" name="kelas" class="form-control rounded-3" placeholder="Masukkan Kelas" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark rounded-pill px-4">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush