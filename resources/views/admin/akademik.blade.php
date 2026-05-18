@extends('layouts.admin')

@section('content')
<div class="p-4">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">KRS & Akademik</h2>
            <p class="text-slate-500 text-sm">Pusat kendali tahun ajaran dan validasi Kartu Rencana Studi (KRS).</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm font-bold flex items-center">
            <i class="fas fa-check-circle mr-2 text-xl"></i>
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
            <ul class="list-disc ml-5 text-sm font-medium">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        <div class="xl:col-span-1">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden mb-6">
                <div class="p-6 bg-slate-50 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800"><i class="fas fa-calendar-alt mr-2 text-blue-600"></i>Tahun Akademik</h3>
                    <p class="text-xs text-slate-500 mt-1">Status tahun berjalan saat ini.</p>
                </div>
                <div class="p-6">
                    <div class="text-center mb-6">
                        <p class="text-sm text-slate-500 font-medium uppercase tracking-widest mb-1">Tahun Aktif Saat Ini</p>
                        <h4 class="text-3xl font-black text-blue-600">{{ $tahunAktif }}</h4>
                    </div>

                    <div class="bg-red-50 p-4 rounded-xl border border-red-100">
                        <label class="text-xs font-bold text-red-600 uppercase mb-3 block"><i class="fas fa-exclamation-triangle mr-1"></i> Tutup Semester (Arsip):</label>
                        <form action="{{ route('admin.akademik.gantiTahun') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tahun_lama" value="{{ $tahunAktif }}">
                            
                            <div class="mb-3">
                                <input type="text" name="tahun_baru" class="w-full bg-white border border-red-200 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-red-400 outline-none" placeholder="Tahun Baru (cth: 2026/2027)" required>
                            </div>
                            <button type="submit" class="w-full py-3 bg-red-500 text-white rounded-xl font-bold shadow-lg hover:bg-red-600 transition-all flex justify-center items-center" onclick="return confirm('PERINGATAN! \n\nSemua kelas pada tahun {{ $tahunAktif }} akan diubah menjadi ARSIP secara permanen. \n\nApakah Anda yakin ingin mengganti tahun ajaran?')">
                                <i class="fas fa-archive mr-2"></i> Eksekusi & Ganti Tahun
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="xl:col-span-2">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800"><i class="fas fa-file-signature mr-2 text-indigo-600"></i>Validasi KRS Mahasiswa</h3>
                        <p class="text-xs text-slate-500 mt-1">Daftar mahasiswa yang mengajukan kelas di tahun <b>{{ $tahunAktif }}</b>.</p>
                    </div>
                </div>
                
                <table class="w-full text-left">
                    <thead class="bg-white text-slate-500 text-[10px] font-bold uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Mahasiswa</th>
                            <th class="px-6 py-4">Total SKS</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        {{-- Ini adalah tempat untuk loop KRS nanti, pakai ?? [] agar tidak error sebelum fiturnya kita buat utuh --}}
                        @forelse($krs_list ?? [] as $krs)
                        <tr>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <p class="text-slate-500 font-medium">Belum ada pengajuan KRS.</p>
                                <p class="text-xs text-slate-400 mt-1">Fitur manajemen detail KRS akan segera aktif.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>
@endsection