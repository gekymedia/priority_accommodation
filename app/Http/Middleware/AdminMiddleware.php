<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user has admin role using Spatie Permission or the role field
        $isAdmin = $user->hasRole(['admin', 'super_admin']) || 
                   in_array($user->role, ['admin', 'super_admin']);

        if (!$isAdmin) {
            // Redirect non-admin users to user dashboard
            return redirect()->route('user.dashboard')->with('error', 'You do not have permission to access the admin area.');
        }

        return $next($request);
    }
}

