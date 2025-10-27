<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect('login');
        }

        // Get the authenticated user
        $user = Auth::user();

        // Check if the user's role is in the list of allowed roles
        if (!in_array($user->role, $roles)) {
            // Redirect to home or show an unauthorized error
            abort(403, 'Unauthorized Action');
        }

        return $next($request);
    }
}
