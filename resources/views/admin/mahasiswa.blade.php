@extends('layouts.admin')

@section('title', 'Data Mahasiswa')
@section('header_title', 'Manajemen Mahasiswa')

@section('content')
<div class="p-4">
    
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
            <p class="font-bold">Berhasil!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
            <p class="font-bold">Error Sistem!</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Daftar Mahasiswa</h2>
            <p class="text-slate-500 text-sm">Kelola data mahasiswa, akun login, dan status akademik.</p>
        </div>
        
        <div class="flex gap-2">
            <form action="{{ route('admin.mahasiswa.sync') }}" method="POST" class="m-0" onsubmit="return confirm('Tarik data mahasiswa dari API?')">
                @csrf
                <button type="submit" class="h-11 px-5 bg-blue-600 text-white font-bold rounded-xl shadow-sm transition-all hover:bg-blue-700 flex items-center justify-center">
                    <i class="fas fa-sync-alt mr-2"></i> Sync API
                </button>
            </form>
            <button type="button" data-bs-toggle="modal" data-bs-target="#modalTambahMahasiswa" class="h-11 px-5 bg-slate-800 text-white font-bold rounded-xl shadow-sm transition-all hover:bg-slate-700 flex items-center justify-center">
                <i class="fas fa-user-plus mr-2"></i> Tambah Mahasiswa
            </button>
        </div>
    </div>

    <div class="bg-white p-4 rounded-2xl mb-6 border border-slate-100 shadow-sm">
        <form action="{{ route('admin.mahasiswa') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="text-xs font-bold text-slate-400 uppercase mb-2 block">Filter Prodi</label>
                <select name="prodi" class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all">
                    <option value="">Semua Program Studi</option>
                    @foreach($daftarProdi as $p)
                        <option value="{{ $p }}" {{ request('prodi') == $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-32">
                <label class="text-xs font-bold text-slate-400 uppercase mb-2 block">Semester</label>
                <select name="semester" class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all">
                    <option value="">Semua</option>
                    @foreach([1,2,3,4,5,6,7,8] as $s)
                        <option value="{{ $s }}" {{ request('semester') == $s ? 'selected' : '' }}>SMT {{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-slate-100 text-slate-600 px-6 py-2.5 rounded-xl font-bold hover:bg-slate-200 transition-all">
                <i class="fas fa-filter mr-2"></i> Terapkan
            </button>
            @if(request('prodi') || request('semester'))
                <a href="{{ route('admin.mahasiswa') }}" class="text-red-500 text-xs underline mb-3">Reset</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold">
                <tr>
                    <th class="px-6 py-4">Mahasiswa</th>
                    <th class="px-6 py-4">Kontak</th>
                    <th class="px-6 py-4">Akademik</th>
                    <th class="px-6 py-4">Sumber</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($mahasiswas as $m)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-800">{{ $m->nama }}</div>
                        <div class="text-xs text-slate-400 font-mono">{{ $m->nim }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-slate-600">{{ $m->user->email ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-slate-700">{{ $m->prodi }}</div>
                        <div class="flex gap-2 mt-1">
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-bold uppercase rounded border border-blue-100">SMT {{ $m->semester }}</span>
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold uppercase rounded border border-slate-200">Kelas {{ $m->kelas }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-[10px] font-bold uppercase {{ ($m->user->source ?? '') == 'api_siap' ? 'text-blue-500' : 'text-slate-400' }}">{{ $m->user->source ?? 'local' }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button type="button" 
                                class="btn-edit p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                data-id="{{ $m->id }}"
                                data-nim="{{ $m->nim }}"
                                data-nama="{{ $m->nama }}"
                                data-email="{{ $m->user->email ?? '' }}"
                                data-prodi="{{ $m->prodi }}"
                                data-semester="{{ $m->semester }}"
                                data-kelas="{{ $m->kelas }}"
                                data-bs-toggle="modal" 
                                data-bs-target="#modalEditMahasiswa">
                                <i class="fas fa-edit"></i>
                            </button>

                            <form action="{{ route('admin.mahasiswa.destroy', $m->id) }}" method="POST" class="m-0" onsubmit="return confirm('Yakin ingin menghapus {{ $m->nama }}? Akun loginnya juga akan hilang!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Data belum tersedia. Silakan Sync API atau input manual.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalTambahMahasiswa" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3xl border-none shadow-2xl">
            <div class="modal-header border-none p-6 pb-0">
                <h5 class="text-xl font-bold text-slate-800">Tambah Mahasiswa Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.mahasiswa.store') }}" method="POST">
                @csrf
                <div class="modal-body p-6">
                    <div class="mb-4">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">NIM (Username)</label>
                        <input type="text" name="nim" class="w-full bg-white border border-slate-300 text-slate-800 px-4 py-2.5 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all placeholder:text-slate-400" required>
                    </div>
                    <div class="mb-4">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Nama Lengkap</label>
                        <input type="text" name="nama" class="w-full bg-white border border-slate-300 text-slate-800 px-4 py-2.5 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all placeholder:text-slate-400" required>
                    </div>
                    <div class="mb-4">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Email</label>
                        <input type="email" name="email" class="w-full bg-white border border-slate-300 text-slate-800 px-4 py-2.5 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all placeholder:text-slate-400" required>
                    </div>
                    <div class="mb-4">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Program Studi</label>
                        <select name="prodi" id="prodi_input" class="w-full bg-white border border-slate-300 text-slate-800 px-4 py-2.5 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all" required>
                            <option value="">Pilih Prodi Terlebih Dahulu</option>
                            @foreach($daftarProdi as $p)
                                <option value="{{ $p }}">{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Semester</label>
                            <select name="semester" id="semester_input" class="w-full bg-white border border-slate-300 text-slate-800 px-4 py-2.5 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all bg-slate-50" required disabled>
                                <option value="">Pilih Prodi Dulu</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Kelas</label>
                            <select name="kelas" id="kelas_input" class="w-full bg-white border border-slate-300 text-slate-800 px-4 py-2.5 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all bg-slate-50" required disabled>
                                <option value="">Pilih Semester Dulu</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-none p-6 pt-0 flex gap-2">
                    <button type="button" class="flex-1 py-3 text-slate-500 font-bold hover:bg-slate-50 rounded-xl" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-blue-600 text-white rounded-xl font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditMahasiswa" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3xl border-none shadow-2xl">
            <div class="modal-header border-none p-6 pb-0">
                <h5 class="text-xl font-bold text-slate-800">Edit Data Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditMahasiswa" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-6">
                    <div class="mb-4">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">NIM (Tidak bisa diubah)</label>
                        <input type="text" id="edit_nim" class="w-full bg-slate-100 border border-slate-300 text-slate-500 px-4 py-2.5 rounded-xl text-sm cursor-not-allowed" readonly>
                    </div>
                    <div class="mb-4">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Email (Tidak bisa diubah)</label>
                        <input type="email" id="edit_email" class="w-full bg-slate-100 border border-slate-300 text-slate-500 px-4 py-2.5 rounded-xl text-sm cursor-not-allowed" readonly>
                    </div>
                    <div class="mb-4">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Nama Lengkap</label>
                        <input type="text" name="nama" id="edit_nama" class="w-full bg-white border border-slate-300 text-slate-800 px-4 py-2.5 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all" required>
                    </div>
                    <div class="mb-4">
                        <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Program Studi</label>
                        <select name="prodi" id="edit_prodi_input" class="w-full bg-white border border-slate-300 text-slate-800 px-4 py-2.5 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all" required>
                            <option value="">Pilih Prodi</option>
                            @foreach($daftarProdi as $p)
                                <option value="{{ $p }}">{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Semester</label>
                            <select name="semester" id="edit_semester_input" class="w-full bg-white border border-slate-300 text-slate-800 px-4 py-2.5 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all" required>
                                <option value="">Pilih Prodi Dulu</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Kelas</label>
                            <select name="kelas" id="edit_kelas_input" class="w-full bg-white border border-slate-300 text-slate-800 px-4 py-2.5 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all" required>
                                <option value="">Pilih Semester Dulu</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-none p-6 pt-0 flex gap-2">
                    <button type="button" class="flex-1 py-3 text-slate-500 font-bold hover:bg-slate-50 rounded-xl" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-blue-600 text-white rounded-xl font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data Kampus POLSA
        const dataPolsa = {
            'Teknik Informatika (D3)': { singkatan: 'TI', max_smt: 6 },
            'Administrasi Bisnis (D3)': { singkatan: 'AB', max_smt: 6 },
            'Akuntansi (D3)': { singkatan: 'AK', max_smt: 6 },
            'TRPL (S1 Terapan)': { singkatan: 'TRPL', max_smt: 8 },
            'Bisnis Digital (S1 Terapan)': { singkatan: 'BD', max_smt: 8 }
        };

        // Fungsi Reusable untuk Setup Dropdown (Bisa dipakai Form Tambah & Edit)
        function setupDynamicDropdown(prodiEl, semesterEl, kelasEl) {
            prodiEl.addEventListener('change', function() {
                const prodi = this.value;
                semesterEl.innerHTML = '<option value="">Pilih Semester</option>';
                kelasEl.innerHTML = '<option value="">Pilih Semester Dulu</option>';
                kelasEl.disabled = true;

                if (prodi && dataPolsa[prodi]) {
                    semesterEl.disabled = false;
                    semesterEl.classList.remove('bg-slate-50');
                    const maxSmt = dataPolsa[prodi].max_smt;
                    for (let i = 1; i <= maxSmt; i++) {
                        semesterEl.appendChild(new Option(`Semester ${i}`, i));
                    }
                } else {
                    semesterEl.disabled = true;
                    semesterEl.classList.add('bg-slate-50');
                }
            });

            semesterEl.addEventListener('change', function() {
                const prodi = prodiEl.value;
                const semester = this.value;
                kelasEl.innerHTML = '<option value="">Pilih Kelas</option>';

                if (prodi && semester && dataPolsa[prodi]) {
                    kelasEl.disabled = false;
                    kelasEl.classList.remove('bg-slate-50');
                    const singkatan = dataPolsa[prodi].singkatan;
                    kelasEl.appendChild(new Option(`${singkatan} ${semester}A (Reguler)`, `${singkatan} ${semester}A`));
                    kelasEl.appendChild(new Option(`${singkatan} ${semester}B (Karyawan)`, `${singkatan} ${semester}B`));
                } else {
                    kelasEl.disabled = true;
                    kelasEl.classList.add('bg-slate-50');
                }
            });
        }

        // Terapkan ke Form Tambah
        setupDynamicDropdown(
            document.getElementById('prodi_input'), 
            document.getElementById('semester_input'), 
            document.getElementById('kelas_input')
        );

        // Terapkan ke Form Edit
        setupDynamicDropdown(
            document.getElementById('edit_prodi_input'), 
            document.getElementById('edit_semester_input'), 
            document.getElementById('edit_kelas_input')
        );

        // LOGIKA KETIKA TOMBOL EDIT DIKLIK
        const editButtons = document.querySelectorAll('.btn-edit');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Ambil data dari tombol
                const id = this.getAttribute('data-id');
                const nim = this.getAttribute('data-nim');
                const nama = this.getAttribute('data-nama');
                const email = this.getAttribute('data-email');
                const prodi = this.getAttribute('data-prodi');
                const semester = this.getAttribute('data-semester');
                const kelas = this.getAttribute('data-kelas');

                // Set Action URL form
                document.getElementById('formEditMahasiswa').action = `/admin/mahasiswa/${id}`;

                // Isi data ke inputan
                document.getElementById('edit_nim').value = nim;
                document.getElementById('edit_nama').value = nama;
                document.getElementById('edit_email').value = email;
                
                // Isi Dropdown Dinamis secara berurutan agar opsinya terbuat
                const prodiSelect = document.getElementById('edit_prodi_input');
                const semesterSelect = document.getElementById('edit_semester_input');
                const kelasSelect = document.getElementById('edit_kelas_input');

                prodiSelect.value = prodi;
                prodiSelect.dispatchEvent(new Event('change')); // Trigger event biar SMT muncul
                
                setTimeout(() => {
                    semesterSelect.value = semester;
                    semesterSelect.dispatchEvent(new Event('change')); // Trigger event biar Kelas muncul
                    
                    setTimeout(() => {
                        kelasSelect.value = kelas;
                    }, 50);
                }, 50);
            });
        });
    });
</script>
@endsection