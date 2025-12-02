<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MedmasterySegmentation;
use Symfony\Component\HttpFoundation\Response;

class CheckSegmentationAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $segmentationId = $request->route('segmentation') ?? $request->route('id');

        if ($segmentationId) {
            $segmentation = MedmasterySegmentation::with('allowedUsers')->find($segmentationId);

            if ($segmentation) {
                // Jika ada allowed users yang ditentukan
                if ($segmentation->allowedUsers->count() > 0) {
                    // Cek apakah user saat ini ada dalam daftar allowed users
                    if (!Auth::check() || !$segmentation->allowedUsers->contains(Auth::id())) {
                        return redirect()->route('medmastery-segmentation.index')
                            ->with('error', 'Anda tidak memiliki akses ke bidang ini.');
                    }
                }
                // Jika tidak ada allowed users, semua user bisa akses (default behavior)
            }
        }

        return $next($request);
    }
}
