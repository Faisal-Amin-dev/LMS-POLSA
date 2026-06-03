@extends('layouts.admin')

@section('title', 'Set Jabatan Dosen')
@section('header_title', 'Struktural & Penugasan Dosen')

@section('content')
<div class="container mx-auto">
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r-xl text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Manajemen Jabatan Dosen</h2>
        <p class="text-slate-500 text-sm">Berikan penugasan struktural sebagai Kaprodi atau BPM untuk mengaktifkan fitur monitoring perkuliahan.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-xs font-bold uppercase tracking-wider">
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">NIDN</th>
                    <th class="px-6 py-4">Nama Lengkap Dosen</th>
                    <th class="px-6 py-4 text-center">Jabatan Aktif</th>
                    <th class="px-6 py-4 text-center">Ubah Penugasan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                @forelse($dosens as $index => $d)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-4 font-medium text-slate-500">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 font-mono font-semibold text-slate-600">{{ $d->nidn ?? '-' }}</td>
                    <td class="px-6 py-4 font-bold text-slate-800">{{ $d->nama }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($d->jabatan == 'Kaprodi')
                            <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-black">
                                <i class="fas fa-user-tie mr-1"></i> Ketua Prodi
                            </span>
                        @elseif($d->jabatan == 'BPM')
                            <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-black">
                                <i class="fas fa-shield-alt mr-1"></i> Tim BPM
                            </span>
                        @else
                            <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-xs font-medium">
                                Dosen Biasa
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.dosen.updateJabatan', $d->id) }}" method="POST" class="flex items-center justify-center gap-2">
                            @csrf
                            @method('PUT')
                            <select name="jabatan" class="bg-slate-50 border border-slate-300 text-xs rounded-xl px-2 py-1.5 focus:ring-1 focus:ring-blue-500 outline-none text-slate-700">
                                <option value="Dosen" {{ $d->jabatan == 'Dosen' ? 'selected' : '' }}>Dosen</option>
                                <option value="Kaprodi" {{ $d->jabatan == 'Kaprodi' ? 'selected' : '' }}>Kaprodi</option>
                                <option value="BPM" {{ $d->jabatan == 'BPM' ? 'selected' : '' }}>BPM</option>
                            </select>
                            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white text-xs px-2.5 py-1.5 rounded-xl font-bold shadow transition-all">
                                Set
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">
                        <i class="fas fa-users-slash text-3xl mb-2 block text-slate-300"></i>
                        Belum ada data master dosen untuk diset jabatannya.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection