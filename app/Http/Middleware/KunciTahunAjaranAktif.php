<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\TahunAjaran; // Pastikan nanti kita buat Model ini jika belum ada

class KunciTahunAjaranAktif
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Posisikan query di luar Auth::check() agar guest/landing page bisa baca data
        $tahunAktif = \DB::table('tahun_ajarans')->where('is_aktif', true)->first();
        
        if (!$tahunAktif) {
            $tahunAktif = \DB::table('tahun_ajarans')->latest()->first();
        }

        if ($tahunAktif) {
            // Bagian session & View share global
            session(['tahun_ajaran_id' => $tahunAktif->id]);
            session(['tahun_akademik_nama' => $tahunAktif->tahun_akademik . ' - ' . $tahunAktif->semester]);

            View::share('semAktif', $tahunAktif);
        } else {
            View::share('semAktif', null);
        }

        return $next($request);
    }
}