<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OtpAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah session 'otp_email' tersedia
        if (!$request->session()->has('otp_email')) {
            return redirect()->route('forgot-password.index')->with('error', 'Anda belum mengajukan permintaan OTP.');
        }

        return $next($request);
    }
}
