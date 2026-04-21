@extends('layouts.dosen')

@section('title', 'Jadwal Praktikum')
@section('header_title', 'Jadwal Praktikum Mingguan')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-xl font-bold text-slate-800">Agenda Mengajar</h2>
            <p class="text-sm text-slate-500">Daftar jadwal praktikum aktif Anda di semester ini.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-400 text-xs uppercase tracking-wider">
                        <th class="p-4 font-bold border-b">Hari</th>
                        <th class="p-4 font-bold border-b">Mata Kuliah</th>
                        <th class="p-4 font-bold border-b">Jam</th>
                        <th class="p-4 font-bold border-b">Ruangan</th>
                        <th class="p-4 font-bold border-b">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($jadwal as $j)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold">
                                {{ $j->day }}
                            </span>
                        </td>
                        <td class="p-4 font-bold text-slate-800">{{ $j->course->course_name }}</td>
                        <td class="p-4 text-sm text-slate-600">
                            {{ \Carbon\Carbon::parse($j->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->end_time)->format('H:i') }}
                        </td>
                        <td class="p-4 text-sm text-slate-500">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="2"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"/></svg>
                                {{ $j->room }}
                            </div>
                        </td>
                        <td class="p-4">
                            <a href="{{ route('dosen.kelas.show', $j->course_id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-bold">Masuk Kelas</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center text-slate-400 italic">Belum ada jadwal yang diinput.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection