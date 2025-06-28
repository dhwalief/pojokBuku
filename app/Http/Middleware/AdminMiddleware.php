<?php

// ==================== MIDDLEWARE ====================
namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== UserRole::Admin) {
            abort(403, 'Access denied. Admin only.');
        }

        return $next($request);
    }
}