<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * CheckRole Middleware
 * 
 * This middleware controls user access permissions to specific pages.
 * Only users with the specified role are allowed to access the page.
 */
class CheckRole
{
    /**
     * Handle an incoming request.
     * 
     * This method runs on every HTTP request and checks user permissions.
     *
     * @param  \Illuminate\Http\Request  $request - Incoming HTTP request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next - Next middleware or controller
     * @param  string  $role - Required role (e.g. 'admin', 'user')
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        // If user is not authenticated, redirect to login page
        if (!Auth::check()) {
            return redirect('/login');
        }

        // If user's role doesn't match required role, return 403 error
        if (Auth::user()->role !== $role) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        // Permission check successful, pass request to next middleware/controller
        return $next($request);
    }
}