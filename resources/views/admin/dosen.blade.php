@extends('layouts.admin')

@section('title', 'Data Dosen')
@section('header_title', 'Manajemen Data Dosen')

@section('content')
<div class="p-4">
<div class="flex gap-2">
            <form action="{{ route('admin.dosen.sync') }}" method="POST">
                @csrf
                <button type="submit" class="text-white font-bold py-2.5 px-4 rounded-xl shadow-sm flex items-center transition-all hover:scale-105 bg-indigo-600 hover:bg-indigo-700" onclick="return confirm('Tarik data dosen terbaru dari API?');">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Sync API
                </button>
            </form>

            <button type="button" data-bs-toggle="modal" data-bs-target="#modalTambahDosen" class="text-slate-900 font-bold py-2.5 px-4 rounded-xl shadow-sm flex items-center transition-all hover:scale-105" style="background-color: #FFD700;">
                <i class="fas fa-plus-circle mr-2"></i>
                Tambah Manual 
            </button>
        </div>

    @if(session('success'))
        <div class="alert alert-dismissible fade show border-0 shadow-sm text-dark mb-4" style="background-color: #FFF3CD;" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3 text-xl text-warning"></i>
                <div>
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider font-bold">
                        <th class="px-6 py-4">Dosen</th>
                        <th class="px-6 py-4">Kontak</th>
                        <th class="px-6 py-4">Bidang Keahlian</th>
                        <th class="px-6 py-4 text-center">Sumber</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($dosens as $dsn)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold border-2 border-white shadow-sm">
                                    {{ strtoupper(substr($dsn->nama, 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-slate-800">{{ $dsn->nama }}</div>
                                    <div class="text-xs text-slate-500 font-mono">NIDN: {{ $dsn->nidn }}</div>
                                </div>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $dsn->user->email ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="text-sm text-slate-800 font-medium">{{ $dsn->bidang_keahlian ?? 'Belum ditentukan' }}</div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if(($dsn->user->source ?? 'local') == 'siap_polsa')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                    <i class="fas fa-cloud mr-1.5"></i> API SIAP
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    <i class="fas fa-keyboard mr-1.5"></i> Manual
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center items-center gap-3">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $dsn->id }}" class="text-slate-400 hover:text-indigo-600 transition-colors p-2 hover:bg-indigo-50 rounded-lg">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <form action="{{ route('admin.dosen.destroy', $dsn->id) }}" method="POST" onsubmit="return confirm('Hapus dosen ini? Akun login juga akan terhapus.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-red-600 transition-colors p-2 hover:bg-red-50 rounded-lg">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-chalkboard-teacher text-5xl mb-4 opacity-20"></i>
                                <p class="text-lg font-medium">Belum ada data dosen.</p>
                                <p class="text-sm">Klik tombol "Tambah Dosen" untuk memulai.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('modals')
    <div class="modal fade" id="modalTambahDosen" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden">
                <div class="modal-header border-0 p-4" style="background-color: #FFD700;">
                    <h5 class="modal-title fw-bold text-dark"><i class="fas fa-user-plus mr-2"></i> Tambah Dosen Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.dosen.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4 bg-white">
                        <div class="mb-3">
                            <label class="form-label font-bold text-slate-700">NIDN</label>
                            <input type="text" name="nidn" class="form-control rounded-xl border-slate-200 p-2.5" placeholder="Nomor Induk Dosen Nasional" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label font-bold text-slate-700">Nama Lengkap & Gelar</label>
                            <input type="text" name="nama" class="form-control rounded-xl border-slate-200 p-2.5" placeholder="Contoh: Budi Santoso, M.Kom." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label font-bold text-slate-700">Email</label>
                            <input type="email" name="email" class="form-control rounded-xl border-slate-200 p-2.5" placeholder="email@polsa.ac.id" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label font-bold text-slate-700">Bidang Keahlian</label>
                            <input type="text" name="bidang_keahlian" class="form-control rounded-xl border-slate-200 p-2.5" placeholder="Contoh: Rekayasa Perangkat Lunak">
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-xl px-4 py-2 font-semibold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-dark rounded-xl px-5 py-2 font-bold shadow-sm">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach($dosens as $dsn)
    <div class="modal fade" id="modalEdit{{ $dsn->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden">
                <div class="modal-header border-0 p-4 bg-slate-900 text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-edit mr-2"></i> Update Data Dosen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.dosen.update', $dsn->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label font-bold text-slate-700">NIDN</label>
                            <input type="text" name="nidn" class="form-control rounded-xl" value="{{ $dsn->nidn }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label font-bold text-slate-700">Nama Lengkap & Gelar</label>
                            <input type="text" name="nama" class="form-control rounded-xl" value="{{ $dsn->nama }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label font-bold text-slate-700">Email</label>
                            <input type="email" name="email" class="form-control rounded-xl" value="{{ $dsn->user->email ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label font-bold text-slate-700">Bidang Keahlian</label>
                            <input type="text" name="bidang_keahlian" class="form-control rounded-xl" value="{{ $dsn->bidang_keahlian }}">
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-xl px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-xl px-5 font-bold shadow-sm">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endpush