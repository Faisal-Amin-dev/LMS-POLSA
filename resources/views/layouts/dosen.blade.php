<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - SERAP</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        polsa: {
                            DEFAULT: '#FBBF24',
                            hover: '#F59E0B',
                            dark: '#D97706',
                            light: '#FEF3C7'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased overflow-hidden" x-data="{ sidebarOpen: true, profileOpen: false }">

    <div class="flex h-screen w-full">

        <aside 
            class="bg-slate-900 text-slate-300 transition-all duration-300 ease-in-out flex flex-col relative z-20"
            :class="sidebarOpen ? 'w-64' : 'w-20'">
            
            <div class="h-16 flex items-center justify-center border-b border-slate-700 bg-slate-800">
                <span class="font-bold text-polsa text-xl tracking-wider" x-show="sidebarOpen">SERAP</span>
                <i class="fas fa-bars text-polsa text-xl cursor-pointer" x-show="!sidebarOpen" @click="sidebarOpen = true"></i>
            </div>

            <button @click="sidebarOpen = !sidebarOpen" class="absolute -right-3 top-20 bg-polsa text-slate-900 rounded-full p-1 shadow-md hover:bg-polsa-hover">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 transition-transform duration-300" :class="sidebarOpen ? 'rotate-180' : ''">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </button>

            <nav class="flex-1 overflow-y-auto py-4 space-y-1">
    
                <a href="{{ route('dosen.dashboard') }}" class="flex items-center px-4 py-3 hover:bg-slate-800 hover:text-white text-slate-400 transition-colors">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="ml-4 font-medium whitespace-nowrap" x-show="sidebarOpen">Kelas Saya</span>
                </a>

                <a href="{{ route('dosen.jadwal') }}" class="flex items-center px-4 py-3 hover:bg-slate-800 hover:text-white text-slate-400 transition-colors">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="ml-4 font-medium whitespace-nowrap" x-show="sidebarOpen">Agenda Tugas</span>
                </a>

                <a href="{{ route('dosen.kelas.arsip') }}" class="flex items-center px-4 py-3 hover:bg-slate-800 hover:text-white text-slate-400 transition-colors">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span class="ml-4 font-medium whitespace-nowrap" x-show="sidebarOpen">Kelas Diarsip</span>
                </a>

                <a href="{{ route('dosen.arsip.nilai') }}" class="flex items-center px-4 py-3 hover:bg-slate-800 hover:text-white text-slate-400 transition-colors">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="ml-4 font-medium whitespace-nowrap" x-show="sidebarOpen">Arsip Nilai</span>
                </a>

            </nav>
        </aside>

        <main class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50 relative z-10">
            
            <header class="h-16 bg-white shadow-sm flex items-center justify-between px-6 z-10">
    
            <div class="text-xl font-semibold text-slate-800">
                @yield('header_title', 'Dashboard')
            </div>

            <div class="flex items-center space-x-4">
                
                <button type="button" class="relative p-2 text-slate-500 hover:text-slate-800 transition-colors rounded-full hover:bg-slate-100" aria-label="Notifikasi">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>                   
                        <span class="absolute top-1 right-1 h-2.5 w-2.5 rounded-full bg-red-500 border border-white"></span>                        
                </button>

                <div class="relative" @click.away="profileOpen = false">
                    <button @click="profileOpen = !profileOpen" class="flex items-center space-x-3 focus:outline-none">
                        <div class="text-right hidden md:block">
                            <p class="text-sm font-semibold text-slate-700">{{ Auth::user()->name ?? 'Dosen Pengajar' }}</p>
                            
                            <p class="text-xs text-slate-500 font-bold text-blue-600">Dosen Praktikum</p> 
                        </div>
                        <div class="h-9 w-9 rounded-full bg-slate-200 overflow-hidden border border-slate-300">
                            @if(Auth::user()->foto)
                                <img src="{{ Storage::url(Auth::user()->foto) }}" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full flex items-center justify-center text-slate-500 bg-slate-100">
                                    <i class="fas fa-user text-sm"></i>
                                </div>
                            @endif
                        </div>
                    </button>

                    <div x-show="profileOpen" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 border border-slate-100" style="display: none;">
                        
                        <button type="button" data-bs-toggle="modal" data-bs-target="#modalPengaturanProfil" class="flex items-center w-full px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-all gap-2">
                            <i class="fas fa-cog text-slate-400"></i> Pengaturan
                        </button>
                        
                        <div class="border-t border-slate-100 my-1"></div>
                        
                        <form method="POST" action="{{ route('logout') ?? '/logout' }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <svg class="inline-block w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                Keluar Sistem
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </div>
            
        </main>
    </div>
    <div class="modal fade" id="modalPengaturanProfil" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" style="background-color: rgba(0,0,0,0.5); z-index: 9999;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3xl border-none shadow-2xl">
                <div class="modal-header border-none p-6 pb-0">
                    <h5 class="text-xl font-bold text-slate-800">Pengaturan Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-6">
                        
                        <div class="mb-4">
                            <label class="text-xs font-bold text-slate-400 uppercase mb-2 block">Username / Email (Tidak dapat diubah)</label>
                            <input type="text" class="w-full bg-slate-100 border border-slate-200 px-4 py-2.5 rounded-xl text-sm text-slate-500 font-mono" value="{{ Auth::user()->email }}" disabled>
                        </div>

                        <div class="mb-4">
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Foto Profil Baru</label>
                            <div class="flex items-center gap-4">
                                <div class="h-14 w-14 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden flex-shrink-0">
                                    @if(Auth::user()->foto)
                                        <img src="{{ Storage::url(Auth::user()->foto) }}" class="h-full w-full object-cover">
                                    @else
                                        <i class="fas fa-user text-slate-400 text-xl"></i>
                                    @endif
                                </div>
                                <input type="file" name="foto" accept="image/*" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
                            </div>
                        </div>

                        <hr class="border-slate-100 my-4">

                        <div class="mb-4">
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Password Baru</label>
                            <input type="password" name="password" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Kosongkan jika tidak ingin mengubah">
                        </div>

                        <div class="mb-2">
                            <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="w-full bg-white border border-slate-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Ulangi password baru">
                        </div>

                    </div>
                    <div class="modal-footer border-none p-6 pt-0 flex gap-2">
                        <button type="button" class="flex-1 py-3 text-slate-500 font-bold hover:bg-slate-50 rounded-xl" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition-all">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('modals')
</body>
</html>