<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response{

        $requiredRole = UserRole::from($role);

        if (Auth::check() && Auth::user()->role == $requiredRole) {
            return $next($request);
        }

        abort(403, 'AKSES DITOLAK. ANDA TIDAK MEMILIKI HAK AKSES YANG SESUAI.');
    }
    
}
