@extends('layouts.dosen')

@section('title', 'Audit Mutu BPM')
@section('header_title', 'Dashboard Monitoring Audit Mutu Internal')

@section('content')
<div class="container mx-auto p-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase">Total Kelas Aktif</p>
                <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $totalKelas }} Kelas</h3>
            </div>
            <div class="h-12 w-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl"><i class="fas fa-university"></i></div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase">RPS Terkumpul</p>
                <h3 class="text-2xl font-black text-green-600 mt-1">{{ $sudahUploadRps }} Kelas</h3>
            </div>
            <div class="h-12 w-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-xl"><i class="fas fa-file-check"></i></div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase">RPS Belum Lengkap</p>
                <h3 class="text-2xl font-black text-rose-600 mt-1">{{ $belumUploadRps }} Kelas</h3>
            </div>
            <div class="h-12 w-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center text-xl"><i class="fas fa-exclamation-circle"></i></div>
        </div>
    </div>

    <div class="bg-slate-900 text-white p-6 rounded-3xl shadow-sm overflow-hidden">
        <h3 class="text-lg font-bold mb-4"><i class="fas fa-clipboard-check text-yellow-400 mr-2"></i>Status Unggah Dokumen RPS Seluruh Kampus</h3>
        <div class="overflow-x-auto rounded-xl">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-white/5 font-bold text-slate-400 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-4">Mata Kuliah / Kelas</th>
                        <th class="px-6 py-4">Dosen Mengampu</th>
                        <th class="px-6 py-4 text-center">Status Audit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($auditGlobal as $row)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4 font-bold text-white">{{ $row->nama_mk }} <span class="text-xs font-mono font-normal text-slate-400 ml-2">({{ $row->nama_kelas }})</span></td>
                        <td class="px-6 py-4 text-slate-300">{{ $row->nama_dosen }}</td>
                        <td class="px-6 py-4 text-center">
                            {!! $row->file_rps ? '<span class="text-green-400 font-bold"><i class="fas fa-shield-check mr-1"></i> AMAN</span>' : '<span class="text-rose-400 font-bold animate-pulse"><i class="fas fa-exclamation-triangle mr-1"></i> AUDIT</span>' !!}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-12 text-center text-slate-500 italic">Tidak ada kelas terdaftar semester ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection