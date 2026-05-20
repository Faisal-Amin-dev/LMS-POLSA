@extends('layouts.mahasiswa')

@section('title', 'KRS & Akademik')
@section('header_title', 'Kartu Rencana Studi (KRS)')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden p-6 md:p-8">
        <div class="flex flex-col md:flex-row items-center gap-6">
            <div class="h-24 w-24 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-4xl text-blue-600 font-bold shadow-inner flex-shrink-0 uppercase">
                {{ substr($mahasiswa->nama, 0, 2) }}
            </div>
            <div class="flex-1 text-center md:text-left">
                <h2 class="text-2xl font-black text-slate-800 mb-1">{{ $mahasiswa->nama }}</h2>
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mt-2 text-sm">
                    <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-lg font-mono font-bold"><i class="fas fa-id-card mr-1 text-slate-400"></i> {{ $mahasiswa->nim }}</span>
                    <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-lg font-bold"><i class="fas fa-graduation-cap mr-1"></i> Prodi {{ $mahasiswa->prodi->nama_prodi ?? '-' }}</span>
                    <span class="bg-amber-50 text-amber-600 px-3 py-1 rounded-lg font-bold"><i class="fas fa-users mr-1"></i> Rombel {{ $mahasiswa->kelas }}</span>
                </div>
            </div>
            <div class="hidden md:block text-right border-l border-slate-100 pl-8">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Status Akademik</p>
                <p class="text-lg font-black text-green-500"><i class="fas fa-check-circle"></i> AKTIF</p>
                <p class="text-xs text-slate-500 mt-1 font-mono">Paket Semester</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold text-slate-800"><i class="fas fa-list-alt text-blue-500 mr-2"></i>Daftar Mata Kuliah Diambil</h2>
                <p class="text-xs text-slate-500 mt-1">Sistem paket otomatis. Daftar matkul di bawah ini sudah ditetapkan oleh pihak akademik Polsa.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-100 bg-slate-50/30">
                        <th class="px-6 py-4">Kode MK</th>
                        <th class="px-6 py-4">Nama Mata Kuliah</th>
                        <th class="px-6 py-4">SKS</th>
                        <th class="px-6 py-4">Dosen Pengampu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($classrooms as $k)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-xs font-mono font-bold text-slate-500">
                            {{ $k->course->kode_mk ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="block font-bold text-slate-800 text-sm">{{ $k->course->nama_mk ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold bg-slate-100 text-slate-600 px-3 py-1.5 rounded-lg border border-slate-200">
                                {{ $k->course->sks ?? '-' }} SKS
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 font-medium">
                            <i class="fas fa-chalkboard-teacher text-slate-400 mr-1.5"></i> {{ $k->dosen->nama ?? 'Belum ditentukan' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center text-slate-400 italic font-medium">
                            <div class="text-4xl text-slate-200 mb-3"><i class="fas fa-folder-open"></i></div>
                            Anda belum terdaftar dalam paket KRS semester ini. Hubungi bagian akademik.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-slate-50 border-t border-slate-200">
                    <tr>
                        <td colspan="2" class="px-6 py-4 text-right font-bold text-slate-700 text-sm">Total SKS Diambil :</td>
                        <td colspan="2" class="px-6 py-4">
                            <span class="text-sm font-black text-blue-600 bg-blue-100 px-4 py-2 rounded-xl">{{ $totalSKS }} SKS</span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection