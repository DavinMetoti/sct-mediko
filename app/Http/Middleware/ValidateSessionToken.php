<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidateSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Periksa apakah pengguna sedang login
        if (Auth::check()) {
            // Ambil token sesi dari database pengguna
            $user = Auth::user();

            if ($request->session()->get('session_token') !== $user->session_token) {
                // Jika token tidak cocok, logout dan redirect ke login
                Auth::logout();

                return redirect()->route('login')->withErrors([
                    'error' => 'Sesi Anda tidak valid, silakan login kembali.',
                ]);
            }

            return $next($request);
        }

        // Redirect ke halaman login jika pengguna belum login
        return redirect()->route('login');
    }
}
