<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - My Polsa LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        polsa: {
                            DEFAULT: '#FBBF24', /* Kuning Utama Logo (Setara Amber-400) */
                            hover: '#F59E0B',   /* Kuning Gelap saat disorot/hover */
                            ring: '#FDE68A',    /* Kuning Terang untuk efek garis pinggir */
                            text: '#D97706',    /* Kuning Kecoklatan khusus untuk teks link */
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .fade-in { animation: fadeIn 0.5s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white max-w-md w-full rounded-2xl shadow-xl overflow-hidden fade-in relative">
        
        <div class="h-2 w-full bg-polsa"></div>

        <div class="p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 tracking-tight">My<span class="text-polsa-hover">Polsa</span></h1>
                <p class="text-sm text-gray-500 mt-2">Sistem Informasi Akademik & LMS Terpadu</p>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6" role="alert">
                    <p class="font-medium text-sm">Gagal Masuk!</p>
                    <ul class="text-sm list-disc list-inside mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf 

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">NIM / NIDN</label>
                    <input type="text" id="username" name="username" required autocomplete="username" autofocus
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-polsa-ring focus:border-polsa transition-colors outline-none"
                        placeholder="Masukkan NIM atau NIDN Anda">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required autocomplete="current-password"
                            class="w-full px-4 py-3 pr-12 rounded-lg border border-gray-300 focus:ring-2 focus:ring-polsa-ring focus:border-polsa transition-colors outline-none"
                            placeholder="••••••••">
                        
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-polsa-hover focus:outline-none">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" 
                    class="w-full bg-polsa hover:bg-polsa-hover text-slate-900 font-bold py-3 px-4 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg focus:ring-4 focus:ring-polsa-ring">
                    Masuk 
                </button>
            </form>

            <div class="mt-8 text-center text-sm text-gray-500">
                <p>Mengalami kendala? <a href="#" class="text-polsa-text hover:underline font-bold">Hubungi Admin Kampus</a></p>
            </div>
        </div>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        const eyeOpenPath = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
        
        const eyeClosedPath = '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />';

        togglePassword.addEventListener('click', function () {
            // Cek tipe input saat ini (apakah password atau text)
            const isPassword = passwordInput.getAttribute('type') === 'password';
            
            // Ubah tipenya
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            
            // Ganti gambar ikonnya
            eyeIcon.innerHTML = isPassword ? eyeOpenPath : eyeClosedPath;
        });
    </script>
</body>
</html>