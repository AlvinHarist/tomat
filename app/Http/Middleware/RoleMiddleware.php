<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // belum login
        if (!auth()->check()) {
            abort(403);
        }

        $userRole = auth()->user()->role ?? null;

        // role tidak sesuai
        if (!$userRole || !in_array($userRole, $roles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
