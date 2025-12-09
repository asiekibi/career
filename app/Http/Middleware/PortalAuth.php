<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalAuth
{
    public function handle(Request $request, Closure $next)
    {
        // login and search routes except
        $allowedRoutes = [
            'portal-login',
            'portal.search',
            'company-portal-login',
            'company-portal.login',
            'company-portal.search'
        ];
        
        $routeName = $request->route() ? $request->route()->getName() : null;
        
        if (!in_array($routeName, $allowedRoutes)) {
            if ($request->is('company-portal*')) {
                // Company auth kontrolü - Auth::check() veya session kontrolü
                $isAuthenticated = Auth::check() && Auth::user()->role === 'company' && Auth::user()->company_approved;
                $hasSession = session('company_id') || session('student_id');
                
                if (!$isAuthenticated && !$hasSession) {
                    return redirect()->route('company-portal-login');
                }
            } elseif ($request->is('student-portal*')) {
                if (!session('student_id')) {
                    return redirect()->route('portal-login');
                }
            }
        }

        return $next($request);
    }
}
