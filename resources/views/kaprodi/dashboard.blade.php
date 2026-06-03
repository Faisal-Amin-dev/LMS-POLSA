@extends('layouts.dosen')

@section('title', 'Monitoring Kaprodi')
@section('header_title', 'Dashboard Ketua Program Studi')

@section('content')
<div class="container mx-auto p-4">
    <div class="mb-6">
        <h2 class="text-2xl font-black text-slate-800">Monitoring Kelengkapan Akademik</h2>
        <p class="text-slate-500 text-sm">Pantau proses pengunggahan Dokumen Mutu (Kontrak Kuliah & RPS) oleh dosen di prodi Anda.</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-xs font-bold uppercase tracking-wider">
                    <th class="px-6 py-4">Mata Kuliah / Kelas</th>
                    <th class="px-6 py-4">Dosen Pengampu</th>
                    <th class="px-6 py-4 text-center">Status RPS / Kontrak</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                @forelse($monitoringKelas as $k)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-800">{{ $k->nama_mk }}</div>
                        <div class="text-[10px] text-blue-600 font-mono mt-0.5">{{ $k->kode_mk }} - Kelas {{ $k->nama_kelas }}</div>
                    </td>
                    <td class="px-6 py-4 font-medium text-slate-600">{{ $k->nama_dosen }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($k->file_rps)
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                                <i class="fas fa-check-circle mr-1"></i> Sudah Unggah RPS
                            </span>
                        @else
                            <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-full text-xs font-bold animate-pulse">
                                <i class="fas fa-times-circle mr-1"></i> Belum Ada RPS
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-6 py-12 text-center text-slate-400 italic">Belum ada data kelas aktif di prodi ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection