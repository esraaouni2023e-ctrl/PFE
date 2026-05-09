<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     * Usage in routes: ->middleware('role:student') or ->middleware('role:counselor')
     * Admin users (is_admin = true) can access all areas without restriction.
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        // Admin can access everything
        if ($user->isAdmin()) {
            return $next($request);
        }

        $allowed = array_map('trim', explode(',', $roles));

        if (! in_array($user->role, $allowed, true)) {
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}
