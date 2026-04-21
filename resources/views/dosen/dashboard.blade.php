@extends('layouts.dosen') @section('title', 'Beranda Dosen')
@section('header_title', 'Kelas Saya')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Daftar Kelas Praktikum</h2>
            <p class="text-slate-500 text-sm">Pilih kelas untuk mulai mengelola modul dan tugas mahasiswa.</p>
        </div>
       
        <button type="button" data-bs-toggle="modal" data-bs-target="#modalBuatKelas" class="text-slate-900 font-bold py-2 px-4 rounded-lg shadow-sm flex items-center transition-colors" style="background-color: #FFD700;">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Buat Kelas Manual
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        @forelse($courses as $course)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow relative">
            <div class="h-24 p-5 flex flex-col justify-end" style="background-color: #FFD700;">
                <h3 class="text-xl font-black text-slate-900 truncate leading-tight">
                    {{ $course->course_name ?? 'Matakuliah Belum Diisi' }}
                </h3>
                <p class="text-sm text-slate-800 font-bold opacity-80">
                    Grup: {{ $course->course_code }}
                </p>
            </div>
            
            <div class="p-5">
                <div class="flex items-center text-slate-500 text-sm mb-4">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Mahasiswa Terdaftar
                </div>
                
                <a href="{{ route('dosen.kelas.show', $course->id) }}" class="block w-full text-center bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-semibold py-2 rounded-lg transition-colors">
                    Masuk Ruang Kelas
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-10 bg-slate-50 border-2 border-dashed border-slate-300 rounded-xl">
            <p class="text-slate-500 font-medium">Belum ada kelas yang Anda ampu.</p>
        </div>
        @endforelse

    </div>
@endsection

@push('modals')
<div class="modal fade" id="modalBuatKelas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0" style="background-color: #FFD700;">
                <h5 class="modal-title fw-bold text-dark">Form Buat Kelas Praktikum</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('dosen.kelas.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info text-sm bg-blue-50 text-blue-800 border-0 rounded-lg mb-4">
                        Kelas yang dibuat secara manual akan otomatis terhubung dengan akun Anda.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Mata Kuliah</label>
                        <input type="text" name="course_name" class="form-control rounded-3" placeholder="Contoh: Jaringan Komputer Dasar" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kode / Grup Kelas</label>
                        <input type="text" name="course_code" class="form-control rounded-3" placeholder="Contoh: TI-2A" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi Singkat (Opsional)</label>
                        <textarea name="deskripsi" class="form-control rounded-3" rows="3" placeholder="Tuliskan aturan atau deskripsi singkat kelas ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark rounded-pill px-4">Buat Kelas</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush