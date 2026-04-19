{{-- 
    DOKUMENTASI DASHBOARD MAHASISWA
    Visi: Mirip Google Classroom (LMS-POLSA)
    Integrasi: Siap dihubungkan ke Google Drive API
--}}

@extends('layouts.mahasiswa')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Dashboard Kuliah</h1>
            <p class="text-gray-500 font-medium">{{ Auth::user()->name }} • {{ $data['akademik']['semester'] }}</p>
        </div>
        {{-- Tombol Cepat ke Google Drive Kelas --}}
        <a href="{{ $data['akademik']['drive_folder'] }}" target="_blank" class="flex items-center px-5 py-2.5 bg-white border-2 border-green-500 text-green-600 rounded-xl font-bold hover:bg-green-50 transition shadow-sm">
            <i class="fab fa-google-drive mr-2 text-xl"></i>
            Buka Drive Kelas
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-blue-600 rounded-2xl p-6 text-white shadow-lg">
            <p class="text-blue-100 text-xs font-bold uppercase tracking-widest">Tugas Perlu Tindakan</p>
            <h3 class="text-4xl font-black mt-2">{{ $data['akademik']['tugas_pending'] }}</h3>
        </div>
        <div class="bg-white border-2 border-gray-100 rounded-2xl p-6 shadow-sm">
            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Status SPP ({{ date('F') }})</p>
            <h3 class="text-2xl font-bold mt-2 text-green-600 italic uppercase">{{ $data['akademik']['status_spp'] }}</h3>
        </div>
        <div class="bg-white border-2 border-gray-100 rounded-2xl p-6 shadow-sm">
            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">IPK Sementara</p>
            <h3 class="text-2xl font-bold mt-2 text-gray-800">3.75</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        
        <div class="lg:col-span-2 space-y-8">
            
            <section>
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-clipboard-list mr-3 text-blue-500"></i>
                    Tugas Mendatang
                </h3>
                <div class="space-y-4">
                    @forelse($data['tugas_terbaru'] as $tugas)
                    <div class="group bg-white p-5 rounded-2xl border-2 border-transparent hover:border-blue-500 shadow-sm transition-all cursor-pointer">
                        <div class="flex justify-between items-start">
                            <div class="flex gap-4">
                                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition">
                                    <i class="fas fa-file-signature text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">{{ $tugas['judul'] }}</h4>
                                    <p class="text-sm text-gray-500">{{ $tugas['matkul'] }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-black px-2 py-1 rounded bg-red-100 text-red-600 uppercase">
                                    Deadline: {{ $tugas['deadline'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-400 italic">Semua tugas sudah selesai dikerjakan.</p>
                    @endforelse
                </div>
            </section>

            <section>
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-folder-open mr-3 text-yellow-500"></i>
                    Materi Kuliah
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($data['materi_terbaru'] as $materi)
                    <a href="{{ $materi['link'] }}" class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-white border border-transparent hover:border-gray-200 transition shadow-sm">
                        <i class="fas fa-file-{{ $materi['tipe'] == 'PDF' ? 'pdf text-red-500' : 'powerpoint text-orange-500' }} text-2xl mr-4"></i>
                        <span class="font-semibold text-sm text-gray-700">{{ $materi['judul'] }}</span>
                    </a>
                    @endforeach
                </div>
            </section>
        </div>

        <aside>
            <div class="bg-gray-900 rounded-3xl p-6 text-white shadow-xl">
                <h3 class="font-bold text-lg mb-6 flex items-center">
                    <i class="fas fa-chalkboard mr-3 text-blue-400"></i>
                    Kelas Anda
                </h3>
                <div class="space-y-4">
                    @foreach($data['daftar_kelas'] as $kelas)
                    <div class="p-4 rounded-2xl bg-gray-800 hover:bg-gray-700 transition cursor-pointer border border-gray-700">
                        <h5 class="font-bold text-sm">{{ $kelas['nama'] }}</h5>
                        <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest">{{ $kelas['dosen'] }} • {{ $kelas['kode'] }}</p>
                    </div>
                    @endforeach
                </div>
                
                <button class="w-full mt-8 py-3 bg-blue-600 hover:bg-blue-500 rounded-xl font-bold text-sm transition">
                    Lihat Semua Kelas
                </button>
            </div>
        </aside>

    </div>
</div>
@endsection