<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PortalAuth
{
    public function handle(Request $request, Closure $next)
    {
        // login and search routes except
        if (!$request->routeIs('portal-login') && !$request->routeIs('portal.search') && !session('student_id')) {
            return redirect()->route('portal-login');
        }

        return $next($request);
    }
}
