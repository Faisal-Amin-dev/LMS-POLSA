@extends('layouts.mahasiswa')

@section('title', 'KRS Paket Akademik')
@section('header_title', 'Kartu Rencana Studi')

@section('content')
<div class="container mx-auto p-4">
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r-xl font-bold text-sm">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r-xl font-bold text-sm">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm mb-6">
                <div class="mb-4">
                    <h3 class="text-xl font-bold text-slate-800"><i class="fas fa-edit text-blue-500 mr-2"></i>Pilihan Paket Matkul Tersedia</h3>
                    <p class="text-slate-500 text-xs mt-1">Daftar mata kuliah di bawah ini otomatis disaring berdasarkan <strong>Kurikulum Aktif: {{ $kurikulumAktif->nama_kurikulum ?? 'Belum Ditentukan' }}</strong>.</p>
                </div>

                @if(!$kurikulumAktif)
                    <div class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-300 text-slate-400 italic">
                        <i class="fas fa-book-reader text-4xl mb-2 text-slate-300 block"></i>
                        Kaprodi belum mengaktifkan acuan kurikulum untuk program studi Anda semester ini.
                    </div>
                @else
                    <form action="{{ route('mahasiswa.krs.store') }}" method="POST">
                        @csrf
                        <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2 mb-4">
                            @forelse($courses as $c)
                                <label class="flex items-center justify-between p-4 bg-slate-50 border border-slate-200 rounded-2xl cursor-pointer hover:border-blue-500 hover:bg-blue-50/20 transition-all">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="course_ids[]" value="{{ $c->id }}" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                                        <div class="ml-4">
                                            <span class="text-xs font-mono font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">{{ $c->kode_mk }}</span>
                                            <div class="text-sm font-bold text-slate-800 mt-1">{{ $c->nama_mk }}</div>
                                        </div>
                                    </div>
                                    <div class="text-xs font-bold text-slate-500 bg-white border border-slate-200 px-3 py-1 rounded-xl">
                                        {{ $c->sks ?? 2 }} SKS
                                    </div>
                                </label>
                            @empty
                                <div class="text-center p-6 text-slate-400 italic">Belum ada daftar mata kuliah pokok di kurikulum ini.</div>
                            @endforelse
                        </div>

                        @if($courses->count() > 0)
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-blue-100 transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-file-signature"></i> Kontrak Paket Mata Kuliah
                            </button>
                        @endif
                    </form>
                @endif
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-slate-900 text-white p-6 rounded-3xl shadow-xl sticky top-6">
                <div class="border-b border-white/10 pb-4 mb-4">
                    <h3 class="font-bold text-md flex items-center gap-2"><i class="fas fa-receipt text-yellow-400"></i> KRS Sah Semester Ini</h3>
                    <p class="text-slate-400 text-[11px] mt-0.5">Sesi Terkunci: {{ session('tahun_akademik_nama') }}</p>
                </div>

                <div class="space-y-3 max-h-[300px] overflow-y-auto mb-4 pr-1">
                    @forelse($krsSudahDiambil as $krs)
                        <div class="bg-white/5 border border-white/10 p-3 rounded-xl flex justify-between items-center">
                            <div>
                                <div class="text-xs font-bold text-slate-200">{{ $krs->nama_mk }}</div>
                                <div class="text-[10px] text-slate-400 font-mono mt-1">{{ $krs->kode_mk }}</div>
                            </div>
                            <div class="text-[11px] font-bold text-yellow-400 bg-yellow-400/10 px-2.5 py-1 rounded-lg">
                                {{ $krs->sks }} SKS
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-500 text-xs italic">
                            <i class="fas fa-file-invoice text-2xl mb-2 text-slate-600 block"></i>
                            Belum ada mata kuliah yang dikontrak untuk semester ini.
                        </div>
                    @endforelse
                </div>

                <div class="border-t border-white/10 pt-4 flex justify-between items-center text-sm font-bold">
                    <span class="text-slate-400">Total Beban SKS:</span>
                    <span class="text-xl text-yellow-400">{{ $krsSudahDiambil->sum('sks') }} <small class="text-xs text-white">SKS</small></span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection