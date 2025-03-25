<?php

namespace App\Http\Middleware;

use App\Traits\HttpResponses;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    use HttpResponses;

    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check() || Auth::user()->role->name !== $role) {
            return $this->error(null, 'Unauthorized access', 401);
        }
        return $next($request);
    }
}


