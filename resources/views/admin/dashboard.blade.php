@extends('layouts.admin')

@section('title', 'Dashboard SERAP')
@section('header_title', 'Ringkasan Sistem')

@section('content')
@php
    $statusConfig = [
        'online' => ['color' => 'bg-green-500', 'text' => 'Online', 'desc' => 'Terkoneksi dengan API Kampus'],
        'standby' => ['color' => 'bg-yellow-500', 'text' => 'Standby', 'desc' => 'Menunggu Respon API'],
        'disconnected' => ['color' => 'bg-red-500', 'text' => 'Terputus', 'desc' => 'Server API Tidak Dapat Dihubungi'],
    ];
    $current = $statusConfig[$apiStatus] ?? $statusConfig['disconnected'];
@endphp

<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Selamat Datang, {{ Auth::user()->name ?? 'Pakdhe' }}!</h2>
    <p class="text-slate-600">Dashboard Admin SERAP — Data terpantau secara real-time.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Mahasiswa</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['total_mahasiswa'] }}</h3>
            </div>
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                <i class="fas fa-user-graduate text-xl"></i>
            </div>
        </div>
        <div class="w-full bg-slate-100 rounded-full h-1.5 mt-4 mb-2">
            @php 
                $persentaseApi = $stats['total_mahasiswa'] > 0 ? ($stats['mahasiswa_api'] / $stats['total_mahasiswa']) * 100 : 0; 
            @endphp
            <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $persentaseApi }}%"></div>
        </div>
        <p class="text-[10px] text-slate-400">Terdaftar di SERAP</p>

    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Total Dosen</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['total_dosen'] }}</h3>
            </div>
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                <i class="fas fa-chalkboard-teacher text-xl"></i>
            </div>
        </div>
        <p class="text-[10px] text-slate-400 mt-8">Staf pengajar aktif</p>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all border-l-4 border-l-yellow-400">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Program Studi</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['total_prodi'] }}</h3>
            </div>
            <div class="p-3 bg-yellow-50 text-yellow-600 rounded-xl">
                <i class="fas fa-university text-xl"></i>
            </div>
        </div>
        <p class="text-[10px] text-slate-400 mt-8">Data master prodi aktif</p>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Status API</p>
                <h3 class="text-lg font-bold mt-1 text-slate-800">{{ $current['text'] }}</h3>
            </div>
            <span class="relative flex h-3 w-3 mt-1">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $current['color'] }} opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 {{ $current['color'] }}"></span>
            </span>
        </div>
        <p class="text-[10px] text-slate-400 mt-6">{{ $current['desc'] }}</p>
    </div>

</div>
@endsection