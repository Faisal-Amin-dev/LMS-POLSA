<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Menghitung Statistik User
        $stats = [
            'total_mahasiswa' => User::where('role', 'mahasiswa')->count(),
            'total_dosen'     => User::where('role', 'dosen')->count(),
            'total_prodi'     => \App\Models\Prodi::count(),
            'mahasiswa_api'   => \App\Models\Mahasiswa::whereNotNull('nim')->count(), 
            'mahasiswa_local' => User::where('role', 'mahasiswa')->where('source', 'local')->count(),
        ];

        // 2. Mengecek Status API SIAP POLSA (Disimpan di cache selama 5 menit)
        $apiStatus = Cache::remember('api_siap_status', 300, function () {
            // Mengambil URL dari .env, jika kosong pakai google.com untuk tes agar hijau
            $url = env('SIAP_POLSA_API_URL', 'https://google.com'); 
            
            try {
                // Mencoba menghubungi API maksimal 3 detik
                $response = Http::timeout(3)->get($url);
                return $response->successful() ? 'online' : 'standby';
            } catch (\Exception $e) {
                return 'disconnected'; // Jika server mati atau URL salah
            }
        });

        // 3. Kirim data ke tampilan
        return view('admin.dashboard', compact('stats', 'apiStatus'));
    }
}