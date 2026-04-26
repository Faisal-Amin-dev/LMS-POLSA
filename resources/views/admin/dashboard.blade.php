@extends('layouts.admin')

@section('title', 'Dashboard SERAP')
@section('header_title', 'Ringkasan Sistem')

@section('content')
@php
    // Mapping warna dan teks berdasarkan status API
    $statusConfig = [
        'online' => ['color' => 'bg-green-500', 'text' => 'Online', 'desc' => 'Terkoneksi dengan API Kampus'],
        'standby' => ['color' => 'bg-yellow-500', 'text' => 'Standby', 'desc' => 'Menunggu Respon API'],
        'disconnected' => ['color' => 'bg-red-500', 'text' => 'Terputus', 'desc' => 'Server API Tidak Dapat Dihubungi'],
    ];
    $current = $statusConfig[$apiStatus] ?? $statusConfig['disconnected'];
@endphp

<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Selamat Datang, {{ Auth::user()->name ?? 'Admin' }}!</h2>
    <p class="text-slate-600">Pusat kendali SERAP (Sistem Edukasi & Rekap Akademik Polsa).</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-sm text-slate-500 font-medium">TOTAL MAHASISWA</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['total_mahasiswa'] }}</h3>
            </div>
            <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                <i class="fas fa-user-graduate text-xl"></i>
            </div>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-1.5 mb-2 mt-4">
            @php 
                $persentaseApi = $stats['total_mahasiswa'] > 0 ? ($stats['mahasiswa_api'] / $stats['total_mahasiswa']) * 100 : 0; 
            @endphp
            <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $persentaseApi }}%"></div>
        </div>
        <p class="text-xs text-slate-500">
            <span class="font-semibold text-slate-700">{{ $stats['mahasiswa_api'] }}</span> dari API | 
            <span class="font-semibold text-slate-700">{{ $stats['mahasiswa_local'] }}</span> Manual
        </p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-sm text-slate-500 font-medium">TOTAL DOSEN</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['total_dosen'] }}</h3>
            </div>
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg">
                <i class="fas fa-chalkboard-teacher text-xl"></i>
            </div>
        </div>
        <p class="text-xs text-slate-500 mt-8">Mengajar pada semester aktif</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-sm text-slate-500 font-medium uppercase tracking-wider">Status API Siap</p>
                <h3 class="text-xl font-bold mt-1 text-slate-800">{{ $current['text'] }}</h3>
            </div>
            <span class="relative flex h-4 w-4 mt-1">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $current['color'] }} opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 {{ $current['color'] }}"></span>
            </span>
        </div>
        <p class="text-xs text-slate-500 mt-8">{{ $current['desc'] }}</p>
    </div>

</div>
@endsection