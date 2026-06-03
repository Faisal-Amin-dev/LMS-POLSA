<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SERAP - Sistem Edukasi Ruang Akademik Polsa</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  <style>
    /* Transisi halus untuk elemen yang muncul saat di-scroll */
    .fade-in {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.8s ease-out, transform 0.8s ease-out;
    }
    .fade-in.visible {
        opacity: 1;
        transform: translateY(0);
    }

        /* Animasi mengambang gambar/vector (naik turun) */
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
        100% { transform: translateY(0px); }
    }

    .floating-image {
        animation: float 4s ease-in-out infinite;
    }
  </style>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-900">

<nav id="navbar" class="fixed top-0 w-full z-50 transition-all duration-300 bg-white/90 backdrop-blur-md shadow-sm py-3 border-b border-gray-200">
    <div class="container mx-auto px-6 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-900 rounded-full flex items-center justify-center text-white font-bold">
                P
            </div>
            <div class="flex flex-col">
                <span class="text-blue-900 font-bold text-xl tracking-tight leading-none">SERAP</span>
                <span class="text-xs text-gray-500 font-medium mt-1">Politeknik Sawunggalih Aji</span>
            </div>
        </div>

        <div class="md:hidden">
            <a href="{{ url('/login') }}" class="bg-blue-900 text-white p-2.5 rounded-xl shadow-md flex items-center justify-center transition-all active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
            </a>
        </div>

        <div class="hidden md:flex items-center space-x-8">
            <a href="#" class="text-gray-600 hover:text-blue-900 font-medium text-sm">Beranda</a>
            <a href="#informasi" class="text-gray-600 hover:text-blue-900 font-medium text-sm">Panduan</a>
            <a href="{{ url('/login') }}" class="bg-blue-900 text-white px-5 py-2 rounded-lg font-semibold hover:bg-blue-800 transition-all text-sm shadow-md">
                Portal Login
            </a>
        </div>
    </div>
</nav>

<div class="relative min-h-screen pt-24 pb-12 flex items-center bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 20px 20px;"></div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
            
            <div class="flex-1 text-center lg:text-left text-white fade-in visible">
                <span class="inline-block py-1 px-3 rounded-full bg-blue-800/50 border border-blue-400/30 text-blue-200 text-sm font-semibold mb-6">
                    Tahun Akademik {{ $semAktif ? $semAktif->tahun_akademik . ' (' . $semAktif->semester . ')' : '2025/2026' }}
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                    Portal Akademik <br />
                    <span class="text-yellow-400">Terintegrasi</span>
                </h1>
                <p class="text-lg text-blue-100 mb-8 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                    Akses layanan akademik, jadwal perkuliahan, penugasan, dan informasi kampus dalam satu sistem informasi terpadu Politeknik Sawunggalih Aji.
                </p>
                <div class="flex flex-wrap justify-center lg:justify-start gap-4">
                    <a href="{{ url('/login') }}" class="bg-yellow-400 hover:bg-yellow-500 text-blue-900 px-8 py-3 rounded-lg font-bold transition shadow-lg flex items-center gap-2">
                        <span>Masuk ke SERAP</span>
                    </a>
                    
                    <a href="#informasi" class="bg-white/10 hover:bg-white/20 border border-white/30 text-white px-6 py-3 rounded-lg font-medium transition backdrop-blur-sm">
                        Pelajari Sistem
                    </a>
                </div>
            </div>
            <!-- gambar/vector naik turun -->
               <div class="flex flex-1 justify-center items-center py-10 lg:py-0 fade-in visible">
                    <div class="relative w-full max-w-[280px] md:max-w-md">
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-48 md:w-80 md:h-80 bg-yellow-400/20 rounded-full blur-3xl"></div>
                        
                        <img src="{{ asset('img/hero-vector.png') }}" 
                            alt="SERAP Vector" 
                            class="relative z-10 w-full h-auto drop-shadow-2xl floating-image">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section id="informasi" class="bg-white py-24">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16 fade-in">
            <h2 class="text-3xl font-bold text-gray-900">Fitur SERAP</h2>
            <div class="w-20 h-1 bg-yellow-400 mx-auto mt-4 rounded-full"></div>
            <p class="mt-4 text-gray-600 max-w-2xl mx-auto">Sistem kami dirancang untuk mempermudah kegiatan akademik antara mahasiswa dan dosen.</p>
        </div>
        
        <div class="grid gap-8 md:grid-cols-3">
            <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100 hover:shadow-lg transition-shadow fade-in">
                <div class="w-14 h-14 bg-blue-100 text-blue-900 rounded-xl flex items-center justify-center text-2xl font-bold mb-6">
                    1
                </div>
                <h3 class="text-xl font-bold text-gray-900">Dashboard Interaktif</h3>
                <p class="mt-3 text-gray-600 leading-relaxed">Pantau nilai KRS, KHS, transkrip, dan rekap kehadiran secara real-time langsung dari beranda Anda.</p>
            </div>
            <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100 hover:shadow-lg transition-shadow fade-in" style="transition-delay: 100ms;">
                <div class="w-14 h-14 bg-yellow-100 text-yellow-700 rounded-xl flex items-center justify-center text-2xl font-bold mb-6">
                    2
                </div>
                <h3 class="text-xl font-bold text-gray-900">Pengumpulan Tugas</h3>
                <p class="mt-3 text-gray-600 leading-relaxed">Unduh materi perkuliahan dan unggah tugas Anda langsung ke dalam sistem tanpa batas waktu jam kerja.</p>
            </div>
            <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100 hover:shadow-lg transition-shadow fade-in" style="transition-delay: 200ms;">
                <div class="w-14 h-14 bg-green-100 text-green-700 rounded-xl flex items-center justify-center text-2xl font-bold mb-6">
                    3
                </div>
                <h3 class="text-xl font-bold text-gray-900">Jadwal & Notifikasi</h3>
                <p class="mt-3 text-gray-600 leading-relaxed">Dapatkan pembaruan langsung terkait perubahan jadwal kuliah, ruang kelas, atau pengumuman kampus.</p>
            </div>
        </div>
    </div>
