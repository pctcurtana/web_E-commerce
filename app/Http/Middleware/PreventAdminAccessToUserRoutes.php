<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventAdminAccessToUserRoutes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Nếu user đã login và là admin
        if (auth()->check() && auth()->user()->isAdmin()) {
            // Admin KHÔNG được vào user routes - redirect về admin dashboard
            return redirect()->route('admin.dashboard')
                ->with('info', 'Admin chỉ được sử dụng admin panel.');
        }

        return $next($request);
    }
}
