@extends('layouts.admin')

@section('title', 'Data Dosen')
@section('header_title', 'Manajemen Data Dosen')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Daftar Dosen</h2>
            <p class="text-slate-500 text-sm">Kelola data dosen pengajar di lingkungan kampus.</p>
        </div>
        
        <button type="button" data-bs-toggle="modal" data-bs-target="#modalTambahDosen" class="text-slate-900 font-bold py-2 px-4 rounded-lg shadow-sm flex items-center transition-colors" style="background-color: #FFD700;">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Dosen
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">No</th>
                        <th class="px-6 py-4 font-bold">NIDN</th>
                        <th class="px-6 py-4 font-bold">Nama Lengkap</th>
                        <th class="px-6 py-4 font-bold">Email</th>
                        <th class="px-6 py-4 font-bold">Prodi</th>
                        <th class="px-6 py-4 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($dosens as $index => $dosen)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-800">{{ $dosen->nidn }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $dosen->name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $dosen->email }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $dosen->prodi }}</td>
                            <td class="px-6 py-4 text-center">
                                <button class="text-red-600 hover:text-red-900 font-medium text-sm">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                Belum ada data dosen.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @push('modals')
    <div class="modal fade" id="modalTambahDosen" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0" style="background-color: #FFD700;">
                    <h5 class="modal-title fw-bold text-dark">Form Tambah Dosen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger" style="background-color: #ffebee; color: #c62828; p: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <p><strong>Aduh Pakdhe, datanya ditolak:</strong></p>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success" style="background-color: #e8f5e9; color: #2e7d32; p: 15px; border-radius: 8px; margin-bottom: 20px;">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('admin.dosen.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">NIDN</label>
                            <input type="text" name="nidn" class="form-control rounded-3" placeholder="Masukkan NIDN" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control rounded-3" placeholder="Masukkan Nama Lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control rounded-3" placeholder="Masukkan Email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Username</label>
                            <input type="text" name="username" class="form-control rounded-3" placeholder="Contoh: fachry99" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password Login</label>
                            <input type="password" name="password" class="form-control rounded-3" placeholder="Minimal 8 karakter" required>
                            <small class="text-muted">Password ini akan digunakan dosen untuk login.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Program Studi</label>
                            <select name="prodi" class="form-select rounded-3" required>
                                <option value="">Pilih Prodi</option>
                                <option value="Teknik Informatika">Teknik Informatika</option>
                                <option value="Akuntansi">Akuntansi</option>
                                <option value="Administrasi Bisnis">Administrasi Bisnis</option>
                                <option value="Teknik Rekayasa Perangkat Lunak">Teknik Rekayasa Perangkat Lunak</option>
                                <option value="Bisnis Digital">Bisnis Digital</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-dark rounded-pill px-4">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endpush
@endsection