</section>

<section id="kalender-akademik" class="bg-gray-50 py-24 border-t border-gray-200">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto bg-white rounded-3xl border border-gray-200 p-8 md:p-12 shadow-sm fade-in">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Kalender Akademik 2025/2026</h2>
                    <p class="mt-2 text-gray-600">Pastikan Anda tidak tertinggal jadwal KRS, UTS, dan UAS. Unduh kalender resmi akademik kampus.</p>
                </div>
                <div class="flex items-center gap-4 shrink-0">
                    <a href="kalender-akademik.pdf" download class="inline-flex items-center justify-center rounded-xl bg-blue-900 px-6 py-3.5 text-white font-semibold hover:bg-blue-800 transition shadow-md">
                        Unduh PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="bg-gray-900 text-gray-300 py-16 border-t-4 border-blue-800">
    <div class="container mx-auto px-6">
        <div class="grid gap-12 md:grid-cols-12">
            <div class="md:col-span-5">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-blue-900 font-bold">
                        P
                    </div>
                    <span class="text-white font-bold text-xl tracking-tight">SERAP </span>
                </div>
                <p class="text-gray-400 mb-4 leading-relaxed">
                    Sistem Edukasi Ruang Akademik<br>
                    Politeknik Sawunggalih Aji (POLSA)
                </p>
                <p class="text-gray-400 text-sm">
                    Jl. Kemiri Raya No. 100, Kutoarjo<br>
                    Purworejo, Jawa Tengah
                </p>
            </div>

            <div class="md:col-span-3 md:col-start-7">
                <h3 class="text-lg font-bold text-white mb-6">Tautan Penting</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="https://polsa.ac.id" target="_blank" class="hover:text-yellow-400 transition">Website Resmi Polsa</a></li>
                    <li><a href="https://siap.polsa.ac.id" target="_blank" class="hover:text-yellow-400 transition">Portal SIAP Polsa</a></li>
                    <li><a href="https://pmb.polsa.ac.id" target="_blank" class="hover:text-yellow-400 transition">Penerimaan Mahasiswa Baru</a></li>
                </ul>
            </div>

            <div class="md:col-span-3">
                <h3 class="text-lg font-bold text-white mb-6">Bantuan</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="hover:text-yellow-400 transition">Panduan Mahasiswa</a></li>
                    <li><a href="#" class="hover:text-yellow-400 transition">Hubungi BAAK</a></li>
                    <li><a href="#" class="hover:text-yellow-400 transition">Lupa Password SIAKAD</a></li>
                </ul>
            </div>
        </div>

        <div class="mt-12 border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
            <p>© 2026 Politeknik Sawunggalih Aji. Hak cipta dilindungi.</p>
            <div class="mt-4 md:mt-0 space-x-4">
                <a href="#" class="hover:text-white transition">Privasi</a>
                <a href="#" class="hover:text-white transition">Syarat & Ketentuan</a>
            </div>
        </div>
    </div>
</footer>

<script>
    // Intersection Observer untuk animasi scroll yang lebih rapi (fade-in)
    document.addEventListener("DOMContentLoaded", function() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px"
        });

        document.querySelectorAll('.fade-in').forEach((el) => {
            observer.observe(el);
        });
    });
</script>
</body>
</html>