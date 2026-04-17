@extends('layouts.dosen')

@section('title', 'Ruang Kelas')
@section('header_title', 'Ruang Kelas Praktikum')

@section('content')
    <div class="relative rounded-2xl overflow-hidden shadow-sm mb-6 h-48 flex items-end p-6" style="background-color: #FFD700;">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 20px 20px;"></div>
        
        <div class="relative z-10 w-full flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 mb-1">Pemrograman Web Lanjut</h1>
                <p class="text-lg font-medium text-slate-800">Kelas: TI-4A</p>
            </div>
            <div class="hidden sm:block text-right">
                <span class="bg-slate-900 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                    32 Mahasiswa
                </span>
            </div>
        </div>
    </div>

    <div class="bg-white border-b border-slate-200 mb-6 sticky top-0 z-20">
        <nav class="flex space-x-8 px-6" aria-label="Tabs">
            <a href="#" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 border-b-4 whitespace-nowrap py-4 px-1 font-medium text-sm transition-colors">
                Beranda
            </a>
            
            <a href="#" class="border-b-4 border-slate-900 text-slate-900 whitespace-nowrap py-4 px-1 font-bold text-sm">
                Materi & Modul
            </a>
            
            <a href="#" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 border-b-4 whitespace-nowrap py-4 px-1 font-medium text-sm transition-colors">
                Tugas Praktikum
            </a>
            
            <a href="#" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 border-b-4 whitespace-nowrap py-4 px-1 font-medium text-sm transition-colors">
                Anggota
            </a>
            
            <a href="#" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 border-b-4 whitespace-nowrap py-4 px-1 font-medium text-sm transition-colors">
                Buku Nilai
            </a>
        </nav>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        <div class="hidden lg:block lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <h3 class="font-bold text-slate-800 mb-3">Tugas Mendatang</h3>
                <p class="text-sm text-slate-500 mb-3">Hore, tidak ada tugas yang perlu segera dinilai minggu ini!</p>
                <a href="#" class="text-sm font-semibold text-blue-600 hover:underline">Lihat semua</a>
            </div>
        </div>

        <div class="lg:col-span-3">
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Modul Praktikum</h2>
                    <p class="text-sm text-slate-500">Materi referensi untuk mahasiswa kelas ini.</p>
                </div>
                
                <button type="button" data-bs-toggle="modal" data-bs-target="#modalTambahMateri" class="w-full sm:w-auto text-slate-900 font-bold py-2 px-4 rounded-lg shadow-sm flex justify-center items-center transition-colors" style="background-color: #FFD700;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Posting Materi Baru
                </button>
            </div>

            <div class="space-y-4">
                
                <div class="bg-white border border-slate-200 p-4 sm:p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow flex flex-col sm:flex-row gap-4 sm:items-center justify-between">
                    <div class="flex items-start gap-4">
                        <div class="bg-red-50 text-red-500 p-3 rounded-lg shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base sm:text-lg">Modul 1: Pengenalan HTML & CSS</h3>
                            <p class="text-sm text-slate-500 mt-1">Diposting 12 Sep 2025 • Dasar pembuatan kerangka web.</p>
                        </div>
                    </div>
                    <a href="#" class="w-full sm:w-auto text-center border border-slate-300 text-slate-700 hover:bg-slate-50 font-semibold py-2 px-4 rounded-lg text-sm transition-colors mt-2 sm:mt-0">
                        Unduh PDF
                    </a>
                </div>

                <div class="bg-white border border-slate-200 p-4 sm:p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow flex flex-col sm:flex-row gap-4 sm:items-center justify-between">
                    <div class="flex items-start gap-4">
                        <div class="bg-blue-50 text-blue-500 p-3 rounded-lg shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base sm:text-lg">Referensi Layout Flexbox</h3>
                            <p class="text-sm text-slate-500 mt-1">Diposting 14 Sep 2025 • Pelajari trik Flexbox di sini.</p>
                        </div>
                    </div>
                    <a href="#" target="_blank" class="w-full sm:w-auto text-center bg-slate-900 text-white hover:bg-slate-800 font-semibold py-2 px-4 rounded-lg text-sm transition-colors mt-2 sm:mt-0">
                        Buka Tautan
                    </a>
                </div>

            </div>
        </div>
    </div>

    @push('modals')
    <div class="modal fade" id="modalTambahMateri" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0" style="background-color: #FFD700;">
                    <h5 class="modal-title fw-bold text-dark">Posting Materi Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form action="#" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul Materi</label>
                            <input type="text" name="title" class="form-control rounded-3" placeholder="Contoh: Modul 1 - Pengenalan..." required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi (Opsional)</label>
                            <textarea name="description" class="form-control rounded-3" rows="3" placeholder="Tambahkan petunjuk untuk mahasiswa..."></textarea>
                        </div>

                        <div class="py-2 mb-3 border-top border-bottom bg-light text-center text-muted small fw-bold">
                            ISI SALAH SATU ATAU KEDUANYA
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="fas fa-file-upload me-1"></i> Unggah File</label>
                            <input type="file" name="file_path" class="form-control rounded-3" accept=".pdf,.doc,.docx,.jpg,.png,.mp4">
                            <div class="form-text text-sm">Format: PDF, Word, Image, Video (Maks 10MB)</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="fas fa-link me-1"></i> Tautan / Link URL</label>
                            <input type="url" name="link_url" class="form-control rounded-3" placeholder="https://youtube.com/...">
                            <div class="form-text text-sm">Jika materi berupa video eksternal atau website.</div>
                        </div>
                    </div>
                    
                    <div class="modal-footer border-0 bg-slate-50">
                        <button type="button" class="btn border border-slate-300 text-slate-700 rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn text-slate-900 font-bold rounded-pill px-4" style="background-color: #FFD700;">Posting Materi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endpush

@endsection