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
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold">No</th>
                    <th class="px-6 py-4 font-semibold">Kode Kelas</th>
                    <th class="px-6 py-4 font-semibold">Nama Kelas</th>
                    <th class="px-6 py-4 font-semibold">Program Studi</th>
                    <th class="px-6 py-4 font-semibold">Dosen</th> <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($courses as $index => $course)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-slate-800">{{ $course->course_code }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $course->course_name }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $course->prodi }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{-- Panggil relasi teacher yang ada di model Course --}}
                            <span class="font-medium text-slate-700">{{ $course->teacher->name ?? 'Dosen Tidak Ada' }}</span>
                        </td>
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
<div class="modal fade" id="modalTambahKelas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0" style="background-color: #FFD700;">
                <h5 class="modal-title fw-bold text-dark">Form Tambah Kelas Praktikum</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('admin.kelas.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kode Kelas</label>
                        <input type="text" name="course_code" class="form-control rounded-3" placeholder="Contoh: TI-1A" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Kelas</label>
                        <input type="text" name="course_name" class="form-control rounded-3" placeholder="Contoh: Teknik Informatika 1A" required>
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
                        <label class="form-label fw-bold">Dosen Pengampu</label>
                        <select name="teacher_id" class="form-select rounded-3" required>
                            <option value="">Pilih Dosen Pengampu</option>
                            @foreach($dosens as $dosen)
                                <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                            @endforeach
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