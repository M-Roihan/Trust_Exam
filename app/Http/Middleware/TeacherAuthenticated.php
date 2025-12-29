<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeacherAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Tetap menggunakan key session milik Bapak
        if (! $request->session()->get('teacher_logged_in')) {
            return redirect()
                ->route('login')
                ->withErrors(['auth' => ' login sebagai guru untuk mengakses halaman guru.']);
        }

        $response = $next($request);

        // Tambahkan header untuk mencegah caching halaman
        if (method_exists($response, 'header')) {
            return $response
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        }

        return $response;
    }
}
