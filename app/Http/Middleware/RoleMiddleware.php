<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (session('role') !== $role) {
            abort(403, 'Không có quyền truy cập.');
        }

        return $next($request);
    }
}
