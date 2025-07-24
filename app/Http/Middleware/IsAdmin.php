<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and is an admin.
        if (!auth()->check() || !auth()->user()->is_admin) {
            // If not, abort with a 403 Forbidden error.
            abort(403, 'Unauthorized Action');
        }

        return $next($request);
    }
}