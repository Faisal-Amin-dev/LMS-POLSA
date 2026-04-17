<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
// INI BARIS YANG TADI KELUPAAN PAKDHE:
use Illuminate\Support\Facades\Auth; 

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Jika user belum login atau rolenya tidak ada di daftar yang diizinkan
        if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
            return redirect('/login')->withErrors(['loginError' => 'Anda tidak punya hak akses ke halaman tersebut.']);
        }

        return $next($request);
    }
}