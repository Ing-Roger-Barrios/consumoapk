<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ContractorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ✅ Usa el helper auth() (disponible globalmente)
        if (! Auth::check() || Auth::user()->role !== 'contractor') {
            abort(403, 'Acceso denegado. Solo contratistas pueden acceder.');
        }

        return $next($request);
    }
}
